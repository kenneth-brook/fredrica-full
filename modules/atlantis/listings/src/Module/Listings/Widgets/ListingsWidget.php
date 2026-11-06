<?php

namespace Module\Listings\Widgets;

/*
 * Widget: Listings
 * @Atlantis CMS
 * v 1.0
 */


class ListingsWidget extends \Atlantis\Widgets\Widget {
  
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
   
    return view("listings-admin::admin/view-widget");
  }

}
