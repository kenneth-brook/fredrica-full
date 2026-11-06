<?php

namespace Module\Forms\Helpers;

use Maatwebsite\Excel\Facades\Excel;
use Module\Forms\Models\Repositories\FormsResultsRepository;
use Module\Forms\Models\Repositories\FormsItemsRepository;
use Module\Forms\Helpers\Builder;

class Export {

  /**
   * total rows per request
   */
  const LIMIT_ROWS = 250;
  const EXPORT_DIR = 'resources/forms/exports/';
  const MAX_EXPORT_FORMS = 400;

  private $form_id = 0;
  private $limitForms;
  private $formItems;

  public function __construct($form_id) {
    $this->form_id = $form_id;
    $this->formItems = FormsItemsRepository::getItems($this->form_id);

    $itemsCount = $this->formItems->count();

    $this->limitForms = ceil(self::LIMIT_ROWS / $itemsCount);
  }

  public function toCSVWithAjax($from, $to, $exportName = NULL, $offset = 0, $exported = 0, $total = NULL) {

    $from = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $from . ' 00:00:01')->toDateTimeString();
    $to = \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $to . ' 23:59:59')->toDateTimeString();

    if ($total == NULL) {
      $total = FormsResultsRepository::countEntries($from, $to, $this->form_id);

      if ($total == 0) {
        return ['error' => 'Nothing to export.'];
      }
      
      if ($total >= self::MAX_EXPORT_FORMS) {
        return ['error' => 'The amount of selected data is too large. Please select shorter date range.'];
      }
    }

    $formResults = FormsResultsRepository::getResultsWithLimit($from, $to, $this->form_id, $this->limitForms, $offset)->groupBy('set_id');

    $rows = $this->makeData($formResults, $this->formItems);

    if (empty($exportName)) {

      $this->removeOldExports();

      $colNames[] = $this->makeColNames($this->formItems);

      $data = array_merge($colNames, $rows);

      $date = \Carbon\Carbon::now()->toDateTimeString();
      $exportName = $this->form_id . '_form_export_' . $date . '.csv';
      $mode = 'w';
    } else {
      $data = $rows;
      $mode = 'a';
    }

    \Storage::disk('local')->makeDirectory(self::EXPORT_DIR);

    $filepath = base_path(self::EXPORT_DIR . $exportName);

    $file = new \SplFileObject($filepath, $mode);

    foreach ($data as $fields) {
      $file->fputcsv($fields);
    }

    $file = NULL;

    $done = $formResults->isEmpty();

    return ['export_name' => $exportName,
        'download_link' => url('/').'/admin/modules/forms/download-export/' . $exportName,
        'next_offset' => $offset + $this->limitForms,
        'limit' => $this->limitForms,
        'exported' => $exported + count($rows),
        'total' => $total,
        'is_done' => $done];
  }

  /**
   * @param \Illuminate\Database\Eloquent\Collection $formItems
   * @return array
   */
  private function makeColNames($formItems) {

    $colNames = array();

    foreach ($formItems as $item) {
      array_push($colNames, $item->label);
    }

    array_push($colNames, 'Post URL', 'IP', 'Added');

    return $colNames;
  }

  /**
   * 
   * @param \Illuminate\Database\Eloquent\Collection $formResults
   * @return array
   */
  private function makeData($formResults, $formItems) {

    $data = array();

    foreach ($formResults as $form) {

      $row = array();

      foreach ($formItems as $item) {
        $val = $form->where('field_name', $item->field_name)->first();

        if (!empty($val)) {

          $field_value = unserialize($val->field_value);
          $item_value = unserialize($item->field_value);

          if (is_array($field_value)) {
            $field_value = implode("\n", $field_value);
          }

          if ($item->field_type == Builder::$_TYPE_SELECT) {             
            $field_value = self::normalizeValue1($item_value, $field_value);
          } else if ($item->field_type == Builder::$_TYPE_SELECT_COUNTRIES) {
            $field_value = self::normalizeValue2($item_value, $field_value);
          } else if ($item->field_type == Builder::$_TYPE_SELECT_STATES) {
            $field_value = self::normalizeValue2($item_value, $field_value);
          } else if ($item->field_type == Builder::$_TYPE_RADIO) {            
            $field_value = self::normalizeValue1($item_value, $field_value);
          } else if ($item->field_type == Builder::$_TYPE_CHECKBOX) {
           
          }

          $row[] = $field_value;
        } else {
          $row[] = '';
        }
      }

      if (!empty($row)) {

        $val = $form->first();

        if (!empty($val)) {

          array_push($row, $val->post_url, $val->IP, $val->created_at->toDateTimeString());

          $data[] = $row;
        }
      }
    }

    return $data;
  }

   public static function normalizeValue1($item_value, $field_value) {
    
    if (is_array($item_value)) {

      foreach ($item_value as $i_key => $i_val) {
        $rm_str = '[' . $i_key . '] => ';

        $field_value = str_replace($rm_str, '', $field_value);
      }
    }
    $field_value = substr(trim($field_value), 1, -1);
    return $field_value;
  }
  
  
  public static function normalizeValue2($item_value, $field_value) {
    
    $chunks = explode('=>', $field_value);
    
    if (isset($chunks[1])) {
      $field_value = substr(trim($chunks[1]), 1, -1);
    }
    
    return $field_value;
  }
 

  private function removeOldExports() {

    $files = \Storage::disk('local')->allFiles(self::EXPORT_DIR);

    $time = \Carbon\Carbon::now()->subMinute(15)->timestamp;

    foreach ($files as $file) {
      $fileTime = \Storage::disk('local')->lastModified($file);
      if ($fileTime < $time) {
        \Storage::disk('local')->delete($file);
      }
    }
  }

  public static function toCSV($form_id) {

    $formResults = FormsResultsRepository::getResults($form_id)->groupBy('set_id');
    $formItems = FormsItemsRepository::getItems($form_id);

    $colNames = array();

    foreach ($formItems as $item) {
      array_push($colNames, $item->label);
    }

    array_push($colNames, 'Post URL', 'IP', 'Added');

    $data[] = $colNames;

    foreach ($formResults as $form) {

      $row = array();

      foreach ($formItems as $item) {
        $val = $form->where('field_name', $item->field_name)->first();

        if (!empty($val)) {

          $field_value = unserialize($val->field_value);

          if (is_array($field_value)) {
            $field_value = implode("\n", $field_value);
          }

          $row[] = $field_value;
        }
      }

      if (!empty($row)) {
        array_push($row, $val->post_url, $val->IP, $val->created_at->toDateTimeString());

        $data[] = $row;
      }
    }

    $date = \Carbon\Carbon::now()->toDateTimeString();

    Excel::create($form_id . '_form_export_' . $date, function($excel) use($data) {

      $excel->sheet('sheet 1', function($sheet) use($data) {

        $sheet->setOrientation('landscape');
        $sheet->fromArray($data);
      });
    })->export('csv');
  }

}
