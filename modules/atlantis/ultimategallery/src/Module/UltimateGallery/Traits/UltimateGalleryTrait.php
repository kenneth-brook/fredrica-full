<?php

 namespace Module\UltimateGallery\Traits;

/**
 * Helper trait for extending UltimateGalleryController
 */
trait UltimateGalleryTrait {

  public function __call($name, $params) {

    /**
     * create controller in site/src/Module/Site/Controllers/Modules/UltimateGalleryController.php
     */
    if (class_exists('Module\Site\Controllers\Modules\UltimateGalleryController')) {

      return \App::make('Module\Site\Controllers\Modules\UltimateGalleryController')->$name($params);
    }
  }

}

