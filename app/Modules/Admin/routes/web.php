<?php



// Route::group(array('module' => 'Admin', 'middleware' => ['web'], 'namespace' => 'App\Modules\Admin\Controllers'), function() { // don't know why session message not appear when using this route group

Route::group(['prefix'=>'admin', 'namespace' => 'App\Modules\Admin\Controllers'], function() {

    Route::get('getStateDistrict/{code}','AdminController@getStateDistrict')->name('admin.getstatedistrict');
	Route::get('getDistrict/{id}','AdminController@getDistrict')->name('admin.getdistrict');
	Route::get('audit-trail', 'AdminController@auditTrail')->name('admin.auditrail');
    Route::get('delete-audit-trail','AdminController@clearAuditTrail')->name('admin.clear.auditrail');
	Route::get('configuration','AdminController@configuration')->name('admin.config');
    Route::get('dashboard','AdminController@index')->name('admin.dashboard');
	Route::post('update-config','AdminController@updateConfiguration')->name('admin.config.update');
	Route::resource('/', 'AdminController', [ 'names'=>['index'=>'admin.dashboard'], ]); 

	// Set Language
    Route::post('language/set-language/{lang}', 'LanguageController@setLocale')->name('setlanguage');
    Route::resource('language', 'LanguageController'); 

    // Roles
    Route::post('roles/storepermission','RoleController@storepermission')->name('admin.roles.storepermission');
    Route::resource('roles','RoleController', [ 'names'=>['index'=>'admin.roles'], ]);

    // Permissions
    Route::get('permission/getUserPermission/{id}','PermissionController@getUserPermission')->name('admin.permission.getUserPermission');
    Route::get('permission/getRolePermission/{id}','PermissionController@getRolePermission')->name('admin.permission.getRolePermission');
    Route::post('permission/update/{id}','PermissionController@update');
    Route::resource('permission','PermissionController', [ 'names'=>['index'=>'admin.permission'], ]);

    // Menus
    Route::get('menus/{id}/enable', 'MenuController@enable')->name('admin.menus.enable');
    Route::get('menus/{id}/disable', 'MenuController@disable')->name('admin.menus.disable');
    Route::get('menus/delete/{id}','MenuController@destroy')->name('admin.menus.delete');
    Route::post('menus/update/{id}','MenuController@update');
    Route::resource('menus','MenuController', [ 'names'=>['index'=>'admin.menus'], ]);
    
});	

Route::group(['prefix'=>'language', 'namespace' => 'App\Modules\Admin\Controllers'], function() {

	Route::get('set/{lang}', 'LanguageController@setLocale')->name('setlanguage');
    Route::resource('/', 'LanguageController');
});	

Route::group(['prefix'=>'user', 'namespace' => 'App\Modules\Admin\Controllers'], function() {

    Route::post('updateagent','UserController@updateagent')->name('user.updateagent');
    Route::post('storeagent','UserController@storeagent')->name('user.storeagent');
    Route::get('showagent/{id}','UserController@showagent')->name('user.showagent');
    Route::get('createagent','UserController@createagent')->name('user.createagent');
    Route::post('storepermission','UserController@storepermission')->name('user.storepermission');
    Route::get('getUserRole/{id}','UserController@getUserRole')->name('user.getUserRole');
    Route::get('getUserInfo/{id}','UserController@getUserInfo')->name('user.getuserinfo');
	Route::get('{id}/show','UserController@show')->name('user.show');
	Route::get('{id}/delete','UserController@destroy')->name('user.delete');
	Route::get('{id}/approve', 'UserController@approveUser')->name('user.approve');
	Route::get('{id}/enable', 'UserController@enableUser')->name('user.enable');
	Route::get('{id}/disable', 'UserController@disableUser')->name('user.disable');
    Route::get('index', 'UserController@index')->name('user.index');
	Route::post('process-action', 'UserController@processaction');
    Route::resource('/', 'UserController');

});
