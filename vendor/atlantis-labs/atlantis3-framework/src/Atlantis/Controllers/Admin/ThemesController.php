<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminController;
use Atlantis\Helpers\Themes\ThemeTools;
use Illuminate\Http\Request;
use Atlantis\Models\Repositories\ConfigRepository;

class ThemesController extends AdminController {

  public function __construct() {

    parent::__construct(self::$_ID_THEMES);
  }

  public function getIndex() {

    $aData = array();

    if (\Session::get('info') != NULL) {
      $aData['msgInfo'] = \Session::get('info');
    }

    if (\Session::get('success') != NULL) {
      $aData['msgSuccess'] = \Session::get('success');
    }

    if (\Session::get('error') != NULL) {
      $aData['msgError'] = \Session::get('error');
    }

    $themeConfigs = ThemeTools::getAllConfigs();

    $aThemes[1] = array();
    $aThemes[2] = array();
    $aThemes[3] = array();
    $aThemes[4] = array();

    $row = 1;

    $i = 0;

    foreach ($themeConfigs as $path => $config) {

      if (isset($config['name']) && isset($config['version'])) {

        $aThemes[$row][$i]['config'] = $config;
        $aThemes[$row][$i]['path'] = $path;
        
        if (config('atlantis.theme_path') == $path) {        
        $aThemes[$row][$i]['active'] = TRUE;        
        } else {
          $aThemes[$row][$i]['active'] = FALSE;
        }

        $i++;

        if ($row == 4) {
          $row = 1;
        } else {
          $row++;
        }
      }
    }
    
    $aData['aThemes'] = $aThemes;
    $aData['count_installed'] = count($aThemes[1]) + count($aThemes[2]) + count($aThemes[3]) + count($aThemes[4]);

    return view('atlantis-admin::themes', $aData);
  }
  
  public function getDetails($name = NULL) {
    
    $aData = array();
    
    if (\Session::get('info') != NULL) {
      $aData['msgInfo'] = \Session::get('info');
    }

    if (\Session::get('success') != NULL) {
      $aData['msgSuccess'] = \Session::get('success');
    }

    if (\Session::get('error') != NULL) {
      $aData['msgError'] = \Session::get('error');
    }

    if (\Session::has('error_page') != NULL) {
      $aData['error_page'] = \Session::get('error_page');
    }
    if (\Session::has('error_pattern') != NULL) {
      $aData['error_pattern'] = \Session::get('error_pattern');
    }
    if (\Session::has('resource_created') != NULL) {
      $aData['resource_created'] = \Session::get('resource_created');
    }
    
    $aData['themeConfig'] = ThemeTools::getThemeConfigByUrlName($name);

    $path = $aData['themeConfig']['_theme_path'];
    
    if (config('atlantis.theme_path') == $path) {        
      $aData['theme']['active'] = TRUE;        
    } else {
      $aData['theme']['active'] = FALSE;
    }
    
    return view('atlantis-admin::themes-details', $aData);
    
  }


  public function postActivateTheme(Request $request) {
    
    if ($request->get('theme_path') != NULL) {
      $oConfig = new ConfigRepository();
      $oConfig->addConfig('theme_path', $request->get('theme_path'));
      
      \Session::flash('success',  trans('admin::messages.Theme was activated'));      
    }
    return redirect()->back();
  }
  
   public static function postDeactivateTheme(Request $request) {
     
    if ($request->get('theme_path') != NULL) {
      $oConfig = new ConfigRepository();
      
      $oConfig->addConfig('theme_path', NULL);
      
      \Session::flash('success',  trans('admin::messages.Theme was deactivated'));      
    }
    return redirect()->back();
  }

   /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateResource(\Illuminate\Http\Request $request)
    {
        $themePath = $request->get('theme_path');        
        $allFileSetup = ThemeTools::getAllConfigs();
        foreach ($allFileSetup as $path => $config) {
          if ($path == $themePath) {
            $fileSetup = $config;
            break;
          }
        }
        
        $pageResponse = array();
        $patternResponse = array();
        $patternResponse['success'] = array();
        $pageResponse['success'] = array();

        if (isset($fileSetup['create_pages']) && !empty($fileSetup['create_pages'])) {
            foreach ($fileSetup['create_pages'] as $create_page) {
                $data = $create_page;
                $data['author'] = auth()->user()->name;
                $data['user'] = auth()->user()->id;
                $data['language'] = isset($create_page['language']) ? $create_page['language'] : config('atlantis.default_language');

                $content = NULL;

                if (isset($create_page['body'])) {

                  if (is_file($themePath.'/'.$create_page['body'])) {

                    $content = file_get_contents($themePath.'/'.$create_page['body']);

                  }
                  else{

                    $content = $create_page['body'];

                  }

                }

                $data['page_body'] = $content;
                //Create page
                $PageData = \Pages::create($data);
                if(is_integer($PageData))
                {
                  $pageResponse['success'][] = $data['name'];
                }
                if(is_array($PageData)){
                  foreach($PageData as $key=>$message){
                    $pageResponse['error'][][$key] = $data[$key] . ' - ' . $message[0];
                  }
                }
            }
        } else {
            $PageData = null;
        }

        if (isset($fileSetup['create_patterns']) && !empty($fileSetup['create_patterns'])) {
            foreach ($fileSetup['create_patterns'] as $create_pattern) {

                $pattern_data = $create_pattern;

                 $content = NULL;

                if (isset($create_pattern['text'])) {

                  if (is_file($themePath.'/'.$create_pattern['text'])) {

                    $content = file_get_contents($themePath.'/'.$create_pattern['text']);

                  }
                  else{

                    $content = $create_pattern['text'];

                  }

                }

                $pattern_data['text'] = $content;

                /*create pattern*/
                $iPatterns = \Patterns::create($pattern_data);

                if(is_integer($iPatterns))
                {
                  $patternResponse['success'][] = $pattern_data['name'];
                }
                
                if(is_array($iPatterns)){
                  foreach($iPatterns as $key=>$message){
                    $patternResponse['error'][][$key] = $pattern_data[$key] . ' - ' . $message[0];
                  }
                }
            }
        } else {
            $iPatterns = null;
        }
        $success_resource_created  = array_merge($patternResponse['success'] , $pageResponse['success']);

        /**
         * Page and Pattern Errors
         */
        if (isset($pageResponse['error'])) {
            
            \Session::flash('error_page', $pageResponse['error']);

        }
        if (isset($patternResponse['error'])) {
            
            \Session::flash('error_pattern', $patternResponse['error']);

        }
        if (count($success_resource_created)) {

          \Session::flash('resource_created', $success_resource_created);

        }

        return redirect()->back();
    }

}
