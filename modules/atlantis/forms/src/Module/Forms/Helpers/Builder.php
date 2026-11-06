<?php

namespace Module\Forms\Helpers;

class Builder {

    /**
     * Field types
     */
    public static $_TYPE_INPUT_TEXT = 'input:text';
    public static $_TYPE_INPUT_PASSWORD = 'input:password';
    public static $_TYPE_TEXTAREA = 'textarea';
    public static $_TYPE_CHECKBOX = 'checkbox';
    public static $_TYPE_RADIO = 'radio';
    public static $_TYPE_SELECT = 'select';
    public static $_TYPE_SELECT_COUNTRIES = 'select:countries';
    public static $_TYPE_SELECT_STATES = 'select:states';
    public static $_TYPE_FILE = 'file';
    public static $_TYPE_DATEPICKER = 'datepicker';

    /**
     * Validation rules
     */
    public static $_VALIDATION_NOT_EMPTY = 'required';
    public static $_VALIDATION_EMAIL = 'required|email';
    public static $_VALIDATION_NUMERIC = 'required|numeric';
    public static $_VALIDATION_ALPHA_NUMERIC = 'required|alpha_num';
    public static $_VALIDATION_IMAGE = 'image';
    public static $_VALIDATION_IMAGE_1M = 'image|max:1000';
    public static $_VALIDATION_IMAGE_2M = 'image|max:2000';
    public static $_VALIDATION_PDF = 'mimes:pdf';
    public static $_VALIDATION_PDF_1M = 'mimes:pdf|max:1000';
    public static $_VALIDATION_PDF_2M = 'mimes:pdf|max:2000';
    public static $_VALIDATION_EXCEL = 'mimes:xlsx,xls';
    public static $_VALIDATION_EXCEL_1M = 'mimes:xlsx,xls|max:1000';
    public static $_VALIDATION_EXCEL_2M = 'mimes:xlsx,xls|max:2000';
    public static $_VALIDATION_CSV = 'mimes:csv,txt';
    public static $_VALIDATION_CSV_1M = 'mimes:csv,txt|max:1000';
    public static $_VALIDATION_CSV_2M = 'mimes:csv,txt|max:2000';
    public static $_VALIDATION_DATEPICKER_FORMAT = 'date_format:m-d-y';
    public static $_VALIDATION_DATEPICKER_FORMAT_REQUIRED = 'date_format:m-d-y|required';
    public static $_VALIDATION_ZIP_US_CA = 'required|zip_us_ca';
    public static $_VALIDATION_PHONE = 'required|phone';

    /**
     * Private variables
     */
    private $form;
    private $formItems;
    private $captcha;
    private $customFormAttributes = array();

    public function __construct($form, $formItems, $captcha = NULL) {

        $this->form = $form;
        $this->formItems = $formItems;
        $this->captcha = $captcha;
    }

    /**
     * Get Filed Types
     *
     * @return array
     */
    public static function getFieldTypes() {

        return [
            self::$_TYPE_INPUT_TEXT => 'Text',
            self::$_TYPE_INPUT_PASSWORD => 'Password',
            self::$_TYPE_TEXTAREA => 'TextArea',
            self::$_TYPE_CHECKBOX => 'Checkbox',
            self::$_TYPE_RADIO => 'Radio button',
            self::$_TYPE_SELECT => 'Dropdown',
            self::$_TYPE_SELECT_COUNTRIES => 'Dropdown Countries',
            self::$_TYPE_SELECT_STATES => 'Dropdown States',
            self::$_TYPE_FILE => 'File',
            self::$_TYPE_DATEPICKER => 'Datepicker'
        ];
    }

