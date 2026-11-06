<?php

namespace Module\Forms\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminModulesController;
use Module\Forms\Models\Repositories\FormsRepository;
use Illuminate\Http\Request;
use Module\Forms\Helpers\Builder as FormBuilder;
use Module\Forms\Helpers\Captcha as CaptchaHelper;
use Module\Forms\Models\Repositories\FormsItemsRepository;
use Module\Forms\Models\Repositories\FormsConfigRepository;
use Illuminate\Support\Facades\Redirect;

class FormsAdminController extends AdminModulesController {

    public function __construct() {
        parent::__construct(\Config::get('forms.setup'));

    }

    /**
     * Show list
     * 
     * admin/modules/forms
     * 
     * Responds to requests to GET
     */
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

        $oModels = FormsRepository::getAll();

        $aData['config'] = FormsConfigRepository::getConfig();

        $aData['oModels'] = $oModels;

        return view('forms-admin::admin/list', $aData);

    }

    /*
     * 
     * clones a form
     * 
     * @param form_id INT
     * @param clone-name STRING
     * 
     */

    public function postCloneForm() {
       
        $request = request();
        $formId = $request->input("form_id");
        $formName = $request->input("clone-name");
        if (!$formName || !$formId) {
            \Session::flash('error', 'You did not specify a name for the form clone or its ID');
            return Redirect::back();
        }

        $form = \Module\Forms\Models\Forms::find($formId);
        $clone = $form->replicate();
        $originalFormName = $clone->name;
        $clone->name = $formName;
        $clone->push();
        $formItems = \Module\Forms\Models\FormsItems::where("form_id", "=", $formId)->get();
        $clonedFormItems = array();
        foreach ($formItems as $formItem) {
            $clonedFormItem = $formItem->replicate();
            $clonedFormItem->form_id = $clone->id;
            \Module\Forms\Models\FormsItems::create($clonedFormItem->toArray());
            //   \Module\Forms\Models\FormsItems::insert($clonedFormItem);
            //array_push($clonedFormItems, $clonedFormItem);
        }

        \Session::flash('success', 'The form ' . $originalFormName . ' has been successfully cloned as ' . $formName . "!");
        return Redirect::back();

    }

    /**
     * Show add view
     * 
     * admin/modules/forms/add
     * 
     * Responds to requests to GET
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

        $aCaptchas = CaptchaHelper::getAll($this->getModuleConfig());

        $aData['fieldTypes'] = FormBuilder::getFieldTypes();
        $aData['validationRules'] = array_prepend(FormBuilder::getValidationRules(), '--', NULL);
        $aData['itemsFields'] = $this->getItemsFields();
        $aData['aCaptcha'] = $this->getCaptchasForSelect($aCaptchas);

        $aData['aCountries'] = $this->getCountriesForHelp();
        $aData['aStates'] = $this->getStatesForHelp();

        return view('forms-admin::admin/add', $aData);

    }

    /**
     * Save new form
     * 
     * admin/modules/forms/add
     * 
     * Responds to requests to POST 
     */
    public function postAdd(Request $request) {
        
        $modelDB = new FormsRepository();

        $validator = $modelDB->validationCreate($request->all());

        if (!$validator->fails()) {

            $aCaptchas = CaptchaHelper::getAll($this->getModuleConfig());

            $data = $request->all();
            $data['captcha_namespace'] = $aCaptchas[$request->get('select_captcha')]['namespace'];
            //$data['items'] = FormBuilder::getPostItems();

            $id = $modelDB->add($data);

            \Session::flash('success', 'Form ' . $data['name'] . ' was created');

            if ($request->get('_update')) {
                return redirect('admin/modules/forms/edit/' . $id);
            } else {
                return redirect('admin/modules/forms');
            }
        } else {
            //dd('validations');
            return redirect('admin/modules/forms/add')->withErrors($validator)->withInput();
        }

    }

    /*
     * Show edit view
     * 
     * admin/modules/forms/edit/{id}
     * 
     * Responds to requests to GET
     */

    public function getEdit($id = NULL) {

        $oModel = FormsRepository::get($id);

        $aCaptchas = CaptchaHelper::getAll($this->getModuleConfig());

        $captcha_select = NULL;

        foreach ($aCaptchas as $k => $captcha) {
            if ($oModel->captcha_namespace == $captcha['namespace']) {
                $captcha_select = $k;
            }
        }

        $aParams = array();

        $aParams['oModel'] = $oModel;
        $aParams['aCaptcha'] = $this->getCaptchasForSelect($aCaptchas);
        $aParams['captcha_select'] = $captcha_select;
        $aParams['fieldTypes'] = FormBuilder::getFieldTypes();
        $aParams['validationRules'] = array_prepend(FormBuilder::getValidationRules(), '--', NULL);
        $aParams['itemsFields'] = $this->getItemsFields(FormsItemsRepository::getItems($id));
        //dd($aParams);
        $aParams['aCountries'] = $this->getCountriesForHelp();
        $aParams['aStates'] = $this->getStatesForHelp();

        return view('forms-admin::admin/edit', $aParams);

    }

    /**
     * Edit
     * 
     * admin/modules/forms/edit/{id}
     * 
     * Responds to requests to POST
     */
    public function postEdit($id = NULL, Request $request) {

        $oModel = new FormsRepository();

        $validator = $oModel->validationEdit($request->all());

        if (!$validator->fails()) {

            $aData = $request->all();

            $aCaptchas = CaptchaHelper::getAll($this->getModuleConfig());
            $aData['captcha_namespace'] = $aCaptchas[$request->get('select_captcha')]['namespace'];

            if (!isset($aData['captcha'])) {
                $aData['captcha'] = 0;
            }

            if (!isset($aData['ga'])) {
                $aData['ga'] = 0;
            }

            if (!isset($aData['use_custom_form'])) {
                $aData['use_custom_form'] = 0;
            }

            if (!isset($aData['submit_with_ajax'])) {
                $aData['submit_with_ajax'] = 0;
            }

            if (!isset($aData['hide_form_upon_success'])) {
                $aData['hide_form_upon_success'] = 0;
            }

            if (!isset($aData['use_flood_control'])) {
                $aData['use_flood_control'] = 0;
            }

            if (!isset($aData['email_check'])) {
                $aData['email_check'] = 0;
            }

            if (!isset($aData['use_front_end_validation'])) {
                $aData['use_front_end_validation'] = 0;
            }

            //$aData['items'] = FormBuilder::getPostItems();
            //dd($aData);
            $oModel->edit($id, $aData);

            \Session::flash('success', 'Form ' . $request->get('name') . ' was edited');

            if ($request->get('_update')) {
                return redirect('admin/modules/forms/edit/' . $id);
            } else {
                return redirect('admin/modules/forms');
            }
        } else {

            return redirect('admin/modules/forms/edit/' . $id)->withErrors($validator)->withInput();
        }

    }

    /**
     * Delete
     * 
     * admin/modules/forms/delete/{id}
     * 
     * Responds to requests to GET
     */
    public function getDelete($id = NULL) {

        if (FormsRepository::deleteEntry($id)) {
            return redirect('admin/modules/forms')->with('success', 'Form was deleted');
        } else {
            return redirect('admin/modules/forms')->with('error', 'Invalid ID');
        }

    }

    public function getExport($id = NULL) {
        //exec('chmod -R 775 ' . base_path('resources/'));
        $form = FormsRepository::get($id);

        if (empty($form)) {
            return redirect('admin/modules/forms')->with('error', 'Invalid form');
        }

        $data['form'] = $form;

        return view('forms-admin::admin/export', $data);

    }

    public function postExport(Request $request, $id = NULL) {
        //return response()->json(['error' => 'errooorrrr']);
        $validator = FormsRepository::validationExport($request->all());

        if ($validator->fails()) {
            return response()->json(['validation_error' => $validator->errors()->toArray()]);
        }

        $offset = $request->get('offset', 0);
        $exportName = $request->get('export_name', NULL);
        $exported = $request->get('exported', 0);
        $total = $request->get('total');

        $from = empty($request->get('from_date')) ? '01/01/2000' : $request->get('from_date');
        $to = empty($request->get('to_date')) ? '01/01/2080' : $request->get('to_date');

        $exporter = new \Module\Forms\Helpers\Export($id);

        $res = $exporter->toCSVWithAjax($from, $to, $exportName, $offset, $exported, $total);

        return response()->json($res);

    }

    public function getDownloadExport($filename) {
        $file = base_path(\Module\Forms\Helpers\Export::EXPORT_DIR . $filename);
        if (file_exists($file)) {
            return response()->download($file);
        } else {
            return "File not found.";
        }

    }

    public function postUpdateConfig(Request $request) {
        $model = new FormsConfigRepository();
        $model->updateConfig($request->all());

        return redirect()->back()->with('success', 'Config was updated');

    }

    private function getCaptchasForSelect($aCaptchas) {

        $aSelect = array();

        foreach ($aCaptchas as $k => $captcha) {
            $aSelect[$k] = $captcha['name'];
        }

        return $aSelect;

    }

    public function postBulkAction(Request $request) {

        if ($request->get('bulk_action_ids') != NULL) {

            $aIDs = explode(',', $request->get('bulk_action_ids'));

            if ($request->get('action') == 'bulk_delete') {

                foreach ($aIDs as $id) {
                    FormsRepository::deleteEntry($id);
                }
                \Session::flash('success', 'Forms was deleted');
            }
        }

        return redirect()->back();

    }

    private function getItemsFields($oItems = NULL) {

        $items = self::getItemsNames();

        if (!empty(old())) {
            $aItems = array();
            foreach ($items as $item) {
                if (is_array(old($item))) {
                    foreach (old($item) as $k => $val) {
                        $aItems[$k][$item]['name'] = $item . '[' . $k . ']';
                        $aItems[$k][$item]['value'] = $val;

                        //if checkbox is not checked
                        if (!isset($aItems[$k]['use_reply_to'])) {
                            $aItems[$k]['use_reply_to']['name'] = 'use_reply_to' . '[' . $k . ']';
                            $aItems[$k]['use_reply_to']['value'] = 0;
                        }
                        
                        if (!isset($aItems[$k]['use_email_from'])) {
                            $aItems[$k]['use_email_from']['name'] = 'use_email_from' . '[' . $k . ']';
                            $aItems[$k]['use_email_from']['value'] = 0;
                        }
                    }
                }
            }
        } else {
            $aItems = array();
            if ($oItems != NULL && $oItems->count() != 0) {
                foreach ($oItems as $item) {
                    foreach ($items as $it) {
                        $aItems[$item->id][$it]['name'] = $it . '[' . $item->id . ']';
                        if ($it == 'attributes') {
                            $aAttrs = unserialize($item->$it);
                            $attrs = '';
                            if (is_array($aAttrs)) {
                                foreach ($aAttrs as $attr_k => $attr_v) {
                                    $attrs .= $attr_k . '=>' . $attr_v . "\n";
                                }
                            }
                            $aItems[$item->id][$it]['value'] = trim($attrs);
                        } else if ($it == 'field_value') {
                            $field_value = unserialize($item->$it);
                            $field_val = '';
                            if (is_array($field_value)) {
                                foreach ($field_value as $fv_k => $fv_v) {
                                    $field_val .= $fv_k . '=>' . $fv_v . "\n";
                                }
                            } else {
                                $field_val = $field_value;
                            }
                            $aItems[$item->id][$it]['value'] = trim($field_val);
                        } else {
                            $aItems[$item->id][$it]['value'] = $item->$it;
                        }
                    }
                }
            } else {
                $aItems = $this->getDefaultValues($items);
            }
        }
        return $aItems;

    }

    private function getDefaultValues($items) {

        $aI = array();
        foreach ($items as $k => $item) {
            $aI[$item]['name'] = $item . '[]';
            if ($item == 'weight') {
                $aI[$item]['value'] = 1;
            } else {
                $aI[$item]['value'] = NULL;
            }
        }
        $aItems[] = $aI;

        return $aItems;

    }

    /**
     * 
     * @return array
     */
    private function getCountriesForHelp() {

        $fitData = array();

        $countries = FormBuilder::getCountries();

        $i = 0;
        $c = 1;

        foreach ($countries as $code => $name) {

            if ($c == 5) {
                $fitData[$i][$code] = htmlentities($name);
                $c = 1;
                $i++;
            } else {
                $fitData[$i][$code] = htmlentities($name);
                $c++;
            }
        }

        return $fitData;

    }

    /**
     * 
     * @return array
     */
    private function getStatesForHelp() {

        $fitData = array();

        $states = FormBuilder::getStates();

        $i = 0;
        $c = 1;

        foreach ($states as $code => $name) {

            if ($c == 5) {
                $fitData[$i][$code] = $name;
                $c = 1;
                $i++;
            } else {
                $fitData[$i][$code] = $name;
                $c++;
            }
        }

        return $fitData;

    }

    public static function getItemsNames() {
        return ['field_name', 'field_type', 'label', 'validation', 'attributes', 'field_value', 'weight', 'validation_msg', 'use_reply_to', 'use_email_from'];

    }

}
