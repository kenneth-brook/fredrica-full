<?php

 namespace Module\Lots\Traits;

/**
 * Helper trait for extending LotsController
 */
trait LotsTrait {

  public function __call($name, $params) {

    /**
     * create controller in site/src/Module/Site/Controllers/Modules/LotsController.php
     */
    if (class_exists('Module\Site\Controllers\Modules\LotsController')) {

      return \App::make('Module\Site\Controllers\Modules\LotsController')->$name($params);
    }
  }

}

