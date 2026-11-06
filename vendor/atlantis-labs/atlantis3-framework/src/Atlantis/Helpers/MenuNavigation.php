<?php

namespace Atlantis\Helpers;

use \Atlantis\Models\Repositories\PermissionsRepository;
use \Atlantis\Models\Repositories\ModulesRepository;
/**
 * Menu navigation for admin only
 */
class MenuNavigation {

  private $is_admin = FALSE;
  
  public function __construct() {
    $this->is_admin = auth()->user()->hasRole('admin');

    if (auth()->user() != NULL) {
      \App::setLocale(auth()->user()->language);
    }
  }


  public function create() {

    $aData = array();

    $routes = \Route::getRoutes();

    $aMenuItems = array();

    $userPermissions = PermissionsRepository::getAllPermissionsForUser(auth()->user()->id);
    $aUserPerm = array();
    foreach ($userPermissions as $user_perm) {
      $aUserPerm[] = $user_perm->type;
    }

    $aUserPerm = array_unique($aUserPerm);

    foreach ($routes as $route) {

      $action = $route->getAction();

      if (isset($action['identifier']) && isset($action['name']) && $this->isAllowed($aUserPerm, $action['identifier'])) {

        $_identifier = request()->attributes->getAlnum('_identifier');

        if (isset($action['icon'])) {
          $class = $action['icon'];
        } else {
          $class = '';
        }
        
        if (isset($action['tooltip'])) {
          $tooltip = $action['tooltip'];
        } else {
          $tooltip = '';
        }

        if (isset($action['parent'])) {

          if (isset($action['parent-icon'])) {
            $parentKey = !empty($action['parent']) ? $action['parent'] : $action['parent-icon'];
            $parentClass = $action['parent-icon'];
          } else {
            $parentKey = $action['parent'];
            $parentClass = '';
          }
          
          $aMenuItems[$parentKey]['child_items'][$action['identifier']]['name'] = trans('admin::views.'.$action['name']);
          $aMenuItems[$parentKey]['child_items'][$action['identifier']]['class'] = $class;
          $aMenuItems[$parentKey]['child_items'][$action['identifier']]['tooltip'] = $tooltip;
          $aMenuItems[$parentKey]['child_items'][$action['identifier']]['url'] = '/' . $action['menu_item_url'];
          
          if ($_identifier == $action['identifier']) {
            $aMenuItems[$parentKey]['child_items'][$action['identifier']]['active'] = ' class="active"';
            $aMenuItems[$parentKey]['active'] = ' class="active"';
          } else {
            $aMenuItems[$parentKey]['child_items'][$action['identifier']]['active'] = '';
          }
          $aMenuItems[$parentKey]['name'] = $action['parent'];
          $aMenuItems[$parentKey]['parent-class'] = $parentClass;
          $aMenuItems[$parentKey]['url'] = 'javascript:void(0)';
          if (!isset($aMenuItems[$parentKey]['active'])) {
            $aMenuItems[$parentKey]['active'] = '';
          }
          $aMenuItems[$parentKey]['is_parent'] = TRUE;
          
        } else {

          $aMenuItems[$action['identifier']]['name'] = trans('admin::views.'.$action['name']);
          $aMenuItems[$action['identifier']]['class'] = $class;
          $aMenuItems[$action['identifier']]['tooltip'] = $tooltip;
          $aMenuItems[$action['identifier']]['url'] = '/' . $action['menu_item_url'];
          if ($_identifier == $action['identifier']) {
            $aMenuItems[$action['identifier']]['active'] = ' class="active"';
          } else {
            $aMenuItems[$action['identifier']]['active'] = '';
          }
          $aMenuItems[$action['identifier']]['is_parent'] = FALSE;
        }
      }
    }
    
    $aData['aMenuItems'] = $aMenuItems;

    return view('atlantis-admin::helpers/menu-navigation', $aData);
  }

  public static function set() {

    $navigation = new self();

    return $navigation->create();
  }

  private function isAllowed($aUserPerm, $identifier) {

    $allow = FALSE;

    if ($this->is_admin || in_array($identifier, $aUserPerm)) {
      $allow = TRUE;
    }

    return $allow;
  }

  public static function sortPatternsByType($oPatterns, $url)
  {

    $aPatterns = array();
    $aPatterns['count'] = count($oPatterns);

    $s = 0;
    $e = 0;
    $c = 0;
    foreach ($oPatterns as $patt) {


            //page specific
      $sp = str_replace(':any', '', $patt->mask);
      if ($patt->mask == $url || (!empty($sp) && starts_with($url, $sp))) {
        $aPatterns['specific'][$s]['id'] = $patt->id;
        $aPatterns['specific'][$s]['name'] = $patt->name;
        $aPatterns['specific'][$s]['mask_id'] = $patt->mask_id;
        $aPatterns['specific'][$s]['status'] = $patt->status;
        $s++;
      }

            //excluded
      $ex = str_replace(':all', '', $patt->mask);
      if ($patt->mask == '!' . $url || (!empty($ex) && starts_with('!' . $url, $ex))) {
        $aPatterns['excluded'][$e]['id'] = $patt->id;
        $aPatterns['excluded'][$e]['name'] = $patt->name;
        $aPatterns['excluded'][$e]['mask_id'] = $patt->mask_id;
        $aPatterns['excluded'][$e]['status'] = $patt->status;
        $e++;
      }

            //common
      if ($patt->mask == ':any') {
        $aPatterns['common'][$c]['id'] = $patt->id;
        $aPatterns['common'][$c]['name'] = $patt->name;
        $aPatterns['common'][$c]['mask_id'] = $patt->mask_id;
        $aPatterns['common'][$c]['status'] = $patt->status;
        $c++;
      }
    }

    return $aPatterns;
  }

  public static function setShortcutBar() {

    $aData['page'] = \Illuminate\Support\Facades\View::shared('_page');
    
    if (auth()->user()) {
      
      $aModules = array();
      $aUserPerm = array();
      
      
      $oPatterns = \Atlantis\Models\Repositories\PatternRepository::getAllPerPage(str_replace(':any', '', $aData['page']->url));        
      $aData['aPatterns'] = self::sortPatternsByType($oPatterns, $aData['page']->url);

      $userPermissions = PermissionsRepository::getAllModulesPermissionsForUser(auth()->user()->id);
      foreach ($userPermissions as $user_perm) {
        $aUserPerm[] = $user_perm->value;
      }

      $aUserPerm = array_unique($aUserPerm);


      $modules = ModulesRepository::getAllModules()->toArray();
      foreach ($modules as $module)
      {
        if (auth()->user()->hasRole('admin') || in_array($module['namespace'], $aUserPerm))
        {
          $aModules[] = $module;
        }
      }
      
      $aData['aModules'] = $aModules;
    }
    
    return view('atlantis-admin::helpers/shortcut-bar', $aData);
    
  }

}
