<?php

namespace Module\Forms\Interfaces;

interface CaptchaAdapterInterface {

  /**
   * 
   * @param String $captchaPath
   * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory || String
   */
  public function build($captchaPath, $form);

  /**
   * @return Bool
   */
  public function fails();
}
