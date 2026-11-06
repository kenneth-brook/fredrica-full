<?php

namespace Module\Forms\Seed;

/*
 * Seed: Forms
 * @Atlantis CMS
 * v 1.0
 */

class FormsSeeder extends \Illuminate\Database\Seeder {

  public function run() {

    $setup = require(base_path() . '/modules/atlantis/forms/src/Module/Forms/Setup/Setup.php');

    //check for the module with the same name
    $result = \DB::table("modules")
                    ->where("name", "=", $setup['name'])->first();

    if (is_null($result)) {

      \DB::table("modules")
              ->insert([
                  'name' => $setup['name'],
                  'author' => $setup['author'],
                  'version' => $setup['version'],
                  'namespace' => $setup['moduleNamespace'],
                  'path' => $setup['path'],
                  'provider' => $setup['provider'],
                  'extra' => serialize($setup['extra']),
                  'adminURL' => $setup['adminURL'],
                  'icon' => $setup['icon'],
                  'active' => 1,
                  'description' => $setup['description'],
                  'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                  'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
      ]);
    }

    $data = $this->getData();

    foreach ($data as $row) {
      \DB::table('forms')->insert($row);
    }

    $dataItems = $this->getDataItems();

    foreach ($dataItems as $row) {
      \DB::table('forms_items')->insert($row);
    }
  }

  private function getData() {

    return [
        [
            'id' => 1,
            'name' => 'test',
            'message' => '',
            'email_check' => 0,
            'email_from' => '',
            'emails' => '',
            'form_class' => '',
            'btn_value' => 'submit',
            'btn_class' => 'submit',
            'captcha' => 1,
            'captcha_namespace' => 'Module\Forms\Captcha\ReCaptcha',
            'ga' => 1,
            'before_form_text' => 'This is demo for the form builder',
            'after_form_text' => 'just text',
            'use_custom_form' => 0,
            'custom_form' => '',
            'redirect_url' => '',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]
    ];
  }

  private function getDataItems() {

    return [
        [
            'id' => 1,
            'form_id' => 1,
            'label' => 'Name',
            'field_type' => 'input:text',
            'field_name' => 'name',
            'validation' => 'required',
            'attributes' => 'a:2:{s:5:"class";s:10:"name-class";s:11:"placeholder";s:4:"Name";}',
            'validation_msg' => 'Name must not be empty',
            'field_value' => 's:0:"";',
            'weight' => 1,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ],
        [
            'id' => 2,
            'form_id' => 1,
            'label' => 'Password',
            'field_type' => 'input:password',
            'field_name' => 'password',
            'validation' => 'required',
            'attributes' => 'a:2:{s:5:"class";s:10:"pass-class";s:11:"placeholder";s:8:"Password";}',
            'validation_msg' => 'Password must not be empty',
            'field_value' => 's:0:"";',
            'weight' => 2,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ],
        [
            'id' => 3,
            'form_id' => 1,
            'label' => 'Gender',
            'field_type' => 'select',
            'field_name' => 'gender',
            'validation' => '0',
            'attributes' => 'a:1:{s:5:"class";s:10:"test-class";}',
            'validation_msg' => 'error',
            'field_value' => 'a:2:{s:4:"male";s:4:"Male";s:6:"female";s:15:"Female::checked";}',
            'weight' => 3,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ],
        [
            'id' => 4,
            'form_id' => 1,
            'label' => 'Details',
            'field_type' => 'textarea',
            'field_name' => 'details',
            'validation' => 'required',
            'attributes' => 'a:2:{s:5:"class";s:13:"details-class";s:4:"rows";s:1:"8";}',
            'validation_msg' => 'Details must not be empty',
            'field_value' => 's:0:"";',
            'weight' => 4,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ],
        [
            'id' => 5,
            'form_id' => 1,
            'label' => 'Send me details',
            'field_type' => 'checkbox',
            'field_name' => 'send_details',
            'validation' => '0',
            'attributes' => 'a:1:{s:5:"class";s:14:"checkbox-class";}',
            'validation_msg' => '',
            'field_value' => 'a:2:{s:19:"send_details_mail_1";s:30:"send details to mail1::checked";s:19:"send_details_mail_2";s:21:"send details to mail2";}',
            'weight' => 5,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ],
        [
            'id' => 6,
            'form_id' => 1,
            'label' => 'Select option',
            'field_type' => 'radio',
            'field_name' => 'options',
            'validation' => '0',
            'attributes' => 'a:1:{s:5:"class";s:13:"options-class";}',
            'validation_msg' => '',
            'field_value' => 'a:3:{s:8:"option_1";s:17:"Option 1::checked";s:8:"option_2";s:8:"Option 2";s:8:"option_3";s:8:"Option 3";}',
            'weight' => 6,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ],
        [
            'id' => 7,
            'form_id' => 1,
            'label' => 'Email',
            'field_type' => 'input:text',
            'field_name' => 'email',
            'validation' => 'required|email',
            'attributes' => 'a:2:{s:5:"class";s:10:"pass-class";s:11:"placeholder";s:5:"Email";}',
            'validation_msg' => 'Please add valid email',
            'field_value' => 's:0:"";',
            'weight' => 7,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]
    ];
  }

}