    /**
     * Get validation rules
     *
     * @return array
     */
    public static function getValidationRules() {

        return [
            self::$_VALIDATION_NOT_EMPTY => 'Not empty',
            self::$_VALIDATION_EMAIL => 'Email',
            self::$_VALIDATION_NUMERIC => 'Numeric',
            self::$_VALIDATION_ALPHA_NUMERIC => 'Alpha Numeric',
            self::$_VALIDATION_IMAGE => 'Image file',
            self::$_VALIDATION_IMAGE_1M => 'Image file - 1 Megabyte',
            self::$_VALIDATION_IMAGE_2M => 'Image file - 2 Megabytes',
            self::$_VALIDATION_PDF => 'PDF file',
            self::$_VALIDATION_PDF_1M => 'PDF file - 1 Megabyte',
            self::$_VALIDATION_PDF_2M => 'PDF file - 2 Megabytes',
            self::$_VALIDATION_EXCEL => 'MS Excel file',
            self::$_VALIDATION_EXCEL_1M => 'MS Excel file - 1 Megabytes',
            self::$_VALIDATION_EXCEL_2M => 'MS Excel file - 2 Megabytes',
            self::$_VALIDATION_CSV => 'CSV file',
            self::$_VALIDATION_CSV_1M => 'CSV file - 1 Megabytes',
            self::$_VALIDATION_CSV_2M => 'CSV file - 2 Megabytes',
            self::$_VALIDATION_DATEPICKER_FORMAT => 'Datepicker - Valid',
            self::$_VALIDATION_DATEPICKER_FORMAT_REQUIRED => 'Datepicker - Valid - Not Empty',
            self::$_VALIDATION_ZIP_US_CA => 'ZIP Codes - United States, Canada',
            self::$_VALIDATION_PHONE => 'Phone'
        ];
    }

    /**
     *
     * Build unique id per item
     *
     * @return  array
     * @param \Module\Forms\Models\FormsItems $items
     */
    public function createItemId($item) {
        
        if (!isset($item)) return '';
        $attrs = unserialize($item->attributes);

        return isset($attrs['id']) ? $attrs['id'] : 'form-item-id-'.$item->id;
    }

