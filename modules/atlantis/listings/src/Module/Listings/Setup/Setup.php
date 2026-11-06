<?php


/*
 * Setup: Listings
 * @Atlantis CMS
 * v 1.0
 */

return [
    'name' => 'Listings',
    'author' => 'Atlantis CMS',
    'version' => '1.0',
    'adminURL' => 'admin/modules/listings',
    /**
     * ex. icon icon-Files
     * http://docteur-abrar.com/wp-content/themes/thunder/admin/stroke-gap-icons/index.html
     * 
     * ex. fa fa-beer
     * http://fontawesome.io/icons/
     */
    'icon' => 'fa fa-home',
    'path' => 'atlantis/listings/src',
    'moduleNamespace' => 'Module\Listings',
    'seedNamespace' => 'Module\Listings\Seed',    
    'seeder' => '\Module\Listings\Seed\ListingsSeeder',
    'provider' => 'Module\Listings\Providers\ListingsServiceProvider',
    'migration' => 'modules/atlantis/listings/src/Module/Listings/Migrations/',
    'extra' => NULL,
    'description' => '',
        /**
        * 'create_pages' => [
        *   [
        *   'name' => 'Listings',
        *   'url' => 'NULL',
        *   'body' => '<div data-pattern-func="module:Listings@build"></div>'
        *   ]
        * ],
        * 'create_patterns' => [
        *   [
        *   'name' => 'Listings',
        *   'url' => 'NULL',
        *   'language' => 'en',
        *   'status' => '1',
        *   'mask' => '',
        *   ]
        * ]
        */
   ];
