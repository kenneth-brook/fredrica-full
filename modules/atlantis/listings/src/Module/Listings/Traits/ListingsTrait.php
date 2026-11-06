<?php

 namespace Module\Listings\Traits;

/**
 * Helper trait for extending ListingsController
 */
trait ListingsTrait {

  public function __call($name, $params) {

    /**
     * create controller in site/src/Module/Site/Controllers/Modules/ListingsController.php
     */
    if (class_exists('Module\Site\Controllers\Modules\ListingsController')) {

      return \App::make('Module\Site\Controllers\Modules\ListingsController')->$name($params);
    }
  }

}

