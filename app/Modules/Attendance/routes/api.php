<?php

Route::group(['module' => 'Attendance', 'middleware' => ['api'], 'namespace' => 'App\Modules\Attendance\Controllers'], function() {

    Route::resource('attendance', 'AttendanceController');

});
