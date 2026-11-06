<?php

namespace Module\UltimateGallery\Widgets;

/*
 * Widget: UltimateGallery
 * @Atlantis CMS
 * v 1.0
 */


class UltimateGalleryWidget extends \Atlantis\Widgets\Widget {
  
  public function title() {
    parent::title();
    
    return 'Widget title';
  }
  
  public function description() {
    parent::description();
    
    return 'widget description';
  }


  public function size() {
    parent::size();
    
    return self::SMALL;
  }
  
  public function view() {
    parent::view();
   
    return view("ultimategallery-admin::admin/view-widget");
  }

}
