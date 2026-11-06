<?php


/*
 * Setup: VanityUrl
 * @Atlantis CMS
 * v 1.2
 */

return [
    'name' => 'Vanity Url',
    'author' => 'Atlantis CMS',
    'version' => '1.8',
    'adminURL' => 'admin/modules/vanityurl', // 
    /**
     * ex. icon icon-Files
     * http://docteur-abrar.com/wp-content/themes/thunder/admin/stroke-gap-icons/index.html
     * 
     * ex. fa fa-beer
     * http://fontawesome.io/icons/
     */
    'icon' => 'icon icon-Layers',
    'path' => 'atlantis/vanityurl/src',
    'moduleNamespace' => 'Module\VanityUrl',
    'seedNamespace' => 'Module\VanityUrl\Seed',    
    'seeder' => '\Module\VanityUrl\Seed\VanityUrlSeeder',
    'provider' => 'Module\VanityUrl\Providers\VanityUrlServiceProvider',
    'migration' => 'modules/atlantis/vanityurl/src/Module/VanityUrl/Migrations/',
    'extra' => NULL,
    'description' => ''
   ];
