<?php


/*
 * Setup: CustomFields
 * @Atlantis CMS
 * v 1.1
 */

return [
    'name' => 'Custom Fields',
    'author' => 'Atlantis CMS',
    'version' => '1.1',
    'adminURL' => 'admin/modules/customfields', // admin/modules/customfields
    /**
     * ex. icon icon-Files
     * http://docteur-abrar.com/wp-content/themes/thunder/admin/stroke-gap-icons/index.html
     * 
     * ex. fa fa-beer
     * http://fontawesome.io/icons/
     */
    'icon' => 'fa fa-cubes',
    'path' => 'atlantis/customfields/src',
    'moduleNamespace' => 'Module\CustomFields',
    'seedNamespace' => 'Module\CustomFields\Seed',    
    'seeder' => '\Module\CustomFields\Seed\CustomFieldsSeeder',
    'provider' => 'Module\CustomFields\Providers\CustomFieldsServiceProvider',
    'migration' => 'modules/atlantis/customfields/src/Module/CustomFields/Migrations/',
    'extra' =>
        [
            'type' => 'admin.tab.pages',
            'adminTabClass' => 'Module\CustomFields\CustomFieldsBuilder'
        ],
    'description' => 'This module extends Atlantis Page by giving the user an option to create custom fields and attach them to a page.'
   ];
