<?php

 namespace Module\VanityUrl\Traits;

/**
 * Helper trait for extending EventsController
 */
trait VanityUrlTrait {

  public function __call($name, $params) {

    /**
     * create controller in site/src/Module/Site/Controllers/Modules/EventsController.php
     */
    if (class_exists('Module\Site\Controllers\Modules\VanityUrlController')) {

      return \App::make('Module\Site\Controllers\Modules\VanityUrlController')->$name($params);
    }
  }

}

