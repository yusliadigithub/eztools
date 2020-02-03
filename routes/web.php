<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    
    if( session()->has('referer') ):
    	session()->forget('referer');
    endif;
    
    return redirect('login');
});

Auth::routes();

Route::get('login/admin', function() {
	// Session::put('referer') = $_SERVER["HTTP_REFERER"];
	session(['referer' => $_SERVER['REQUEST_URI'] ]);
	return redirect('login');
});

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('home', 'HomeController@index')->name('home');

Route::get('/admin/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('admin.logs');

