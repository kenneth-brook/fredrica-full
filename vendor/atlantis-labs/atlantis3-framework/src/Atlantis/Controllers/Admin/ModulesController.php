<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminController;
use Atlantis\Models\Repositories\ModulesRepository;
use Atlantis\Helpers\Modules\Updater as ModuleUpdater;
use Atlantis\Helpers\Tools;

class ModulesController extends AdminController {

  private $is_admin = FALSE;

  public function __construct() {

    parent::__construct(self::$_ID_MODULES);

    if (!empty(auth()->user())) {
      $this->is_admin = auth()->user()->hasRole('admin');
    }
  }

  public function getIndex() {

    $aData = array();

    if (\Session::has('info') != NULL) {
      $aData['msgInfo'] = \Session::get('info');
    }

    if (\Session::has('success') != NULL) {
      $aData['msgSuccess'] = \Session::get('success');
    }

    if (\Session::has('error') != NULL) {
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


    $userPermissions = \Atlantis\Models\Repositories\PermissionsRepository::getAllModulesPermissionsForUser(auth()->user()->id);
    $aUserPerm = array();
    foreach ($userPermissions as $user_perm) {
      $aUserPerm[] = $user_perm->value;
    }

    $aUserPerm = array_unique($aUserPerm);

    $modules = ModulesRepository::getAllModules()->toArray();

    $aMod = array();
    $aModProtected = array();

    $aData['needUpdate'] = FALSE;

    foreach ($modules as $module) {

      if ($this->isAllowed($aUserPerm, $module['namespace'])) {

        $fileSetup = Tools::getModuleFileSetup($module['path']);

        if ($this->isChangedSetup($module, $fileSetup)) {
          $aData['needUpdate'] = TRUE;
        }

        if (isset($fileSetup['create_pages']) || isset($fileSetup['create_patterns']))  {
          
          $module['create_resources'] = TRUE;
        }

        if ($module['icon'] != NULL || !empty($module['icon'])) {

          if (isset($fileSetup['protected']) && $fileSetup['protected']) {
            $module['protected'] = $fileSetup['protected'];
            $aModProtected[] = $module;
          } else {
            $aMod[] = $module;
          }
        }
      }
    }

    $aMod = ModuleUpdater::checkNewVersions($aMod);

    $aMod = array_merge($aModProtected, $aMod);

    $aModules[1] = array();
    $aModules[2] = array();
    $aModules[3] = array();

    $row = 1;

    foreach ($aMod as $mod) {

      $aModules[$row][] = $mod;

      if ($row == 3) {
        $row = 1;
      } else {
        $row++;
      }
    }

    //dd($aModules);
    $aData['aModules'] = $aModules;
    $aData['count_installed'] = count($aModules[1]) + count($aModules[2]) + count($aModules[3]);

    $aNotInstalled[1] = array();
    $aNotInstalled[2] = array();
    $aNotInstalled[3] = array();

    $row = 1;

    foreach ($this->getNotInstalledModules() as $module) {

      $aNotInstalled[$row][] = $this->fitModuleSetup($module);

      if ($row == 3) {
        $row = 1;
      } else {
        $row++;
      }
    }

    $aData['aNotInstalledModules'] = $aNotInstalled;
    $aData['count_notinstalled'] = count($aNotInstalled[1]) + count($aNotInstalled[2]) + count($aNotInstalled[3]);
    $aData['canEditModules'] = $this->canEditModules();

    return view('atlantis-admin::modules', $aData);
  }

  public function getActivateModule($id = NULL) {

    if ($this->canEditModules()) {

      $module = ModulesRepository::getInstalledModuleByID($id);

      if ($module != NULL) {

        $module->active = 1;
        $module->save();

        \Session::flash('success', trans('admin::messages.Module activated', ['name' => $module->name]));
      } else {
        \Session::flash('error', 'Invalid module');
      }
    }
    return redirect()->back();
  }

  public function getDeactivateModule($id = NULL) {

    if ($this->canEditModules()) {

      $module = ModulesRepository::getInstalledModuleByID($id);

      if ($module != NULL) {

        $module->active = 0;
        $module->save();

        \Session::flash('success', trans('admin::messages.Module deactivated', ['name' => $module->name]));
      } else {
        \Session::flash('error', trans('admin::messages.Invalid module'));
      }
    }
    return redirect()->back();
  }

  public function getUpdateModulesSetup() {

    $modules = ModulesRepository::getAllModules();

    foreach ($modules as $module) {

      $fileSetup = Tools::getModuleFileSetup($module->path);

      if ($this->isChangedSetup($module, $fileSetup)) {

        $fileSetup['extra'] = serialize($fileSetup['extra']);
        $module->update($fileSetup);
      }
    }

    \Session::flash('success', trans('admin::messages.Success'));

    return redirect()->back();
  }

  public function postInstall(\Illuminate\Http\Request $request) {

    if ($this->canEditModules()) {
      $moduleSetup = Tools::getModuleFileSetup($request->get('module_path'));

      $installer = new \Atlantis\Helpers\Modules\Installer();
      $installer->install($moduleSetup);

      \Session::flash('success', trans('admin::messages.Module installed', ['name' =>  $moduleSetup['name']]));
    }
    return redirect()->back();
  }

  public function postUninstall($id = NULL) {

    if ($this->canEditModules()) {

      $uninstaller = new \Atlantis\Helpers\Modules\Uninstaller($id);
      $uninstaller->uninstall();

      $messages = '';

      foreach ($uninstaller->getMessages() as $message) {
        $messages .= $message . '<br>';
      }

      if ($uninstaller->isSuccessful()) {
        \Session::flash('success', $messages);
      } else {
        \Session::flash('error', $messages);
      }
    }

    return redirect()->back();
  }

  public function postUpdate(\Illuminate\Http\Request $request) {

    if (Tools::isWritableDir(Tools::getParentFolderPath(config('atlantis.modules_dir') . $request->get('path')), TRUE)) {

      $result = ModuleUpdater::downloadFile($request);

      if (isset($result['success'])) {

        $res = ModuleUpdater::replaceModule($request, $result['success']['filename']);

        if (isset($res['error'])) {
          \Session::flash('error', $res['error']);
        } else {
          \Session::flash('success', 'Update completed');
        }
      } else if (isset($result['error'])) {
        \Session::flash('error', $result['error']);
      } else {
        \Session::flash('error', trans('admin::messages.Unexpected error'));
      }
    } else {
      \Session::flash('error', trans('admin::messages.Permissions denied'));
    }
    return redirect()->back();
  }

  private function isChangedSetup($module, $fileSetup) {

    $changed = FALSE;

    if (isset($fileSetup['name']) && $module['name'] != $fileSetup['name']) {
      $changed = TRUE;
    } else if (isset($fileSetup['author']) && $module['author'] != $fileSetup['author']) {
      $changed = TRUE;
    } else if (isset($fileSetup['version']) && $module['version'] != $fileSetup['version']) {
      $changed = TRUE;
    } else if (isset($fileSetup['moduleNamespace']) && $module['namespace'] != $fileSetup['moduleNamespace']) {
      $changed = TRUE;
    } else if (isset($fileSetup['path']) && $module['path'] != $fileSetup['path']) {
      $changed = TRUE;
    } else if (isset($fileSetup['provider']) && $module['provider'] != $fileSetup['provider']) {
      $changed = TRUE;
    } else if (isset($fileSetup['extra']) && $module['extra'] != serialize($fileSetup['extra'])) {
      $changed = TRUE;
    } else if (isset($fileSetup['adminURL']) && $module['adminURL'] != $fileSetup['adminURL']) {
      $changed = TRUE;
    } else if (isset($fileSetup['icon']) && $module['icon'] != $fileSetup['icon']) {
      $changed = TRUE;
    } else if (isset($fileSetup['description']) && $module['description'] != $fileSetup['description']) {
      $changed = TRUE;
    }

    return $changed;
  }

  private function fitModuleSetup($fileSetup) {

    if (!isset($fileSetup['name'])) {
      $fileSetup['name'] = NULL;
    }
    if (!isset($fileSetup['author'])) {
      $fileSetup['author'] = NULL;
    }
    if (!isset($fileSetup['version'])) {
      $fileSetup['version'] = NULL;
    }
    if (!isset($fileSetup['moduleNamespace'])) {
      $fileSetup['moduleNamespace'] = NULL;
    }
    if (!isset($fileSetup['path'])) {
      $fileSetup['path'] = NULL;
    }
    if (!isset($fileSetup['provider'])) {
      $fileSetup['provider'] = NULL;
    }
    if (!isset($fileSetup['extra'])) {
      $fileSetup['extra'] = NULL;
    }
    if (!isset($fileSetup['adminURL'])) {
      $fileSetup['adminURL'] = NULL;
    }
    if (!isset($fileSetup['icon'])) {
      $fileSetup['icon'] = NULL;
    }
    if (!isset($fileSetup['description'])) {
      $fileSetup['description'] = NULL;
    }

    return $fileSetup;
  }

  private function getNotInstalledModules() {

    $aNotInstalled = array();

    if ($this->canEditModules()) {

      $installer = new \Atlantis\Helpers\Modules\Installer();
      $available = $installer->showAvailableModules();

      foreach ($available as $details) {

        if (!$installer->isInstalled($details[0]['moduleNamespace'])) {

          $aNotInstalled[] = $details[0];
        }
      }
    }
    return $aNotInstalled;
  }

  private function isAllowed($aUserPerm, $namespace) {

    $allow = FALSE;

    if ($this->is_admin || in_array($namespace, $aUserPerm)) {
      $allow = TRUE;
    }

    return $allow;
  }

  private function canEditModules() {
    return $this->is_admin;
  }

  /**
   * Modules Repository
   */
  public function getRepository() {

    if (!$this->is_admin) {
      return redirect(url('/').'/admin/modules');
    }
    
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

    return view('atlantis-admin::modules-repository', $aData);
  }

  public function postDownloadInstall(\Illuminate\Http\Request $request) {

    if (!$this->is_admin) {
      return redirect('admin/modules');
    }
    
    if (Tools::isWritableDir(Tools::getParentFolderPath(config('atlantis.modules_dir') . $request->get('path')), TRUE)) {

      /**
       * download module from repository
       */
      $result = ModuleUpdater::downloadFile($request);

      if (isset($result['success'])) {

        /**
         * move module in modules folder
         */
        $res = ModuleUpdater::moveModule($request, $result['success']['filename']);

        if (isset($res['error'])) {
          \Session::flash('error', $res['error']);
        } else {

          /**
           * install module
           */
          $moduleSetup = Tools::getModuleFileSetup($request->get('path'));

          $installer = new \Atlantis\Helpers\Modules\Installer();
          $installer->install($moduleSetup);

          \Session::flash('success', trans('admin::messages.Module installed', ['name' =>  $moduleSetup['name']]));
        }
      } else if (isset($result['error'])) {
        \Session::flash('error', $result['error']);
      } else {
        \Session::flash('error', trans('admin::messages.Unexpected error'));
      }
    } else {
      \Session::flash('error', trans('admin::messages.Permissions denied'));
    }
    return redirect()->back();
  }

  public function postUpdateKeys(\Illuminate\Http\Request $request) {

    if (!$this->is_admin) {
      return redirect('admin/modules');
    }
    
    $keys = explode("\n", $request->get('modules_keys'));

    foreach ($keys as $k => $v) {

      $keys[$k] = trim($v);
    }

    $keys = array_unique(array_filter($keys));

    $config = new \Atlantis\Models\Repositories\ConfigRepository();

    $config->addConfig('modules_keys', $keys);

    \Session::flash('success', trans('admin::messages.Keys updated'));

    \Atlantis\Helpers\Cache\AtlantisCache::clearAll();

    return redirect()->back();
  }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreatePage(\Illuminate\Http\Request $request)
    {
        $aPageData = $request->all();

        $module = \Atlantis\Models\Repositories\ModulesRepository::getInstalledModuleByID($aPageData['iModuleId']);

        $fileSetup = \Atlantis\Helpers\Tools::getModuleFileSetup($module['path']);
        
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
            
            //\Session::flash('error_page', $pageResponse['error']);

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
