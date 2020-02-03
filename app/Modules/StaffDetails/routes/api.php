<?php

Route::group(['module' => 'StaffDetails', 'middleware' => ['api'], 'namespace' => 'App\Modules\StaffDetails\Controllers'], function() {

    Route::resource('StaffDetails', 'StaffDetailsController');

});
