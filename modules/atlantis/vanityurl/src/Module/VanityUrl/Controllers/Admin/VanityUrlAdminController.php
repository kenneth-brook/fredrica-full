<?php

namespace Module\VanityUrl\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminModulesController;
use Illuminate\Http\Request;
use Module\VanityUrl\Models\Repositories\VanityUrlRepository;

class VanityUrlAdminController extends AdminModulesController {

  public function __construct() {
    parent::__construct(\Config::get('vanityurl.setup'));
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

    return view("vanityurl-admin::admin/list", $aData);
  }

  public function getAdd() {

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

    return view("vanityurl-admin::admin/add", $aData);
  }

  public function postAdd(Request $request) {

    $model = new VanityUrlRepository();

    $data = $request->all();

    $validator = $model->validationCreate($data);

    if (!$validator->fails()) {

      $id = $model->add($data);

      \Session::flash('success', 'Vanity URL ' . $data['source_url'] . ' was created');

      if ($request->get('_update')) {
        return redirect('admin/modules/vanityurl/edit/' . $id);
      } else {
        return redirect('admin/modules/vanityurl');
      }
    } else {

      return redirect()->back()->withErrors($validator)->withInput();
    }
  }

  public function getEdit($id = NULL) {

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

    $model = VanityUrlRepository::get($id);

    if ($model != NULL) {

      $aData['model'] = $model;
    } else {
      $aData['invalid_item'] = 'This item is not valid';
    }

    return view("vanityurl-admin::admin/edit", $aData);
  }

  public function postEdit($id = NULL, Request $request) {

    if ($id != NULL) {

      $model = new VanityUrlRepository();

      $data = $request->all();

      $validator = $model->validationCreate($data, $id);

      if (!$validator->fails()) {

        $model->updateURL($id, $data);

        \Session::flash('success', 'Vanity URL ' . $data['source_url'] . ' was edited');

        if ($request->get('_update')) {
          return redirect('admin/modules/vanityurl/edit/' . $id);
        } else {
          return redirect('admin/modules/vanityurl');
        }
      } else {
        return redirect()->back()->withErrors($validator)->withInput();
      }
    }
  }
  
  public function getDelete($id = NULL) {

    if ($id != NULL) {
      
      VanityUrlRepository::deleteURL($id);

      \Session::flash('success', 'Vanity URL was deleted');
      return redirect('admin/modules/vanityurl');
    }
  }

  public function postBulkAction(Request $request) {

    if ($request->get('bulk_action_ids') != NULL) {

      $aIDs = explode(',', $request->get('bulk_action_ids'));

      if ($request->get('action') == 'bulk_delete') {

        foreach ($aIDs as $id) {
          VanityUrlRepository::deleteURL($id);
        }
        \Session::flash('success', 'Vanity URLs was deleted');
      }
    }
    
    return redirect()->back();
  }

}
