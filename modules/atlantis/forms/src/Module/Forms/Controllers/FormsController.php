<?php

namespace Module\Forms\Controllers;

use Module\Forms\Models\Repositories\FormsRepository;
use Module\Forms\Models\Repositories\FormsItemsRepository;
use Module\Forms\Models\Repositories\FormsResultsRepository;
use Module\Forms\Helpers\Builder as FormsBuilder;
use Module\Forms\Helpers\Validator as FormsValidator;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Controller: Forms
 * @Atlantis CMS
 * v 1.0
 */
class FormsController extends Controller {

  use \Module\Forms\Traits\FormsTrait;

  /**
   * <div data-pattern-func="module:forms@build-1"></div>
   */
  public function build($aParams = NULL) {

    if (isset($aParams[0])) {

      $form_id = $aParams[0];

      $form = FormsRepository::get($form_id);

      if ($form != NULL) {

        $formItems = FormsItemsRepository::getItems($form_id);

        if ($form->use_custom_form == 0) {
          return $this->normalBuild($form, $formItems, $aParams);
        } else {
          return $this->customTemplateBuild($form, $formItems, $aParams);
        }
      } else {
        abort(404, "form with ID: " . $form_id . " not found");
      }
    } else {
      abort(404, "form ID is missing");
    }
  }

  private function normalBuild($form, $formItems, $patt_func_params) {

    $aData['patt_func_params'] = $patt_func_params;
    $captcha = NULL;
    $captchaView = NULL;

    if ($form->captcha == 1) {

      $captcha = new \Module\Forms\Helpers\Captcha($form->captcha_namespace, $form);
      $captchaView = $captcha->get();
    }

    $formsBuilder = new FormsBuilder($form, $formItems, $captchaView);

    $aData['form'] = $form;
    $aData['escaped_name'] = strtolower(str_replace(" ", "-", $form->name));
    //$aData['url'] = request()->path() . str_replace(request()->url(), '',request()->fullUrl());
    //$aData['url'] = url('/').request()->path().str_replace(request()->url(), '',request()->fullUrl());
    $qString = request()->getQueryString() ? ('?'.request()->getQueryString()) : '';
    $aData['url'] = url('/').'/'.request()->path('/').$qString;

    $aData['items'] = $formsBuilder->buildItems();
    $aData['custom_form_attributes'] = $formsBuilder->getCustomFormAttributes();
    $aData['submit_button'] = FormsBuilder::createSubmitButton($form);
    $aData['captcha'] = $captchaView;

    if (request()->method() == \App\Http\Requests\Request::METHOD_POST && request()->get('form_id') == $form->id) {

      $validator = new FormsValidator($formItems);
      $validator->make(request()->all());

      $captchaFails = FALSE;
      if ($captcha != NULL) {
        $captchaFails = $captcha->fails();
      }

     /*$form->redirect_url = str_replace('{{url}}', str_replace(url('/'), '', request()->fullUrl()), $form->redirect_url);
      $form->redirect_url_error = str_replace('{{url}}', str_replace(url('/'), '', request()->fullUrl()), $form->redirect_url_error);*/

      $form->redirect_url = str_replace('{{url}}', url('/').'/'.request()->path('/'), $form->redirect_url);
      $form->redirect_url_error = str_replace('{{url}}', url('/').'/'.request()->path('/'), $form->redirect_url_error);
     if ($form->redirect_url == '') {
        $form->redirect_url = $aData['url'];
      }
      if ($form->redirect_url_error == '') {
        $form->redirect_url_error = $aData['url'];
      }

      if (!$validator->fails() && !$captchaFails) {

        $floodValid = TRUE;

        if ($form->use_flood_control) {
          $flood_control_time = \Module\Forms\Models\Repositories\FormsConfigRepository::getConfigKey('flood_control_time');
          $flood_control_time = empty($flood_control_time) ? FormsRepository::DEFAULT_FLOOD_CONTROL_TIME : $flood_control_time;

          $floodValid = \Module\Forms\Models\Repositories\FormsIpRepository::validFloodControl($form->id, request()->ip(), $flood_control_time);
        }

        if ($floodValid) {

          //save post data in DB 
          $savedResults = FormsResultsRepository::saveResults(request());
          request()->session()->flash('success', $form->message);

          /**
           * fire submit event with POST data
           */
          \Event::fire('form.submitted', [request()->all()]);

          $this->sendMail($form, $patt_func_params, $formItems, $savedResults);

          if (empty($form->redirect_url)) {
            app('AtlantisRedirect')->set(redirect()->back());
          } else {
            app('AtlantisRedirect')->set(redirect($form->redirect_url));
          }
        } else {
          $messageBag = new MessageBag();
          $messageBag->add('flood_control', 'Flood Control is enabled. Please wait ' . $flood_control_time . ' seconds.');

          $this->errorRedirect($messageBag, $form);
        }
      } else {

        $messageBag = $validator->getErrors(new MessageBag());
        if ($captcha != NULL) {
          $messageBag = $captcha->getErrors($messageBag);
        }

        $this->errorRedirect($messageBag, $form);
      }
    }
    return view('forms::form-builder', $aData);
  }

