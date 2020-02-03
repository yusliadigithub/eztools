<?php

Route::group(['module' => 'Pdca', 'middleware' => ['api'], 'namespace' => 'App\Modules\Pdca\Controllers'], function() {

    Route::resource('pdca', 'PdcaController');

});
