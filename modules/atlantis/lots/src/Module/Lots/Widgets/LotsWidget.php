<?php

namespace Module\Lots\Widgets;

/*
 * Widget: Lots
 * @Atlantis CMS
 * v 1.0
 */


class LotsWidget extends \Atlantis\Widgets\Widget {
  
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
   
    return view("lots-admin::admin/view-widget");
  }

}
