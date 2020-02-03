<?php

Route::group(['module' => 'Agent', 'middleware' => ['api'], 'namespace' => 'App\Modules\Agent\Controllers'], function() {

    Route::resource('agent', 'AgentController');

});
