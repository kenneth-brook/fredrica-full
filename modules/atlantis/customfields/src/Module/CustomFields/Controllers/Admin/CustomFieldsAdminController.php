<?php

namespace Module\CustomFields\Controllers\Admin;

use Illuminate\Http\Request;
use Atlantis\Controllers\Admin\AdminModulesController;
use Module\CustomFields\Controllers\CustomFieldsController;
use Module\CustomFields\Models\CustomFields;
use Module\CustomFields\Models\Repositories\CustomFieldsRepository;
use Atlantis\Helpers\Tools;

class CustomFieldsAdminController extends AdminModulesController
{

    public function __construct()
    {
        parent::__construct(\Config::get('customfields.setup'));
    }

    public function getIndex($id = null)
    {
        $data = [];

        if (\Session::get('info') != NULL) {
            $data['msgInfo'] = \Session::get('info');
        }

        if (\Session::get('success') != NULL) {
            $data['msgSuccess'] = \Session::get('success');
        }

        if (\Session::get('error') != NULL) {
            $data['msgError'] = \Session::get('error');
        }


        return view("customfields-admin::admin.main", $data);
    }

    public function getAddField(Request $request)
    {

        $data = [];

        if (\Session::get('info') != NULL) {
            $data['msgInfo'] = \Session::get('info');
        }

        if (\Session::get('success') != NULL) {
            $data['msgSuccess'] = \Session::get('success');
        }

        if (\Session::get('error') != NULL) {
            $data['msgError'] = \Session::get('error');
        }

        $cats = CustomFieldsRepository::getAllCategories();

        if (count($cats)) {
            foreach ($cats as $c) {
                $data['categories'][$c->id] = $c->category_name;
            }
        } else {
            $data['categories'] = null;
        }

        return view("customfields-admin::admin.add-field", $data);
    }

    public function postAddField(Request $request)
    {
        $data = $request->all();

        $model = new CustomFieldsRepository();

        $validate = $model->validateOnCreate($data);

        if (!$validate->fails()) {

            $data['field_slur'] = str_slug($data['field_label']);

            $model::add($data);

            if ($request->get('_save_close')) {
                \Session::flash('success', 'Field Label ' . $data['field_label'] . ' was created');

                return redirect('admin/modules/customfields');

            } elseif ($request->get('_update')) {
                \Session::flash('success', 'Field Label ' . $data['field_label'] . ' was created');

                return redirect()->back();
            }
        } else {
            return redirect()->back()->withErrors($validate)->withInput();
        }

    }

    public function getEditField($id, Request $request)
    {
        $data = [];

        $data['model'] = CustomFieldsRepository::get($id);

        $cats = CustomFieldsRepository::getAllCategories();

        if (count($cats)) {
            foreach ($cats as $c) {
                $data['categories'][$c->id] = $c->category_name;
            }
        } else {
            $data['categories'] = null;
        }

        if (\Session::get('info') != NULL) {
            $data['msgInfo'] = \Session::get('info');
        }

        if (\Session::get('success') != NULL) {
            $data['msgSuccess'] = \Session::get('success');
        }

        if (\Session::get('error') != NULL) {
            $data['msgError'] = \Session::get('error');
        }

        return view("customfields-admin::admin.edit-field", $data);
    }

    public function postEditField($id, Request $request)
    {
        $data = $request->all();

        $model = new CustomFieldsRepository();

        $validate = $model->validateOnUpdate($id, $data);

        if (!$validate->fails()) {

            $data['field_slur'] = str_slug($data['field_label']);

            $model::update($id, $data);

            if ($request->get('_save_close')) {
                \Session::flash('success', 'Field Label ' . $data['field_label'] . ' was updated');

                return redirect('admin/modules/customfields');

            } elseif ($request->get('_update')) {
                \Session::flash('success', 'Field Label ' . $data['field_label'] . ' was updated');

                return redirect()->back();
            }
        } else {
            return redirect()->back()->withErrors($validate)->withInput();
        }
    }

    public function getDeleteField($id, Request $request)
    {
        $model = CustomFieldsRepository::get($id);

        CustomFieldsRepository::delete($id);

        \Session::flash('success', 'Field Label ' . $model->field_label . ' was deleted');

        return redirect('admin/modules/customfields');
    }

