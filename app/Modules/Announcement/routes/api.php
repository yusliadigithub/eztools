<?php

Route::group(['module' => 'Announcement', 'middleware' => ['api'], 'namespace' => 'App\Modules\Announcement\Controllers'], function() {

    Route::resource('Announcement', 'AnnouncementController');

});
