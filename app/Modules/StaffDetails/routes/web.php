<?php

Route::group(['prefix' => 'staffdetails', 'module' => 'StaffDetails', 'middleware' => ['web'], 'namespace' => 'App\Modules\StaffDetails\Controllers'], function() {

	// Route::get(''); // get page
	Route::get('mainpage','StaffDetailsController@index')->name('staffdetails.page');
	Route::get('show/{id}','StaffDetailsController@show')->name('staffdetails.show');
	// Route::get('destroy/{id}','StaffDetailsController@destroy')->name('staffdetails.destroy');
    Route::get('{id}/delete', 'StaffDetailsController@destroy')->name('staffdetails.delete');
    Route::get('checkStaffId/{code}','StaffDetailsController@checkStaffId')->name('staffdetails.checkstaffid');
    Route::get('checkStaffEmail/{code}','StaffDetailsController@checkStaffEmail')->name('staffdetails.checkstaffemail');

	// Route::post(); // insert, update

	// annuncement
    Route::resource('/', 'StaffDetailsController');

});
