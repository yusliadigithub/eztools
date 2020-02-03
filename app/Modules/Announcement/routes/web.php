<?php

Route::group(['prefix'=>'announcement', 'module' => 'Announcement', 'middleware' => ['web'], 'namespace' => 'App\Modules\Announcement\Controllers'], function() {

	// Route::get(''); // get page
	Route::get('mainpage','AnnouncementController@index')->name('announcement.page');
	Route::get('show/{id}','AnnouncementController@show')->name('announcement.show');
	// Route::get('destroy/{id}','AnnouncementController@destroy')->name('announcement.destroy');
    Route::get('{id}/delete', 'AnnouncementController@destroy')->name('announcement.delete');

	// Route::post(); // insert, update

	// annuncement
	Route::resource('/', 'AnnouncementController');
});
