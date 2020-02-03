<?php

Route::group(['prefix' => 'setting', 'module' => 'Setting', 'middleware' => ['web'], 'namespace' => 'App\Modules\Setting\Controllers'], function() {

	// Route::get(''); // get page
	Route::get('mainpage','SettingController@index')->name('setting.page');
	
	Route::get('show/{id}','SettingController@show')->name('Setting.show');

	// Route::post(); // insert, update

	// annuncement
    Route::resource('/', 'SettingController');


    //////////// setting star 
	Route::get('showchief/{id}','SettingControllerChief@show')->name('settingchief.show');
	// Route::get('destroychief/{id}','SettingControllerChief@destroy')->name('settingchief.destroy');
    Route::get('{id}/deletechief', 'SettingControllerChief@destroy')->name('settingchief.delete');

	Route::get('showstar/{id}','SettingControllerStar@show')->name('settingstar.show');
	// Route::get('destroystar/{id}','SettingControllerStar@destroy')->name('settingstar.destroy');
    Route::get('{id}/deletestar', 'SettingControllerStar@destroy')->name('settingstar.delete');

	Route::get('showrequest/{id}','SettingControllerRequest@show')->name('settingrequest.show');
	// Route::get('destroyrequest/{id}','SettingControllerRequest@destroy')->name('settingrequest.destroy');
    Route::get('{id}/deleterequest', 'SettingControllerRequest@destroy')->name('settingrequest.delete');

	Route::get('showpoint/{id}','SettingControllerPoint@show')->name('settingpoint.show');
	// Route::get('destroypoint/{id}','SettingControllerPoint@destroy')->name('settingpoint.destroy');
    Route::get('{id}/deletepoint', 'SettingControllerPoint@destroy')->name('settingpoint.delete');

	Route::get('showlevel/{id}','SettingControllerLevel@show')->name('settinglevel.show');
	// Route::get('destroylevel/{id}','SettingControllerLevel@destroy')->name('settinglevel.destroy');
    Route::get('{id}/deletelevel', 'SettingControllerLevel@destroy')->name('settinglevel.delete');

	Route::get('showjobposition/{id}','SettingControllerJobPosition@show')->name('settingjobposition.show');
	// Route::get('destroyjobposition/{id}','SettingControllerJobPosition@destroy')->name('settingjobposition.destroy');
    Route::get('{id}/deletejobposition', 'SettingControllerJobPosition@destroy')->name('settingjobposition.delete');

	Route::get('showdepartment/{id}','SettingControllerDepartment@show')->name('settingdepartment.show');
	// Route::get('destroydepartment/{id}','SettingControllerDepartment@destroy')->name('settingdepartment.destroy');
    Route::get('{id}/deletedepartment', 'SettingControllerDepartment@destroy')->name('settingdepartment.delete');
	
	Route::get('showempstatus/{id}','SettingControllerEmpStatus@show')->name('settingempstatus.show');
	// Route::get('destroyempstatus/{id}','SettingControllerEmpStatus@destroy')->name('settingempstatus.destroy');
    Route::get('{id}/deleteempstatus', 'SettingControllerEmpStatus@destroy')->name('settingempstatus.delete');

    Route::resource('settingstar', 'SettingControllerStar');
    Route::resource('settingrequest', 'SettingControllerRequest');
    Route::resource('settingpoint', 'SettingControllerPoint');
    Route::resource('settingchief', 'SettingControllerChief');
    Route::resource('settinglevel', 'SettingControllerLevel');
    Route::resource('settingjobposition', 'SettingControllerJobPosition');
    Route::resource('settingdepartment', 'SettingControllerDepartment');
    Route::resource('settingempstatus', 'SettingControllerEmpStatus');

});
