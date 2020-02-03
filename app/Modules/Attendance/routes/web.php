<?php

Route::group(['module' => 'Attendance', 'middleware' => ['web'], 'namespace' => 'App\Modules\Attendance\Controllers'], function() {

    Route::resource('attendance', 'AttendanceController');

});
