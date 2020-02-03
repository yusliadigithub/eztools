<?php

Route::group(['prefix'=>'product','module' => 'product', 'namespace' => 'App\Modules\Product\Controllers'], function() {

	Route::get('attribute/getInfo/{id}','AttributeController@getInfo')->name('product.attribute.getInfo');
	Route::get('attribute/{id}/delete', 'AttributeController@destroy')->name('product.attribute.delete');
	Route::get('attribute/{id}/index', 'AttributeController@index')->name('product.attribute.index');
	Route::resource('attribute', 'AttributeController', [ 'names'=>['store'=>'product.attribute.store'], ]);

    //type
	Route::get('type/getInfo/{id}','TypeController@getInfo')->name('product.type.getInfo');
	Route::get('type/getParent/{id}/{eid}','TypeController@getParentCategoryByMerchant')->name('product.type.getParent');
	//Route::get('type/{id}/enable', 'PackageController@enableData')->name('product.type.enable');
	Route::get('type/{id}/disable', 'TypeController@disableData')->name('product.type.disable');
	Route::get('type/{id}/delete', 'TypeController@destroy')->name('product.type.delete');
	Route::get('type/{id}/index', 'TypeController@index')->name('product.type.index');
	Route::resource('type', 'TypeController', [ 'names'=>['store'=>'product.type.store'], ]);
	
	Route::get('productvariantmovement/{id}','ProductController@productvariantmovement')->name('product.productvariantmovement');
	Route::get('productmovement/{id}','ProductController@productmovement')->name('product.productmovement');
	Route::get('stockqtytrans/{id}','ProductController@stockqtytrans')->name('product.stockqtytrans');
	Route::get('stockqtyledger/{id}','ProductController@stockqtyledger')->name('product.stockqtyledger');
	Route::get('stockmovement/{id}','ProductController@stockmovement')->name('product.stockmovement');
	Route::post('adjustquantity', 'ProductController@adjustquantity')->name('product.adjustquantity');
	Route::get('getStockInfo/{id}','ProductController@getStockInfo')->name('product.getStockInfo'); 
	Route::get('getModalAttribute/{id}','ProductController@getModalAttribute')->name('product.getModalAttribute');
	//Route::post('createstock/{id}', 'ProductController@createstock')->name('product.createstock');
	Route::post('storestock', 'ProductController@storestock')->name('product.storestock');
	Route::post('setprice', 'ProductController@setprice')->name('product.setprice');
	Route::get('{id}/enablestock', 'ProductController@enablestock')->name('product.enablestock');
	Route::get('{id}/disablestock', 'ProductController@disablestock')->name('product.disablestock');
    Route::get('{id}/deletestock', 'ProductController@destroystock')->name('product.deletestock');
	Route::get('getValue/{id}/{rownum}','ProductController@getValue')->name('product.getValue');
	Route::get('getAttribute/{rownum}/{pid}','ProductController@getAttribute')->name('product.getAttribute');
	Route::get('getProductAttribute/{id}','ProductController@getProductAttribute')->name('product.getProductAttribute');
	Route::get('getInfo/{id}','ProductController@getInfo')->name('product.getInfo');
	//Route::get('{id}/confirm', 'ProductController@confirm')->name('product.confirm');
	Route::post('confirm', 'ProductController@confirm')->name('product.confirm');
	Route::get('{id}/enable', 'ProductController@enable')->name('product.enable');
	Route::get('{id}/disable', 'ProductController@disable')->name('product.disable');
    Route::get('{id}/delete', 'ProductController@destroy')->name('product.delete');
    Route::post('storeattribute', 'ProductController@storeattribute')->name('product.storeattribute');
    Route::get('attributes/{id}', 'ProductController@attribute')->name('product.attribute');
    Route::get('{id}/index', 'ProductController@index')->name('product.index');
    Route::resource('/', 'ProductController', [ 'names'=>['store'=>'product.store'], ]);

});
