<?php

namespace Module\Forms\Helpers;

use Illuminate\Support\Facades\Validator as BaseValidator;
use Illuminate\Support\MessageBag;
use Module\Forms\Helpers\Builder as FormsBuilder;

class Validator {

    private $oItems;
    private $rules = array();
    private $messages = array();
    private $baseValidator;
    private $fails = TRUE;

    public function __construct($oItems) {

        $this->oItems = $oItems;
        $this->setRules();

    }

    public function make($data) {

        $aData = array();
        
        foreach ($data as $key => $field_value) {
            $item = $this->oItems->where('field_name', $key)->first();

            if (!empty($item)) {

                $item_value = unserialize($item->field_value);           

                if (is_array($field_value)) {
                    $field_value = implode("\n", $field_value);
                }

                if ($item->field_type == FormsBuilder::$_TYPE_SELECT) {
                    $aData[$item->field_name] = \Module\Forms\Helpers\Export::normalizeValue1($item_value, $field_value);
                } else if ($item->field_type == FormsBuilder::$_TYPE_SELECT_COUNTRIES) {
                    $aData[$item->field_name] = \Module\Forms\Helpers\Export::normalizeValue2($item_value, $field_value);
                } else if ($item->field_type == FormsBuilder::$_TYPE_SELECT_STATES) {
                    $aData[$item->field_name] = \Module\Forms\Helpers\Export::normalizeValue2($item_value, $field_value);
                } else if ($item->field_type == FormsBuilder::$_TYPE_RADIO) {
                    $aData[$item->field_name] = \Module\Forms\Helpers\Export::normalizeValue1($item_value, $field_value);
                } else {
                    $aData[$item->field_name] = $field_value;
                }
            }
        }
        
        foreach ($this->oItems as $it) {
            if(!isset($aData[$it->field_name])) {
                $aData[$it->field_name] = '';
            }
        }

        $this->baseValidator = BaseValidator::make($aData, $this->rules);
        $this->fails = $this->baseValidator->fails();

    }

    public function getErrors(MessageBag $messageBag) {

        $errors = $this->baseValidator->errors();
        foreach ($this->messages as $key => $message) {

            if ($errors->has($key)) {
                $messageBag->add($key, $message);
            }
        }

        return $messageBag;

    }

    public function fails() {

        return $this->fails;

    }

    private function setRules() {

        foreach ($this->oItems as $item) {

            if (!empty($item->validation)) {
                $atts = unserialize($item->attributes);

                if (!strpos($item->validation, "required") && $item->field_type === 'datepicker' && array_key_exists("format", $atts)) {
                    $item->validation = "date_format:{$atts['format']}";
                } else if (strpos($item->validation, "required") && $item->field_type === 'datepicker' && array_key_exists("format", $atts)) {
                    $item->validation = "date_format:{$atts['format']}|required";
                }

                $this->rules[$item->field_name] = $item->validation;
                $this->messages[$item->field_name] = $item->validation_msg;
            }
        }

    }

}