  /*
   * {{submit_button}} - add submit button / always needed
   */

  private function customTemplateBuild($form, $formItems, $patt_func_params) {

    $aData['patt_func_params'] = $patt_func_params;
    $captcha = NULL;
    $captchaView = NULL;

    if ($form->captcha == 1) {

      $captcha = new \Module\Forms\Helpers\Captcha($form->captcha_namespace, $form);
      $captchaView = $captcha->get();
    }

    $formsBuilder = new FormsBuilder($form, $formItems, $captchaView);

    $aData['content'] = $formsBuilder->buildCustomTemplate();
    $aData['custom_form_attributes'] = $formsBuilder->getCustomFormAttributes();
    $aData['form'] = $form;
    $aData['escaped_name'] = strtolower(str_replace(" ", "-", $form->name));
    
    /*$aData['url'] = request()->fullUrl();
    $aData['url'] = request()->path() . str_replace(request()->url(), '',request()->fullUrl());*/
    
    $qString = request()->getQueryString() ? ('?'.request()->getQueryString()) : '';
    $aData['url'] = url('/').'/'.request()->path('/').$qString; 

    if (request()->method() == \App\Http\Requests\Request::METHOD_POST && request()->get('form_id') == $form->id) {

      
      $validator = new FormsValidator($formItems);
      $validator->make(request()->all());

      $captchaFails = FALSE;
      if ($captcha != NULL) {
        $captchaFails = $captcha->fails();
        //dd($captcha->fails(), 1);
      }

      /*$form->redirect_url = str_replace('{{url}}', str_replace(url('/'), '', request()->fullUrl()), $form->redirect_url);
      $form->redirect_url_error = str_replace('{{url}}', str_replace(url('/'), '', request()->fullUrl()), $form->redirect_url_error);*/

      $form->redirect_url = str_replace('{{url}}', url('/').'/'.request()->path('/'), $form->redirect_url);
      $form->redirect_url_error = str_replace('{{url}}', url('/').'/'.request()->path('/'), $form->redirect_url_error);
     if ($form->redirect_url == '') {
        $form->redirect_url = $aData['url'];
      }
      if ($form->redirect_url_error == '') {
        $form->redirect_url_error = $aData['url'];
      }

      if (!$validator->fails() && !$captchaFails) {

        $floodValid = TRUE;

        if ($form->use_flood_control) {
          $flood_control_time = \Module\Forms\Models\Repositories\FormsConfigRepository::getConfigKey('flood_control_time');
          $flood_control_time = empty($flood_control_time) ? FormsRepository::DEFAULT_FLOOD_CONTROL_TIME : $flood_control_time;

          $floodValid = \Module\Forms\Models\Repositories\FormsIpRepository::validFloodControl($form->id, request()->ip(), $flood_control_time);
        }

        if ($floodValid) {

          //save post data in DB 
          $savedResults = FormsResultsRepository::saveResults(request());
          request()->session()->flash('success', $form->message);

          /**
           * fire submit event with POST data
           */
          \Event::fire('form.submitted', [request()->all()]);

          $this->sendMail($form, $patt_func_params, $formItems, $savedResults);

          if (empty($form->redirect_url)) {
            app('AtlantisRedirect')->set(redirect()->back());
          } else {
            app('AtlantisRedirect')->set(redirect($form->redirect_url));
          }
        } else {

          $messageBag = new MessageBag();
          $messageBag->add('flood_control', 'Flood Control is enabled. Please wait ' . $flood_control_time . ' seconds.');

          $this->errorRedirect($messageBag, $form);
        }
      } else {

        $messageBag = $validator->getErrors(new MessageBag());
        if ($captcha != NULL) {
          $messageBag = $captcha->getErrors($messageBag);
        }

        $this->errorRedirect($messageBag, $form);
      }
    }

    return view('forms::form-builder-custom', $aData);
  }

  /* AJAX FRONT END VALIDATION */

