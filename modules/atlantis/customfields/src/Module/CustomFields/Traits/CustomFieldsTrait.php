<?php

 namespace Module\CustomFields\Traits;

/**
 * Helper trait for extending CustomFieldsController
 */
trait CustomFieldsTrait {

  public function __call($name, $params) {

    /**
     * create controller in site/src/Module/Site/Controllers/Modules/CustomFieldsController.php
     */
    if (class_exists('Module\Site\Controllers\Modules\CustomFieldsController')) {

      return \App::make('Module\Site\Controllers\Modules\CustomFieldsController')->$name($params);
    }
  }

}

