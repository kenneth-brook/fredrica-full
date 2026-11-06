<?php

namespace Module\Forms\Helpers;

use Illuminate\Support\MessageBag;

class Captcha {

  private $adapterNamespace;
  private $setupForms;
  private $captcha;
  private $captchaPath;
  private $captchaConfig;
  private $buildCaptcha;

  public function __construct($captchaNamespace, $form = NULL) {    
    
    $this->captchaConfig = self::getCaptchaConfig($captchaNamespace);
    $this->adapterNamespace = $this->captchaConfig['adapterNamespace'];
    $this->setupForms = config('forms.setup');
    $this->captchaPath = base_path() . config('atlantis.modules_dir') . $this->setupForms['path'] . '/Module/Forms/Captcha/';
    
    $this->form = $form;

    $this->captcha = new $this->adapterNamespace();
    $this->buildCaptcha = $this->captcha->build($this->captchaPath, $this->form);
  }

  /**
   * Return captcha
   * 
   * @return captcha view
   */
  public function get() {

    return $this->buildCaptcha;
  }

  /**
   * Check if captcha is valid
   * 
   * @return BOOL
   */
  public function fails() {
    return $this->captcha->fails();
  }

  /**
   * Get error message
   * 
   * @return \Illuminate\Support\MessageBag
   */
  public function getErrors(MessageBag $messageBag) {

    if ($this->captcha->fails()) {

      return $messageBag->add('captcha', 'Wrong captcha');
    } else {
      return $messageBag;
    }
  }

  /**
   * Find all in /Captcha folder and return in array with configs
   * 
   * @return array
   */
  public static function getAll($formsSetup) {

    $captchaPath = base_path() . config('atlantis.modules_dir') . $formsSetup['path'] . '/Module/Forms/Captcha';

    $dirs = array_diff(scandir($captchaPath), array('.', '..'));

    $aCaptchas = array();

    foreach ($dirs as $dir) {

      $path = $captchaPath . '/' . $dir;

      if (is_dir($path)) {

        $configFile = $path . '/config.php';

        if (is_file($configFile)) {

          $captchaConfig = require($configFile);
         
          array_push($aCaptchas, $captchaConfig);
        }
      }
    }

    return $aCaptchas;
  }

  public static function getCaptchaConfig($captchaNamespace) {

    $formsSetup = config('forms.setup');
    
    $aConfigs = self::getAll($formsSetup);
 
    foreach ($aConfigs as $config) {
      if ($captchaNamespace == $config['namespace']) {
        
        return $config;
      }
    }
   
    abort(404, 'Captcha config not found by namespace: ' . $captchaNamespace);
  }

}
