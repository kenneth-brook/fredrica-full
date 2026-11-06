<?php

/*
 *  \Config::get('blog.config') ;
 */


return [
    /*
     * $this->app->bind("Modules\Blog", "Module\\Blog\\Controllers\\BlogController");
     * 
     * 'appBind' => [
     *    'Modules\Blog' => 'Module\\Blog\\Controllers\\BlogController'
     * ],
     * [
     *    'Modules\Blog\CustomController' => 'Module\\Blog\\Controllers\\CustomController'
     * ]
     */
    'appBind' => [
        'Module\Blog' => 'Module\\Blog\\Controllers\\BlogController'
    ]   
];
