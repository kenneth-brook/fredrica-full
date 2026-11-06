<?php

namespace Module\Forms\Captcha\ReCaptcha;

class InvisibleReCaptchaAdapter implements \Module\Forms\Interfaces\CaptchaAdapterInterface {

  /**
   * Register API keys at https://www.google.com/recaptcha/admin
   * 
   * @var String
   */
  private $site_key = NULL;
  private $secret = NULL;

  /**
   * reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
   * 
   * @var String
   */
  private $lang = 'en';
  
  private $fails = TRUE;

  public function build($captchaPath, $form) {
    
    $this->site_key = \Module\Forms\Models\Repositories\FormsConfigRepository::getConfigKey('invisible_re_captcha_site_key');
    $this->secret = \Module\Forms\Models\Repositories\FormsConfigRepository::getConfigKey('invisible_re_captcha_secret');
    
    if (empty($this->site_key) || empty($this->secret)) {
      return 'Please set site key and secret';
    }
    
    if (request()->method() == \App\Http\Requests\Request::METHOD_POST && request()->has('g-recaptcha-response')) {

      /*require_once $captchaPath . 'ReCaptcha/vendor/src/autoload.php';

      $recaptcha = new \ReCaptcha\ReCaptcha($this->secret);

      $resp = $recaptcha->verify(request()->get('g-recaptcha-response'), $_SERVER['REMOTE_ADDR']);
      
      if ($resp->isSuccess()) {
        $this->fails = FALSE;
      } else {
        $this->fails = TRUE;
        $aErrors["captcha"] = "Wrong captcha";
      }*/
    }

    $aData = array();
    $aData['site_key'] = $this->site_key;
    $aData['lang'] = $this->lang;
    $aData['invisible'] = true;
    $aData['scope'] = 'captcha' . rand();
    $aData['form'] = $form;

    return $view = view()->file($captchaPath . 'ReCaptcha/Views/ReCaptcha.blade.php', $aData);
  }

  public function fails() {

   $data = array(
    'secret' => $this->secret,
    'response' => request()->get('g-recaptcha-response')
  );

   $verify = curl_init();
   curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
   curl_setopt($verify, CURLOPT_POST, true);
   curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
   curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
   $response = curl_exec($verify);

   $response = json_decode($response);

   return !$response->success; 

 }

}
