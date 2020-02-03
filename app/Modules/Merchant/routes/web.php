<?php

Route::group(['prefix'=>'merchant','module' => 'merchant', 'namespace' => 'App\Modules\Merchant\Controllers'], function() {

	//supplier
	Route::get('supplier/{id}/enable', 'SupplierController@enable')->name('merchant.supplier.enable');
	Route::get('supplier/{id}/disable', 'SupplierController@disable')->name('merchant.supplier.disable');
	Route::get('supplier/{id}/delete', 'SupplierController@destroy')->name('merchant.supplier.delete');
	Route::get('supplier/{id}/create', 'SupplierController@create')->name('merchant.supplier.create');
	Route::get('supplier/{id}/index', 'SupplierController@index')->name('merchant.supplier.index');
    Route::resource('supplier', 'SupplierController', [ 'names'=>['create'=>'merchant.supplier.create','store'=>'merchant.supplier.store','show'=>'merchant.supplier.show','update'=>'merchant.supplier.update'], ]);

	//type
	Route::get('type/{id}/delete', 'TypeController@destroy')->name('merchant.type.delete');
	Route::resource('type', 'TypeController', [ 'names'=>['index'=>'merchant.type.index','store'=>'merchant.package.store'], ]);

	//package
	Route::get('package/getSubpackageNew','PackageController@getSubpackageNew')->name('merchant.package.getSubpackageNew');
	Route::get('package/getSubPackageInfo/{id}','PackageController@getSubPackageInfo')->name('merchant.package.getSubPackageInfo');
	Route::get('package/getInfo/{id}','PackageController@getPackageInfo')->name('merchant.package.getPackageInfo');
	Route::get('package/{id}/enable', 'PackageController@enableData')->name('merchant.package.enable');
	Route::get('package/{id}/disable', 'PackageController@disableData')->name('merchant.package.disable');
	Route::get('package/{id}/delete', 'PackageController@destroy')->name('merchant.package.delete');
	Route::resource('package', 'PackageController', [ 'names'=>['index'=>'merchant.package.index','store'=>'merchant.package.store'], ]);

	//subpackage
	Route::get('subpackage/getInfo/{id}','SubPackageController@getInfo')->name('merchant.subpackage.getPackageInfo');
	Route::get('subpackage/{id}/enable', 'SubPackageController@enable')->name('merchant.subpackage.enable');
	Route::get('subpackage/{id}/disable', 'SubPackageController@disable')->name('merchant.subpackage.disable');
	Route::get('subpackage/{id}/delete', 'SubPackageController@destroy')->name('merchant.subpackage.delete');
	Route::resource('subpackage', 'SubPackageController', [ 'names'=>['index'=>'merchant.subpackage.index','store'=>'merchant.subpackage.store'], ]);

	//template 
	Route::get('template/getInfo/{id}','TemplateController@getTemplateInfo')->name('merchant.template.getTemplateInfo');
	Route::get('template/{id}/enable', 'TemplateController@enableData')->name('merchant.template.enable');
	Route::get('template/{id}/disable', 'TemplateController@disableData')->name('merchant.template.disable');
	Route::get('template/{id}/delete', 'TemplateController@destroy')->name('merchant.template.delete');
	Route::resource('template', 'TemplateController', [ 'names'=>['index'=>'merchant.template.index','store'=>'merchant.template.store','update'=>'merchant.template.update'], ]);

	//branch
	Route::get('branch/showinfo', 'BranchController@showinfo')->name('merchant.branch.showinfo');
	Route::get('branch/{id}/approve', 'BranchController@approve')->name('merchant.branch.approve');
	Route::get('branch/{id}/enable', 'BranchController@enable')->name('merchant.branch.enable');
	Route::get('branch/{id}/disable', 'BranchController@disable')->name('merchant.branch.disable');
	Route::get('branch/{id}/delete', 'BranchController@destroy')->name('merchant.branch.delete');
	Route::get('branch/{id}/create', 'BranchController@create')->name('merchant.branch.create');
	Route::get('branch/{id}/index', 'BranchController@index')->name('merchant.branch.index');
    Route::resource('branch', 'BranchController', [ 'names'=>['store'=>'merchant.branch.store','show'=>'merchant.branch.show','update'=>'merchant.branch.update'], ]);

    Route::get('payment/gateway/params', 'MerchantController@paymentGatewayParams')->name('merchant.payment.params');
    Route::post('changememberpassword', 'MerchantController@changememberpassword')->name('merchant.changememberpassword');
    Route::get('members/{id}', 'MerchantController@members')->name('merchant.members');
    Route::get('{id}/disablepage', 'MerchantController@disablepage')->name('merchant.disablepage'); 
    Route::get('{id}/enablepage', 'MerchantController@enablepage')->name('merchant.enablepage');
    Route::get('pageindex/{id}', 'MerchantController@pageindex')->name('merchant.pageindex');
    Route::get('createpage/{id}', 'MerchantController@createpage')->name('merchant.createpage');
    Route::post('updatepage', 'MerchantController@updatepage')->name('merchant.editpage');
    Route::get('showpage/{id}', 'MerchantController@showpage')->name('merchant.showpage');
    Route::get('{id}/deletepage', 'MerchantController@destroypage')->name('merchant.deletepage'); 
    Route::get('showconfig/{id}', 'MerchantController@showconfig')->name('merchant.showconfig');
    Route::get('getbuttonpkg/{id}', 'MerchantController@getbuttonpackage')->name('merchant.getbuttonpkg');
    Route::get('getbutton/{id}', 'MerchantController@getbutton')->name('merchant.getbutton');
    Route::post('editconfig', 'MerchantController@editconfig')->name('merchant.editconfig');
    Route::post('setdomain', 'MerchantController@setdomain')->name('merchant.setdomain');
    Route::get('showinfo', 'MerchantController@showinfo')->name('merchant.showinfo');
    Route::post('set/payment/gateway','MerchantController@setPaymentGateway')->name('merchant.setpayment');
    Route::post('save/payment/params', 'MerchantController@savePaymentParameters')->name('merchant.save.params');
	Route::get('{id}/delete', 'MerchantController@destroy')->name('merchant.delete'); 
	Route::resource('/', 'MerchantController', [ 'names'=>['index'=>'merchant.index','create'=>'merchant.create','store'=>'merchant.store','show'=>'merchant.show','update'=>'merchant.update'], ]);

});