  public function frontEndValidation(Request $request) {

    if ($request->ajax()) {
      $form_id = $request->input('form_id');

      $form = FormsRepository::get($form_id);
      $formItems = FormsItemsRepository::getItems($form_id);


      $captcha = NULL;
      $captchaView = NULL;

      if ($form->captcha == 1) {

        $captcha = new \Module\Forms\Helpers\Captcha($form->captcha_namespace, $form);
        $captchaView = $captcha->get();
      }

      // $formsBuilder = new FormsBuilder($form, $formItems, $captchaView);
      //$aData['content'] = $formsBuilder->buildCustomTemplate();
      //$aData['custom_form_attributes'] = $formsBuilder->getCustomFormAttributes();
      //$aData['form'] = $form;
      //$aData['escaped_name'] = strtolower(str_replace(" ", "-", $form->name));
      //$aData['url'] = str_replace(url('/'), '', request()->fullUrl());

      if (request()->method() == \App\Http\Requests\Request::METHOD_POST && request()->get('form_id') == $form->id) {

        $validator = new FormsValidator($formItems);
        $validator->make(request()->all());


        $captchaFails = FALSE;
        if ($captcha != NULL) {
          $captchaFails = $captcha->fails();
        }

        /* $form->redirect_url = str_replace('{{url}}', str_replace(url('/'), '', request()->fullUrl()), $form->redirect_url);
          $form->redirect_url_error = str_replace('{{url}}', str_replace(url('/'), '', request()->fullUrl()), $form->redirect_url_error); */

        if (!$validator->fails() && !$captchaFails) {

          $aData['validator'] = $validator->getErrors(new MessageBag());
        } else {

          $messageBag = $validator->getErrors(new MessageBag());

          $aData['validator'] = $messageBag;

          if ($captcha != NULL) {
            $messageBag = $captcha->getErrors($messageBag);
          }

          $this->errorRedirect($messageBag, $form);
        }
      }


      return response()->json(['status' => 'ok', 'data' => $aData], 200);
    }
  }

  private function sendMail($form, $patt_func_params, $formItems, $savedResults) {

    if (isset($patt_func_params['attributes']['data-emails'])) {
      $aMails = array_filter(explode(',', $patt_func_params['attributes']['data-emails']));
      $email_check = TRUE;
    } else if (isset($patt_func_params['attributes']['data-append-emails'])) {
      $aTagMails = array_filter(explode(',', $patt_func_params['attributes']['data-append-emails']));
      $aMails = array_filter(explode(',', $form->emails));
      $aMails = array_merge($aTagMails, $aMails);
      $email_check = TRUE;
    } else if ($form->email_check == 1) {
      $aMails = array_filter(explode(',', $form->emails));
      $email_check = TRUE;
    } else {
      $aMails = array();
      $email_check = FALSE;
    }

    if ($email_check) {

      $aData['post'] = array();
      $aData['form'] = $form;
      $aReplyTo = array();
      $emailFrom = NULL;
      $files = array();


      foreach ($savedResults as $res) {
        $field_value = unserialize($res['field_value']);
        $key = $res['field_name'];

        $item = $formItems->where('field_name', $key)->first();

        if (!empty($item)) {

          $item_value = unserialize($item->field_value);
          $item_label = empty($item->label) ? $item->field_name : $item->label;


          if ($item->use_reply_to == 1) {
            $aReplyTo[] = $field_value;
          }

          if ($item->use_email_from == 1) {
            $emailFrom = $field_value;
          }

          if (is_array($field_value)) {
            $field_value = implode("\n", $field_value);
          }

          if ($item->field_type == FormsBuilder::$_TYPE_SELECT) {
            $aData['post'][$item_label] = \Module\Forms\Helpers\Export::normalizeValue1($item_value, $field_value);
          } else if ($item->field_type == FormsBuilder::$_TYPE_SELECT_COUNTRIES) {
            $aData['post'][$item_label] = \Module\Forms\Helpers\Export::normalizeValue2($item_value, $field_value);
          } else if ($item->field_type == FormsBuilder::$_TYPE_SELECT_STATES) {
            $aData['post'][$item_label] = \Module\Forms\Helpers\Export::normalizeValue2($item_value, $field_value);
          } else if ($item->field_type == FormsBuilder::$_TYPE_RADIO) {
            $aData['post'][$item_label] = \Module\Forms\Helpers\Export::normalizeValue1($item_value, $field_value);
          } elseif ($item->field_type == FormsBuilder::$_TYPE_FILE) {
            array_push($files, [
                'path' => base_path() . "/" . config("atlantis.user_media_upload") . $field_value,
                'filename' => $field_value
            ]);
            $aData['post'][$item_label] = $field_value;
          } else {
            $aData['post'][$item_label] = $field_value;
          }
        }
      }    

      foreach ($aMails as $mail_to) {

        \Illuminate\Support\Facades\Mail::send('forms::form-email', $aData, function ($message) use ($mail_to, $form, $aReplyTo, $emailFrom, $files) {

          $name = 'AtlantisCMS WebMaster';
          if (empty($form->email_from)) {
            $form->email_from = 'noreply@atlantis-cms.com';
          }

          if (!empty($emailFrom)) {
            $form->email_from = $emailFrom;
            $name = NULL;
          }

          $message->to(trim($mail_to));
          $message->from(trim($form->email_from), $name);
          $message->subject($form->name . ' form');
          
          if (count($aReplyTo) > 0) {
            $message->replyTo($aReplyTo);
          }

          foreach ($files as $file) {
            $message->attach($file['path'], [
                'as' => $file['filename']
            ]);
          }
        });
      }
    }
  }

  private function errorRedirect(MessageBag $messageBag, $form) {

   
    if (empty($form->redirect_url_error)) {
      app('AtlantisRedirect')->set(redirect()->back()->withErrors($messageBag)->withInput());
    } else {
      app('AtlantisRedirect')->set(redirect($form->redirect_url_error)->withErrors($messageBag)->withInput());
    }
  }

}