    /**
     *
     * Build form items
     *
     * @return  array
     * @param \Module\Forms\Models\FormsItems $items
     */
    public function buildItems() {

        $data = array();
        //add custom form attributes
        $this->addCustomFormAttributes($this->form->custom_attributes);

        foreach ($this->formItems as $k => $item) {

            $aFieldValues = unserialize($item->field_value);

            if (empty($item->validation)) {
                $required = ' aria-required="false" ';
            } else {
                $required = ' required aria-required';
            }
            $attrId = $this->createItemId($item);


            /** <input type="text"> */
            if ($item->field_type == self::$_TYPE_INPUT_TEXT) {

                if (is_array($aFieldValues) && isset(array_values($aFieldValues)[0])) {
                    $value = array_values($aFieldValues)[0];
                } else {
                    $value = $aFieldValues;
                }
                $data[$k] = view('forms::items/input-text', ['item' => $item, 'id' => $attrId, 'required' => $required, 'field' => self::createInputText($item, $value, $attrId)]);

                /** <input type="password"> */
            } else if ($item->field_type == self::$_TYPE_INPUT_PASSWORD) {

                if (is_array($aFieldValues) && isset(array_values($aFieldValues)[0])) {
                    $value = array_values($aFieldValues)[0];
                } else {
                    $value = $aFieldValues;
                }

                $data[$k] = view('forms::items/input-password', ['item' => $item,  'id' => $attrId, 'required' => $required, 'field' => self::createInputPassword($item, $value, $attrId)]);

                /** <textarea></textarea> */
            } else if ($item->field_type == self::$_TYPE_TEXTAREA) {

                if (is_array($aFieldValues) && isset(array_values($aFieldValues)[0])) {
                    $value = array_values($aFieldValues)[0];
                } else {
                    $value = $aFieldValues;
                }

                $data[$k] = view('forms::items/textarea', ['item' => $item, 'id' => $attrId, 'required' => $required, 'field' => self::createTextarea($item, $value, $attrId)]);

                /** <input type="checkbox"> */
            } else if ($item->field_type == self::$_TYPE_CHECKBOX) {

                $data[$k] = view('forms::items/checkbox', ['item' => $item, 'required' => $required, 'checkboxes' => self::createCheckbox($aFieldValues, $item)]);

                /** <input type="radio"> */
            } else if ($item->field_type == self::$_TYPE_RADIO) {

                $data[$k] = view('forms::items/radio', ['item' => $item, 'required' => $required, 'radios' => self::createRadio($aFieldValues, $item)]);

                /**
                 * <select name="gender">
                 * <option value="male">Male</option>
                 * <option value="female">Female</option>
                 * </select>
                 */
            } else if ($item->field_type == self::$_TYPE_SELECT) {

                $aSelects = array();
                $checked = NULL;
                foreach ($aFieldValues as $key => $value) {

                    if (stristr($value, '::checked')) {
                        $value = str_replace('::checked', '', $value);
                        $checked = '[' . $key . '] => [' . $value . ']';
                    }

                    $aSelects['[' . $key . '] => [' . $value . ']'] = $value;
                }

                $data[$k] = view('forms::items/select', ['item' => $item, 'id' => $attrId, 'required' => $required, 'field' => self::createSelect($item, $aSelects, $checked, $attrId)]);

                /**
                 * <select name="country">
                 * <option value="bg">Bulgaria</option>
                 * <option value="gr">Greece</option>
                 * .
                 * .
                 * </select>
                 */
            } else if ($item->field_type == self::$_TYPE_SELECT_COUNTRIES) {

                $aSelects = array();
                $checked = NULL;

                if (is_array($aFieldValues)) {

                    foreach ($aFieldValues as $key => $value) {

                        if (stristr($value, '::checked')) {
                            $value = str_replace('::checked', '', $value);
                            $checked = '[' . $key . '] => [' . $value . ']';
                        }

                        $aSelects['[' . $key . '] => [' . $value . ']'] = $value;
                    }
                }

                $aCountires = self::getCountries();

                foreach ($aCountires as $c_key => $c_name) {
                    $aSelects['[' . $c_key . '] => [' . htmlentities($c_name) . ']'] = htmlentities($c_name);
                }

                $data[$k] = view('forms::items/select', ['item' => $item, 'id' => $attrId, 'required' => $required, 'field' => self::createSelect($item, $aSelects, $checked, $attrId)]);

                /**
                 * <select name="states">
                 * <option value="fl">Florida</option>
                 * <option value="ga">Georgia</option>
                 * .
                 * .
                 * </select>
                 */
            } else if ($item->field_type == self::$_TYPE_SELECT_STATES) {

                $aSelects = array();
                $checked = NULL;

                if (is_array($aFieldValues)) {

                    foreach ($aFieldValues as $key => $value) {

                        if (stristr($value, '::checked')) {
                            $value = str_replace('::checked', '', $value);
                            $checked = '[' . $key . '] => [' . $value . ']';
                        }

                        $aSelects['[' . $key . '] => [' . $value . ']'] = $value;
                    }
                }

                $aStates = self::getStates();

                foreach ($aStates as $s_key => $s_name) {
                    $aSelects['[' . $s_key . '] => [' . htmlentities($s_name) . ']'] = htmlentities($s_name);
                }

                $data[$k] = view('forms::items/select', ['item' => $item, 'id' => $attrId, 'required' => $required, 'field' => self::createSelect($item, $aSelects, $checked, $attrId)]);

                /**
                 * <input type="file">
                 */
            } else if ($item->field_type == self::$_TYPE_FILE) {

                $data[$k] = view('forms::items/file', ['item' => $item, 'id' => $attrId, 'required' => $required, 'field' => self::createFile($item, $attrId)]);

                $this->addCustomFormAttributes(['enctype' => 'multipart/form-data']);
            } else if ($item->field_type == self::$_TYPE_DATEPICKER) {
                if (is_array($aFieldValues) && isset(array_values($aFieldValues)[0])) {
                    $value = array_values($aFieldValues)[0];
                } else {
                    $value = $aFieldValues;
                }
                $id = "datepicker-" . mt_rand(0, 99999999);
                $data[$k] = view('forms::items/datepicker', ['item' => $item, 'id' => $id, 'required' => $required, 'field' => self::createDatepicker($item, $value, $id)]);
            }
        }

        return $data;
    }

