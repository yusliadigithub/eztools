<?php

Route::group(['prefix'=>'pdca','module' => 'Pdca', 'middleware' => ['web'], 'namespace' => 'App\Modules\Pdca\Controllers'], function() {

    
	Route::get('show/{id}','PdcaController@show')->name('pdca.show');
	// Route::get('destroy/{id}','PdcaController@destroy')->name('pdca.destroy');
    Route::get('{id}/delete', 'PdcaController@destroy')->name('pdca.delete');
    Route::get('pending-list', 'PdcaController@pendingList')->name('pdca.pending.list');
    Route::get('approved-list', 'PdcaController@approvedList')->name('pdca.approved.list');

    Route::resource('/', 'PdcaController');

});
