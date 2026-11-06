<?php
/*
 * Routes: CustomFields
 * @Atlantis CMS
 * v 1.0
 */


Route::group(['prefix' => 'admin/modules/customfields'], function ()
{
    Route::get('/' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@getIndex');

    Route::get('add-field' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@getAddField');

    Route::post('add-field' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@postAddField');

    Route::get('edit-field/{id}' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@getEditField');

    Route::post('edit-field/{id}' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@postEditField');

    Route::get('delete/{id}' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@getDeleteField');

    Route::post('bulk-action' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@postBulkDelete');

    Route::get('categories' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@getCategories');

    Route::get('add-category' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@getAddCategory');

    Route::post('add-category' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@postAddCategory');

    Route::get('edit-category/{id}' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@getEditCategory');

    Route::post('edit-category/{id}' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@postEditCategory');

    Route::get('delete-category/{id}' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@getDeleteCategory');

    Route::post('bulk-action-categories' , 'Module\CustomFields\Controllers\Admin\CustomFieldsAdminController@postBulkDeleteCategories');

    Route::post('get-category-fields', 'Module\CustomFields\Controllers\CustomFieldsController@postGetCategoryFields');

});