    public function buildCustomTemplate() {

        /** find all input fields */
        preg_match_all('/{{(\w+)}}/im', $this->form->custom_form, $aMatchesFunc);
        /** find all error fields */
        preg_match_all('/{!!(\w+)!!}/im', $this->form->custom_form, $aMatchesFuncError);

        $customBody = $this->form->custom_form;
        //add custom form attributes
        $this->addCustomFormAttributes($this->form->custom_attributes);
        /** check for errors from validation */
        $errors = \Session::get('errors');
        if ($errors != NULL) {
            foreach ($aMatchesFuncError[1] as $err) {

                foreach ($errors->getMessages() as $key => $err_message) {

                    if ($key == $err) {
                        $customBody = preg_replace('/{!!' . $err . '!!}/im', self::createErrorMsg($err_message[0]), $customBody);
                    }
                }
                $customBody = preg_replace('/{!!' . $err . '!!}/im', '', $customBody);
            }
        } else {

            foreach ($aMatchesFuncError[1] as $err) {
                /** remove error tags */
                $customBody = preg_replace('/{!!' . $err . '!!}/im', '', $customBody);
            }
        }

        /** {{submit_button}} - always needed */
        $customBody = preg_replace('/{{' . 'submit_button' . '}}/im', self::createSubmitButton($this->form), $customBody);
        /** {{before_form_text}} - always needed */
        $customBody = preg_replace('/{{' . 'before_form_text' . '}}/im', $this->form->before_form_text, $customBody);
        /** {{after_form_text}} - always needed */
        $customBody = preg_replace('/{{' . 'after_form_text' . '}}/im', $this->form->after_form_text, $customBody);
        /** {{captcha}} - always needed */
        if ($this->captcha != NULL) {
            $customBody = preg_replace('/{{' . 'captcha' . '}}/im', $this->captcha, $customBody);
        } else {
            $customBody = preg_replace('/{{' . 'captcha' . '}}/im', '', $customBody);
        }

        foreach ($aMatchesFunc[1] as $k => $token) {

            foreach ($this->formItems as $item) {

                $aFieldValues = unserialize($item->field_value);

                if (is_array($aFieldValues) && isset(array_values($aFieldValues)[0])) {
                    $value = array_values($aFieldValues)[0];
                } else {
                    $value = $aFieldValues;
                }

                if ($token == $item->field_name) {

                    $buildItem = '';
                    $attrId = $this->createItemId($item);


                    /** <input type="text"> */
                    if ($item->field_type == self::$_TYPE_INPUT_TEXT) {

                        $buildItem = self::createInputText($item, $value, $attrId);

                        /** <input type="password"> */
                    } else if ($item->field_type == self::$_TYPE_INPUT_PASSWORD) {

                        $buildItem = self::createInputPassword($item, $value, $attrId);

                        /** <textarea></textarea> */
                    } else if ($item->field_type == self::$_TYPE_TEXTAREA) {

                        $buildItem = self::createTextarea($item, $value, $attrId);

                        /** <input type="checkbox"> */
                    } else if ($item->field_type == self::$_TYPE_CHECKBOX) {

                        $buildItem = self::createCheckbox($aFieldValues, $item);

                        /** <input type="radio"> */
                    } else if ($item->field_type == self::$_TYPE_RADIO) {

                        $buildItem = self::createRadio($aFieldValues, $item);

                        /**
                         * <select name="gender">
                         * <option value="male">Male</option>
                         * <option value="female">Female</option>
                         * </select>
                         */
                    } else if ($item->field_type == self::$_TYPE_SELECT) {

                        $aSelects = array();
                        $checked = NULL;
                        foreach ($aFieldValues as $k => $value) {

                            if (stristr($value, '::checked')) {
                                $value = str_replace('::checked', '', $value);
                                $checked = '[' . $k . '] => [' . $value . ']';
                            }

                            $aSelects['[' . $k . '] => [' . $value . ']'] = $value;
                        }

                        $buildItem = self::createSelect($item, $aSelects, $checked, $attrId);


                        /**
                         * <select name="country">
                         * <option value="bg">Bulgaria</option>
                         * <option value="gr">Greece</option>
                         * .
                         * .
                         * </select>
                         */
                    } else if ($item->field_type == self::$_TYPE_SELECT_COUNTRIES) {

                        $aSelects = array();
                        $checked = NULL;

                        if (is_array($aFieldValues)) {

                            foreach ($aFieldValues as $key => $value) {

                                if (stristr($value, '::checked')) {
                                    $value = str_replace('::checked', '', $value);
                                    $checked = '[' . $key . '] => [' . $value . ']';
                                }

                                $aSelects['[' . $key . '] => [' . $value . ']'] = $value;
                            }
                        }

                        $aCountires = require(__DIR__ . '/../Data/Countries.php');

                        foreach ($aCountires as $c_key => $c_name) {
                            $aSelects['[' . $c_key . '] => [' . $c_name . ']'] = $c_name;
                        }

                        $buildItem = self::createSelect($item, $aSelects, $checked, $attrId);

                        /**
                         * <select name="states">
                         * <option value="fl">Florida</option>
                         * <option value="ga">Georgia</option>
                         * .
                         * .
                         * </select>
                         */
                    } else if ($item->field_type == self::$_TYPE_SELECT_STATES) {

                        $aSelects = array();
                        $checked = NULL;

                        if (is_array($aFieldValues)) {

                            foreach ($aFieldValues as $key => $value) {

                                if (stristr($value, '::checked')) {
                                    $value = str_replace('::checked', '', $value);
                                    $checked = '[' . $key . '] => [' . $value . ']';
                                }

                                $aSelects['[' . $key . '] => [' . $value . ']'] = $value;
                            }
                        }

                        $aStates = require(__DIR__ . '/../Data/States.php');

                        foreach ($aStates as $s_key => $s_name) {
                            $aSelects['[' . $s_key . '] => [' . $s_name . ']'] = $s_name;
                        }

                        $buildItem = self::createSelect($item, $aSelects, $checked, $attrId);

                        /**
                         * <input type="file">
                         */
                    } else if ($item->field_type == self::$_TYPE_FILE) {

                        $buildItem = self::createFile($item, $attrId);
                        $this->addCustomFormAttributes(['enctype' => 'multipart/form-data']);
                    } else if ($item->field_type == self::$_TYPE_DATEPICKER) {
                        if (is_array($aFieldValues) && isset(array_values($aFieldValues)[0])) {
                            $value = array_values($aFieldValues)[0];
                        } else {
                            $value = $aFieldValues;
                        }
                        $id = "datepicker-" . mt_rand(0, 99999999);
                        $buildItem = self::createDatepicker($item, $value, $id);
                    }

                    $customBody = preg_replace('/{{' . $token . '}}/im', $buildItem, $customBody);
                }
            }
        }

        return $customBody;
    }

