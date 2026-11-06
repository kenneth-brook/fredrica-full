<?php


/*
 * Setup: UltimateGallery
 * @Atlantis CMS
 * v 1.0
 */

return [
    'name' => 'Ultimate Gallery',
    'author' => 'Atlantis CMS',
    'version' => '1.1',
    'adminURL' => 'admin/modules/ultimategallery',
    /**
     * ex. icon icon-Files
     * http://docteur-abrar.com/wp-content/themes/thunder/admin/stroke-gap-icons/index.html
     * 
     * ex. fa fa-beer
     * http://fontawesome.io/icons/
     */
    'icon' => '/Module/UltimateGallery/Assets/icons/icon.png',
    'path' => 'atlantis/ultimategallery/src',
    'moduleNamespace' => 'Module\UltimateGallery',
    'seedNamespace' => 'Module\UltimateGallery\Seed',    
    'seeder' => '\Module\UltimateGallery\Seed\UltimateGallerySeeder',
    'provider' => 'Module\UltimateGallery\Providers\UltimateGalleryServiceProvider',
    'migration' => 'modules/atlantis/ultimategallery/src/Module/UltimateGallery/Migrations/',
    'extra' => NULL,
    'description' => '',
        /**
        * 'create_pages' => [
        *   [
        *   'name' => 'Ultimate Gallery',
        *   'url' => 'NULL',
        *   'body' => '<div data-pattern-func="module:Ultimate Gallery@build"></div>'
        *   ]
        * ],
        * 'create_patterns' => [
        *   [
        *   'name' => 'Ultimate Gallery',
        *   'url' => 'NULL',
        *   'language' => 'en',
        *   'status' => '1',
        *   'mask' => '',
        *   ]
        * ]
        */
   ];
