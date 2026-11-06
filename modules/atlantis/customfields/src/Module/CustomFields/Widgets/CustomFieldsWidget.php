<?php

namespace Module\CustomFields\Widgets;

/*
 * Widget: CustomFields
 * @Atlantis CMS
 * v 1.0
 */


class CustomFieldsWidget extends \Atlantis\Widgets\Widget {
  
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
   
    return view("customfields-admin::admin/view-widget");
  }

}