    /**
     * Always use after buildItems() or buildCustomTemplate()
     *
     * @return array
     */
    public function getCustomFormAttributes() {

        return $this->customFormAttributes;
    }

    public static function createInputText($item, $value, $id = NULL) {

        $attrs = unserialize($item->attributes);
        if (isset($id) && !is_null($id)){
            $attrs['id'] = $id;
        }
        return \Form::input('text', $item->field_name, old($item->field_name, $value), $attrs);
    }

    public static function createInputPassword($item, $value, $id = NULL) {

        $attrs = unserialize($item->attributes);
        if (isset($id) && !is_null($id)){
            $attrs['id'] = $id;
        }

        return \Form::input('password', $item->field_name, old($item->field_name, $value), $attrs);
    }

    public static function createTextarea($item, $value, $id = NULL) {

        $attrs = unserialize($item->attributes);
        if (isset($id) && !is_null($id)){
            $attrs['id'] = $id;
        }
        return \Form::textarea($item->field_name, old($item->field_name, $value), $attrs);
    }

    public static function createDatepicker($item, $value, $id = NULL) {
        $atts = unserialize($item->attributes);
        if (array_key_exists("id", $atts)) {
            $id = $atts['id'];
        }
        $format = HelperFunctions::dateformat_PHP_to_jQueryUI("m-d-y");

        if (array_key_exists("format", $atts) && $atts['format']) {
            $format = HelperFunctions::dateformat_PHP_to_jQueryUI($atts['format']);
        }
        \Atlantis\Helpers\Assets::registerJS(view('forms::items.includes.datepicker-dependencies', ["id" => $id, "format" => $format])->render());

        return \Form::input('text', $item->field_name, old($item->field_name, $value), array_merge(['id' => $id], unserialize($item->attributes)));
    }

