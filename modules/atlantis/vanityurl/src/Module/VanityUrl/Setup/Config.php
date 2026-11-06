<?php

/*
 * Config: VanityUrl
 * @Atlantis CMS
 * v 1.0
 */

return [
   /*
    * $this->app->bind("Module\VanityUrl", "Atlantis\\VanityUrl\\Controllers\\VanityUrlController");
    * 
    * 'appBind' => [
    *    'Module\VanityUrl' => 'Module\\VanityUrl\\Controllers\\VanityUrlController'
    *],
    *[
    *    'Module\VanityUrl\CustomController' => 'Module\\VanityUrl\\Controllers\\CustomController'
    *]
    */
    'appBind' => [
        'Module\VanityUrl' => 'Module\\VanityUrl\\Controllers\\VanityUrlController'
    ]
   ];