    public function postBulkDelete(Request $request)
    {

        if ($request->get('bulk_action_ids') != NULL) {

            $aIDs = explode(',', $request->get('bulk_action_ids'));

            if ($request->get('action') == 'bulk_delete') {

                foreach ($aIDs as $id) {
                    CustomFieldsRepository::delete($id);
                }

                \Session::flash('success', 'Multiple Fields were deleted');
            }
        }

        return redirect()->back();
    }

    public function getCategories()
    {
        $data = [];

        if (\Session::get('info') != NULL) {
            $data['msgInfo'] = \Session::get('info');
        }

        if (\Session::get('success') != NULL) {
            $data['msgSuccess'] = \Session::get('success');
        }

        if (\Session::get('error') != NULL) {
            $data['msgError'] = \Session::get('error');
        }


        return view("customfields-admin::admin.main-category", $data);
    }

    public function getAddCategory(Request $request)
    {

        $data = [];

        if (\Session::get('info') != NULL) {
            $data['msgInfo'] = \Session::get('info');
        }

        if (\Session::get('success') != NULL) {
            $data['msgSuccess'] = \Session::get('success');
        }

        if (\Session::get('error') != NULL) {
            $data['msgError'] = \Session::get('error');
        }

        $data['templates'] = Tools::getTemplates();;

        return view("customfields-admin::admin.add-category", $data);
    }

    public function postAddCategory(Request $request)
    {
        $data = $request->all();

        $model = new CustomFieldsRepository();

        $validate = $model->validateOnCreateCategory($data);

        if (!$validate->fails()) {
            $model::addCategory($data);

            if ($request->get('_save_close')) {
                \Session::flash('success', 'Category ' . $data['category_name'] . ' was saved');

                return redirect('admin/modules/customfields/categories');

            } elseif ($request->get('_update')) {
                \Session::flash('success', 'Category ' . $data['category_name'] . ' was saved');

                return redirect()->back();
            }

        } else {
            return redirect()->back()->withInput()->withErrors($validate);
        }
    }

    public function getEditCategory($id, Request $request)
    {

        $data = [];

        $data['model'] = CustomFieldsRepository::getCategory($id);

        if (\Session::get('info') != NULL) {
            $data['msgInfo'] = \Session::get('info');
        }

        if (\Session::get('success') != NULL) {
            $data['msgSuccess'] = \Session::get('success');
        }

        if (\Session::get('error') != NULL) {
            $data['msgError'] = \Session::get('error');
        }

        $data['templates'] = Tools::getTemplates();;

        return view("customfields-admin::admin.edit-category", $data);
    }

    public function postEditCategory($id, Request $request)
    {
        $data = $request->all();

        $model = new CustomFieldsRepository();

        $validate = $model->validateOnUpdateCategory($id, $data);

        if (!$validate->fails()) {
            $model::updateCategory($id, $data);

            if ($request->get('_save_close')) {
                \Session::flash('success', 'Category ' . $data['category_name'] . ' was updated');

                return redirect('admin/modules/customfields/categories');

            } elseif ($request->get('_update')) {
                \Session::flash('success', 'Category ' . $data['category_name'] . ' was updated');

                return redirect()->back();
            }

        } else {
            return redirect()->back()->withInput()->withErrors($validate);
        }
    }

    public function getDeleteCategory($id, Request $request)
    {
        $model = CustomFieldsRepository::getCategory($id);

        CustomFieldsRepository::deleteCategory($id);

        \Session::flash('success', 'Category ' . $model->category_name . ' was deleted.');

        return redirect('admin/modules/customfields/categories');
    }


    public function postBulkDeleteCategories(Request $request)
    {

        if ($request->get('bulk_action_ids') != NULL) {

            $aIDs = explode(',', $request->get('bulk_action_ids'));

            if ($request->get('action') == 'bulk_delete') {

                foreach ($aIDs as $id) {
                    CustomFieldsRepository::deleteCategory($id);
                }

                \Session::flash('success', 'Multiple Categories were deleted');
            }
        }

        return redirect()->back();
    }

}