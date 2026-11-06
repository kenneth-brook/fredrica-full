<?php

namespace Module\Forms\Models\Repositories;

use Module\Forms\Models\Forms;
use Illuminate\Support\Facades\Validator;
use Module\Forms\Models\Repositories\FormsItemsRepository;
use Module\Forms\Helpers\Builder;

class FormsRepository {

  const DEFAULT_FLOOD_CONTROL_TIME = 60;
  const DEFAULT_FLOOD_CONTROL_ERROR_MSG = 'Flood Control is enabled. Please wait 60 seconds.';

  /**
   *  Validation rules for create
   *
   * @var array
   */
  private $rules_create = [
      'name' => 'required',
      'btn_value' => 'required',
      'email_from' => 'email',
      'field_value' => 'valid_checkbox|valid_select|valid_radio'
  ];

  public function validationCreate($data) {
    $messages = [
        'required' => trans('forms::validation.required'),
        'email' => trans('forms::validation.email'),
        'valid_checkbox' => trans('forms::validation.valid_checkbox'),
        'valid_select' => trans('forms::validation.valid_select'),
        'valid_radio' => trans('forms::validation.valid_radio')
    ];

    return Validator::make($data, $this->rules_create, $messages);
  }

  public function validationEdit($data) {
    $messages = [
        'required' => trans('forms::validation.required'),
        'email' => trans('forms::validation.email'),
        'valid_checkbox' => trans('forms::validation.valid_checkbox'),
        'valid_select' => trans('forms::validation.valid_select'),
        'valid_radio' => trans('forms::validation.valid_radio')
    ];

    return Validator::make($data, $this->rules_create, $messages);
  }

  public static function validationExport($data) {

    $rules = [
        'from_date' => 'date_format:m/d/Y',
        'to_date' => 'date_format:m/d/Y'
    ];

    $messages = [
        'date_format' => 'The field does not match the format M/D/Y'
    ];
    return Validator::make($data, $rules, $messages);
  }

  public function add($data) {
    $data = $this->fitData($data);

    $model = Forms::create($data);

    foreach ($data['items'] as $item) {

      $item['form_id'] = $model->id;
      FormsItemsRepository::add($item);
    }

    return $model->id;
  }

  public function edit($id, $data) {
    $data = $this->fitData($data);

    $model = Forms::findOrNew($id);

    if ($model != NULL) {

      $model->update($data);

      FormsItemsRepository::edit($id, $data['items']);

      return TRUE;
    } else {
      return FALSE;
    }
  }

  private function fitData($data) {

    $itemsNames = \Module\Forms\Controllers\Admin\FormsAdminController::getItemsNames();

    $aItems = array();

    foreach ($itemsNames as $item_name) {
      if (isset($data[$item_name]) && is_array($data[$item_name])) {
        foreach ($data[$item_name] as $item_k => $item_v) {
          if ($item_name == 'attributes') {
            $aAttrs = array_filter(explode("\n", $item_v));
            $aAtt = array();
            foreach ($aAttrs as $attr) {
              $att = explode('=>', $attr);
              if (isset($att[0]) && isset($att[1])) {
                $aAtt[trim($att[0])] = htmlentities(trim($att[1]));
              } else if (isset($att[0]) && !isset($att[1])) {
                $aAtt[trim($att[0])] = '';
              }
            }
            $aItems[$item_k][$item_name] = serialize($aAtt);
          } else if ($item_name == 'field_value') {
            if (strpos($item_v, '=>') !== FALSE) {
              $aVals = array_filter(explode("\n", $item_v));
              $aVal = array();
              foreach ($aVals as $val) {
                $att = explode('=>', $val);
                if (isset($att[0]) && isset($att[1])) {
                  $aVal[trim($att[0])] = htmlentities(trim($att[1]));
                }
              }

              $aItems[$item_k][$item_name] = serialize($aVal);
            } else {
              $aItems[$item_k][$item_name] = serialize($item_v);
            }
          } else if ($item_name == 'use_reply_to') {
            $aItems[$item_k][$item_name] = 1;
          } else if ($item_name == 'use_email_from') {
            $aItems[$item_k][$item_name] = 1;
          } else {
            $aItems[$item_k][$item_name] = $item_v;
          }
        }
        unset($data[$item_name]);
      }
    }
    $data['items'] = $aItems;
    return $data;
  }

  public static function getAll() {

    return Forms::all();
  }

  public static function get($id) {

    return Forms::find($id);
  }

  public static function deleteEntry($id) {

    $model = Forms::findOrNew($id);

    if ($model != NULL) {
      $model->delete();
      FormsItemsRepository::deleteItems($id);
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function validCheckbox($attribute, $value, $parameters, $validator) {

    $aFieldTypes = request()->get('field_type');

    foreach ($aFieldTypes as $key => $type) {
      if ($type == Builder::$_TYPE_CHECKBOX) {

        $val = $value[$key];

        $rows = explode("\n", $val);

        foreach ($rows as $row) {

          $aVal = explode('=>', $row);

          if (!isset($aVal[1])) {
            return FALSE;
          }
        }
      }
    }
    return TRUE;
  }

  public function validSelect($attribute, $value, $parameters, $validator) {

    $aFieldTypes = request()->get('field_type');

    foreach ($aFieldTypes as $key => $type) {
      if ($type == Builder::$_TYPE_SELECT) {

        $val = $value[$key];

        $rows = explode("\n", $val);

        foreach ($rows as $row) {

          $aVal = explode('=>', $row);

          if (!isset($aVal[1])) {
            return FALSE;
          }
        }
      }
    }
    return TRUE;
  }

  public function validRadio($attribute, $value, $parameters, $validator) {

    $aFieldTypes = request()->get('field_type');

    foreach ($aFieldTypes as $key => $type) {
      if ($type == Builder::$_TYPE_RADIO) {

        $val = $value[$key];

        $rows = explode("\n", $val);

        foreach ($rows as $row) {

          $aVal = explode('=>', $row);

          if (!isset($aVal[1])) {
            return FALSE;
          }
        }
      }
    }
    return TRUE;
  }

  public function validZipUsCa($attribute, $value, $parameters, $validator) {

    $US = "^\d{5}([\-]?\d{4})?$";
    $CA = "^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ])\ {0,1}(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$";

    if (preg_match("/" . $US . "/i", $value) || preg_match("/" . $CA . "/i", $value)) {
      //Validation passed, provided zip/postal code is valid.
      return TRUE;
    } else {
      //Validation failed, provided zip/postal code is not valid.
      return FALSE;
    }
  }
  
  public function validPhone($attribute, $value, $parameters, $validator) {

    return preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value) && strlen($value) >= 10;
  }

}
