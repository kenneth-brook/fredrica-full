<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminController;
use Atlantis\Helpers\Tools;
use Atlantis\Models\Repositories\PagesCategoriesRepository;
use \Illuminate\Http\Request;

class CategoriesController extends AdminController {

  public function ACTIONS(){

    return [
      '' =>  trans('admin::views.Do Nothing'),
      'prepend' =>  trans('admin::views.Prepend With'),
      'append' =>  trans('admin::views.Append With')
    ];
  } 

  public function __construct() {

    parent::__construct(self::$_ID_PAGES);
  }

  /**
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

    return view('atlantis-admin::categories', $aData);
    }
   * 
   */
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

    $aData['actions'] = $this->ACTIONS();

    $aData['aTemplates'] = Tools::getTemplates();

    return view('atlantis-admin::categories-add', $aData);
  }

  public function postAdd(Request $request) {

    $oPagesCat = new PagesCategoriesRepository();

    $data = $request->all();

    $validator = $oPagesCat->validationCreate($data);

    if (!$validator->fails()) {

      $id = $oPagesCat->createCategory($data);

      \Session::flash('success', trans('admin::messages.Category was created', ['name'=> $data['category_name']]));

      if ($request->get('_update')) {
        return redirect('admin/categories/edit/' . $id);
      } else {
        \Session::flash('tab_panel', 'categories');
        return redirect('admin/pages');
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

    $model = PagesCategoriesRepository::getCategory($id);

    if ($model != NULL) {

      $aData['oCat'] = $model;

      $aData['actions'] = $this->ACTIONS();

      $aData['aTemplates'] = Tools::getTemplates();
    } else {
      $aData['invalid_item'] = trans('admin::messages.This page category is not valid');
    }

    return view('atlantis-admin::categories-edit', $aData);
  }

  public function postEdit($id = NULL, Request $request) {

    if ($id != NULL) {

      $oPagesCat = new PagesCategoriesRepository();

      $data = $request->all();

      $validator = $oPagesCat->validationCreate($data, $id);

      if (!$validator->fails()) {

        $oPagesCat->updateCategory($id, $data);

        \Session::flash('success', trans('admin::messages.Category was edited', ['name'=> $data['category_name']]));

        if ($request->get('_update')) {
          return redirect('admin/categories/edit/' . $id);
        } else {
          \Session::flash('tab_panel', 'categories');
          return redirect('admin/pages');
        }
      } else {

        return redirect()->back()->withErrors($validator)->withInput();
      }
    }
  }

  public function getDelete($id = NULL) {

    if ($id != NULL) {
      PagesCategoriesRepository::deleteCategory($id);

      \Session::flash('success', trans('admin::messages.Category was deleted', ['name'=> $id]));
      \Session::flash('tab_panel', 'categories');
      return redirect('admin/pages');
    }
  }

  public function postBulkAction(Request $request) {

    if ($request->get('bulk_action_ids') != NULL) {

      $aIDs = explode(',', $request->get('bulk_action_ids'));

      $model = new PagesCategoriesRepository();

      if ($request->get('action') == 'bulk_delete') {

        foreach ($aIDs as $id) {
          $model->deleteCategory($id);
        }
        \Session::flash('success',  trans('admin::messages.Categories were deleted'));
      }
    }
    \Session::flash('tab_panel', 'categories');
    return redirect()->back();
  }

}
