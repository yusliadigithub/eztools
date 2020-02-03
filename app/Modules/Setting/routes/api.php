<?php

Route::group(['module' => 'Setting', 'middleware' => ['api'], 'namespace' => 'App\Modules\Setting\Controllers'], function() {

    Route::resource('Setting', 'SettingController');

});
