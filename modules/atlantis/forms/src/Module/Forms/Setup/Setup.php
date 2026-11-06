<?php

/*
 * Setup: Forms
 * @Atlantis CMS
 * v 2.0.6
 */

return [
    'name' => 'Forms',
    'author' => 'Atlantis CMS',
    'version' => '2.6',
    'adminURL' => 'admin/modules/forms', // admin/modules/forms
    /**
     * ex. icon icon-Files
     * http://docteur-abrar.com/wp-content/themes/thunder/admin/stroke-gap-icons/index.html
     * 
     * ex. fa fa-beer
     * http://fontawesome.io/icons/
     */
    'icon' => 'icon icon-Files',
    'path' => 'atlantis/forms/src',
    'moduleNamespace' => 'Module\Forms',
    'seedNamespace' => 'Module\Forms\Seed',    
    'seeder' => '\Module\Forms\Seed\FormsSeeder',
    'provider' => 'Module\Forms\Providers\FormsServiceProvider',
    'migration' => 'modules/atlantis/forms/src/Module/Forms/Migrations/',
    'extra' => NULL,
    'description' => 'Module to help you generate web forms and embed them into pages.'
   ];
