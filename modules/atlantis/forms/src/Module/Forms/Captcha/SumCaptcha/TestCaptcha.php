<?php

namespace Module\Forms\Captcha\SumCaptcha;

class TestCaptcha implements \Module\Forms\Interfaces\CaptchaAdapterInterface {
    
  public function build($captchaPath) {
    
    return 'test captcha';
    
  }

  public function fails() {
    
    return TRUE;
    
  }

}

