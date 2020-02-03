<?php

Route::group(['prefix'=>'order','module' => 'Order', 'namespace' => 'App\Modules\Order\Controllers'], function() {

	Route::get('quotation{id}/delete', 'QuotationController@destroy')->name('order.quotation.delete');
    Route::resource('quotation', 'QuotationController', [ 'names'=>['index'=>'order.quotation.index','store'=>'order.quotation.store'], ]);

    Route::get('choosedraftaddress/{id}/{type}', 'OrderController@choosedraftaddress')->name('order.choosedraftaddress');
    Route::get('chooseaddress/{id}/{type}/{cartid}', 'OrderController@chooseaddress')->name('order.chooseaddress'); 
    Route::get('getaddress/{id}/{type}', 'OrderController@getaddress')->name('order.getaddress');
    Route::get('printdoc/{id}/{type}', 'OrderController@printdoc')->name('order.printdoc');
    Route::get('getorderinfo', 'OrderController@getorderinfo')->name('order.getorderinfo');
    Route::post('updatequotationstatus', 'OrderController@updatequotationstatus')->name('order.updatequotationstatus');
    Route::post('updatequotation', 'OrderController@updatequotation')->name('order.updatequotation');
    Route::get('invoice', 'OrderController@invoice')->name('order.invoice');
    Route::get('removeitem/{id}', 'OrderController@removeitem')->name('order.removeitem');
    Route::get('showquotation/{id}', 'OrderController@showquotation')->name('order.showquotation');
    Route::get('quotation', 'OrderController@quotation')->name('order.quotation');
    Route::post('submitcart', 'OrderController@submitcart')->name('order.submitcart'); 
    Route::post('keepcart', 'OrderController@keepcart')->name('order.keepcart'); 
    Route::get('getdraftaddress', 'OrderController@getdraftaddress')->name('order.getdraftaddress');
    Route::get('minuspluscartitem', 'OrderController@minuspluscartitem')->name('order.minuspluscartitem');
    Route::get('displaycart', 'OrderController@displaycart')->name('order.displaycart'); 
    Route::get('clearcart', 'OrderController@clearcart')->name('order.clearcart');
    Route::get('createcartsession', 'OrderController@createcartsession')->name('order.createcartsession');
    Route::get('checkexistcartsession', 'OrderController@checkexistcartsession')->name('order.checkexistcartsession');
    Route::get('{id}/deletecart', 'OrderController@deletecart')->name('order.deletecart');
    Route::get('removecartitem/{id}', 'OrderController@removecartitem')->name('order.removecartitem');
    Route::get('getcartinfo', 'OrderController@getcartinfo')->name('order.getcartinfo');
    Route::get('getcartlistformodal/{id}', 'OrderController@getcartlistformodal')->name('order.getcartlistformodal'); 
    Route::get('{id}/activatecart', 'OrderController@activatecart')->name('order.activatecart'); 
    Route::get('getGuestAddress/{gid}', 'OrderController@getGuestAddress')->name('order.getGuestAddress');
    Route::post('createcart', 'OrderController@createcart')->name('order.createcart');
    Route::get('getActiveCart/{sid}/{qty}/{mid}', 'OrderController@getActiveCart')->name('order.getActiveCart');
    Route::get('{id}/catalogdetail', 'OrderController@catalogdetail')->name('order.catalogdetail');
    Route::get('catalog', 'OrderController@catalog')->name('order.catalog');
    Route::get('{id}/delete', 'OrderController@destroy')->name('order.delete');
    Route::resource('/', 'OrderController', [ 'names'=>['index'=>'order.index'], ]);

});