    public static function createSelect($item, $aSelects, $checked, $id = NULL) {

        $attrs = unserialize($item->attributes);
        if (isset($id) && !is_null($id)){
            $attrs['id'] = $id;
        }
        return \Form::selectWithDisabled($item->field_name, $aSelects, $checked, $attrs);
    }

    public static function createFile($item, $id = NULL) {
        $attrs = unserialize($item->attributes);
        if (isset($id) && !is_null($id)){
            $attrs['id'] = $id;
        }
        return \Form::file($item->field_name, $attrs);
    }

    public static function createCheckbox($checkboxes, $item) {

        $fields = '';
        $i = 1;
        foreach ($checkboxes as $k => $checkbox) {
            $id = 'checkbox-id-' . $item->id . '-' . $i; 
            $fields .= '<label for="' . $id . '">';
            if (stristr($checkbox, '::checked')) {
                $fields .= \Form::checkbox($item->field_name . '[' . $k . ']', '[' . str_replace('::checked', '', $checkbox) . ']', TRUE, array_merge(unserialize($item->attributes), ['id' => $id])) . str_replace('::checked', '', $checkbox);
            } else {
                $fields .= \Form::checkbox($item->field_name . '[' . $k . ']', '[' . $checkbox . ']', FALSE, array_merge(unserialize($item->attributes), ['id' => $id])) . $checkbox;
            }
            $fields .= '</label>';
            $i++;
        }

        return $fields;
    }

    public static function createRadio($radios, $item) {

        $fields = '';
        $i = 1;
        foreach ($radios as $k => $radio) {
            $id = 'radio-id-' . $item->id . '-' . $i; 
            $fields .= '<label for="' . $id . '">';
            if (stristr($radio, '::checked')) {
                $fields .= \Form::radio($item->field_name, '[' . $k . '] => [' . str_replace('::checked', '', $radio) . ']', TRUE, array_merge(unserialize($item->attributes), ['id' => $id])) . str_replace('::checked', '', $radio);
            } else {
                $fields .= \Form::radio($item->field_name, '[' . $k . '] => [' . $radio . ']', FALSE, array_merge(unserialize($item->attributes), ['id' => $id])) . $radio;
            }
            $fields .= '</label>';
            $i++;
        }

        return $fields;
    }

    public static function createSubmitButton($form) {

        if ($form->ga == 1) {
            return '<input type="submit" class="' . $form->btn_class . '" value="' . $form->btn_value . '" onClick="' . "ga('send', 'event', 'Forms', '" . $form->name . "', '" . $form->btn_value . "');" . '">';
        } else {
            return '<input type="submit" class="' . $form->btn_class . '" value="' . $form->btn_value . '">';
        }
    }
    
    public static function getCountries() {
        return require(__DIR__ . '/../Data/Countries.php');
    }
    
    public static function getStates() {
        return require(__DIR__ . '/../Data/States.php');
    }

    public static function createErrorMsg($error) {
        return view('forms::items/error-msg', ['error' => $error]);
    }

    private function addCustomFormAttributes($atts) {

        /*  Add (Any) Custom Form Attributes */
        if ($atts && is_string($atts)) {
            $customFormAtts = explode("\n", $atts);
            foreach ($customFormAtts as $customFormAtt) {
                $pair = explode("=>", $customFormAtt);
                $pair[0] = array_key_exists(0, $pair) ? trim($pair[0]) : "";
                $pair[1] = array_key_exists(1, $pair) ? trim($pair[1]) : "";
                $this->customFormAttributes = array_merge($this->customFormAttributes, [$pair[0] => $pair[1]]);
            }
        } else if ($atts && is_array($atts)) {
            foreach ($atts as $attKey => $attValue) {
                $this->customFormAttributes = array_merge($this->customFormAttributes, [$attKey => $attValue]);
            }
        }
        /*  End custom form attributes */
    }

}
