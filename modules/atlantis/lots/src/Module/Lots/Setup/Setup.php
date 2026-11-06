<?php


/*
 * Setup: Lots
 * @Atlantis CMS
 * v 1.0
 */

return [
    'name' => 'Lots',
    'author' => 'Atlantis CMS',
    'version' => '1.1',
    'adminURL' => 'admin/modules/lots',
    /**
     * ex. icon icon-Files
     * http://docteur-abrar.com/wp-content/themes/thunder/admin/stroke-gap-icons/index.html
     * 
     * ex. fa fa-beer
     * http://fontawesome.io/icons/
     */
    'icon' => 'fa fa-map',
    'path' => 'atlantis/lots/src',
    'moduleNamespace' => 'Module\Lots',
    'seedNamespace' => 'Module\Lots\Seed',    
    'seeder' => '\Module\Lots\Seed\LotsSeeder',
    'provider' => 'Module\Lots\Providers\LotsServiceProvider',
    'migration' => 'modules/atlantis/lots/src/Module/Lots/Migrations/',
    'extra' => NULL,
    'description' => '',
        /**
        * 'create_pages' => [
        *   [
        *   'name' => 'Lots',
        *   'url' => 'NULL',
        *   'body' => '<div data-pattern-func="module:Lots@build"></div>'
        *   ]
        * ],
        * 'create_patterns' => [
        *   [
        *   'name' => 'Lots',
        *   'url' => 'NULL',
        *   'language' => 'en',
        *   'status' => '1',
        *   'mask' => '',
        *   ]
        * ]
        */
   ];
