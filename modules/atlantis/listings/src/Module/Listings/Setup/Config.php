<?php

/*
 * Config: Listings
 * @Atlantis CMS
 * v 1.0
 */

return [
   /*
    * $this->app->bind("Module\Listings", "Atlantis\\Listings\\Controllers\\ListingsController");
    * 
    * 'appBind' => [
    *    'Module\Listings' => 'Module\\Listings\\Controllers\\ListingsController'
    *],
    *[
    *    'Module\Listings\CustomController' => 'Module\\Listings\\Controllers\\CustomController'
    *]
    */
    'appBind' => [
        'Module\Listings' => 'Module\\Listings\\Controllers\\ListingsController'
    ]
   ];
