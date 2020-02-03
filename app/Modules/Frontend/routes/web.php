<?php

// Route::group(['prefix'=>'','module' => 'Frontend', 'middleware' => ['web'], 'namespace' => 'App\Modules\Frontend\Controllers'], function() {
Route::group(['prefix'=>'frontend', 'middleware'=>'check_merchant', 'namespace' => 'App\Modules\Frontend\Controllers'], function() {

	Route::get('online/payment/{type}/{cartid}', 'FrontendController@onlinePayment')->name('frontend.online.payment');
	Route::get('logout', 'FrontendController@logout')->name('frontend.logout');
	Route::get('login', 'FrontendController@login')->name('frontend.login');
	Route::get('account', 'FrontendController@myaccount')->name('frontend.account');
	Route::get('manage/address', 'FrontendController@manageAddress')->name('frontend.manage.address');
	Route::get('order', 'FrontendController@order')->name('frontend.order');
	Route::get('order/detail/{orderid}', 'FrontendController@orderDetail')->name('frontend.order.detail');
	Route::get('delete/phone/{id}', 'FrontendController@deletePhone')->name('frontend.delete.phone');
	Route::get('delete/address/{id}', 'FrontendController@deleteAddress')->name('frontend.delete.address');
	Route::get('language/{lang}', 'FrontendController@setLanguage')->name('frontend.update.language');
	Route::get('stock/detail', 'FrontendController@getStockDetail')->name('frontend.stock.detail');
	Route::get('subscribe/confirm/{enc}/{type}', 'FrontendController@confirmSubscription')->name('frontend.subscribe.confirm');
	Route::get('cart','FrontendController@cart')->name('frontend.cart');
	Route::get('cart/add', 'FrontendController@addToCart')->name('frontend.add.cart');
	Route::get('cart/remove', 'FrontendController@removeItemCart')->name('frontend.remove.cart');
	Route::get('cart/amend', 'FrontendController@plusMinusItemCart')->name('frontend.amend.cart');
	Route::get('cart/checkout', 'FrontendController@checkout')->name('frontend.checkout.cart');
	Route::get('setdefault/address', 'FrontendController@setDefaultAddress')->name('frontend.setdefault.address');
	Route::get('page/{slug}', 'FrontendController@page')->name('frontend.page');
	Route::get('{slug}', 'FrontendController@show')->name('frontend.show'); // letak paling bawah di dalam get nnti dia kacau	

	Route::post('payment/response', 'FrontendController@paymentResponse')->name('frontend.payment.response'); // return from payment gateway
	Route::post('product/review/{id}','FrontendController@reviewProduct')->name('frontend.product.review');
	Route::post('subscribe/newsletter', 'FrontendController@subscribeNewsletter')->name('frontend.subscribe');
	Route::post('insert/address', 'FrontendController@insertMyAddress')->name('frontend.insert.address');
	Route::post('insert/phone', 'FrontendController@insertPhone')->name('frontend.insert.phone');
	Route::post('update/account', 'FrontendController@updateMyaccount')->name('frontend.update.account');
	Route::post('register','FrontendController@register')->name('frontend.register');
	Route::post('reset/password','FrontendController@resetpassword')->name('frontend.reset.password');
	Route::post('process/login', 'FrontendController@processlogin')->name('frontend.process.login');
	Route::post('upload/payment_slip','FrontendController@uploadPaymentSlip')->name('frontend.upload.payslip');
	Route::post('delete/payment_slip','FrontendController@deletePaymentSlip')->name('frontend.delete.payslip');
	Route::resource('/', 'FrontendController', [ 'names'=>['index'=>'frontend','show'=>'frontend.show'] ] );

});
