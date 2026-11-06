<?php

/*
 * Config: CustomFields
 * @Atlantis CMS
 * v 1.0
 */

return [
   /*
    * $this->app->bind("Module\CustomFields", "Atlantis\\CustomFields\\Controllers\\CustomFieldsController");
    * 
    * 'appBind' => [
    *    'Module\CustomFields' => 'Module\\CustomFields\\Controllers\\CustomFieldsController'
    *],
    *[
    *    'Module\CustomFields\CustomController' => 'Module\\CustomFields\\Controllers\\CustomController'
    *]
    */
    'appBind' => [
        'Module\CustomFields' => 'Module\\CustomFields\\Controllers\\CustomFieldsController'
    ]
   ];
