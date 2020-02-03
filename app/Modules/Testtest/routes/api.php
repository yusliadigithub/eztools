<?php

Route::group(['module' => 'Testtest', 'middleware' => ['api'], 'namespace' => 'App\Modules\Testtest\Controllers'], function() {

    Route::resource('testtest', 'TesttestController');

});
