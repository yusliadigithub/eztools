<?php

Route::group(array('module' => 'Admin', 'middleware' => ['api'], 'namespace' => 'App\Modules\Admin\Controllers'), function() {

    Route::resource('admin', 'AdminController');
    
});	
