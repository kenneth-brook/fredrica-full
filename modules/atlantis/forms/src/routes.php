<?php
/*
 * Routes: Forms
 * @Atlantis CMS
 * v 1.0
 */

 Route::controller('admin/modules/forms' , 'Module\Forms\Controllers\Admin\FormsAdminController');
 Route::post('admin/modules/forms/clone-form/{id}' , 'Module\Forms\Controllers\Admin\FormsAdminController@postCloneForm');
 Route::post('admin/modules/formsFrontEndValidation' , 'Module\Forms\Controllers\FormsController@frontEndValidation');