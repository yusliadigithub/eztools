<?php

Route::group(['module' => 'Testtest', 'middleware' => ['web'], 'namespace' => 'App\Modules\Testtest\Controllers'], function() {

    Route::resource('testtest', 'TesttestController');

});
