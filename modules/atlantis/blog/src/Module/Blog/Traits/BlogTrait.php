<?php

namespace Module\Blog\Traits;

/**
 * Helper trait for extending BlogController
 */
trait BlogTrait {

  public function __call($name, $params) {
   
    /**
     * create controller in site/src/Module/Site/Controllers/Modules/BlogController.php
     */
    
    if (class_exists('Module\Site\Controllers\Modules\BlogController')) {

      return \App::make('Module\Site\Controllers\Modules\BlogController')->$name($params);
    }
  }

}
