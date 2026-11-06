<?php

namespace Module\Forms\Traits;

/**
 * Helper trait for extending FormsController
 */
trait FormsTrait {

  public function __call($name, $params) {
   
    /**
     * create controller in site/src/Module/Site/Controllers/Modules/FormsController.php
     */
    if (class_exists('Module\Site\Controllers\Modules\FormsController')) {

      return \App::make('Module\Site\Controllers\Modules\FormsController')->$name($params);
    }
  }

}
