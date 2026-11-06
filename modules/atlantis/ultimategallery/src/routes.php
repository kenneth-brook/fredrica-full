<?php
/*
 * Routes: UltimateGallery
 * @Atlantis CMS
 * v 1.0
 */

 Route::controller('admin/modules/ultimategallery' , 'Module\UltimateGallery\Controllers\Admin\UltimateGalleryAdminController');

 Route::post('admin/modules/ultimategallery/add' , 'Module\UltimateGallery\Controllers\Admin\UltimateGalleryAdminController@postAdd');

 /*Route::get('admin/modules/ultimategallery/edit/{id }}' , 'Module\UltimateGallery\Controllers\Admin\UltimateGalleryAdminController@getEdit');*/