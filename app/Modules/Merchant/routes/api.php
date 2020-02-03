<?php

Route::group(['module' => 'Merchant', 'middleware' => ['api'], 'namespace' => 'App\Modules\Merchant\Controllers'], function() {

    Route::resource('merchant', 'MerchantController');

});
