<?php

Route::group(['prefix'=>'agent','module' => 'agent', 'namespace' => 'App\Modules\Agent\Controllers'], function() {

	Route::get('{id}/delete', 'AgentController@destroy')->name('agent.delete');
    Route::resource('/', 'AgentController', [ 'names'=>['index'=>'agent','create'=>'agent.create','store'=>'agent.store'], ]);

});
