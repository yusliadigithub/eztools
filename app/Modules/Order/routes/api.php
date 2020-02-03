<?php

Route::group(['module' => 'Order', 'middleware' => ['api'], 'namespace' => 'App\Modules\Order\Controllers'], function() {

    Route::resource('order', 'OrderController');

});
