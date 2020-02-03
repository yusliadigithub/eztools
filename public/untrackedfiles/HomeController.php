<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth; 
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = \App\User::with('roles')->get();
        $str = '';

        //dd(Auth::user()->hasRole('admin'));
        if(Auth::user()->hasRole('admin')){
            $str .= 'admin';
        }
        if(Auth::user()->hasRole('superadmin')){
            $str .= 'superadmin';
        }
        if(Auth::user()->hasRole('unitowner')){
            $str .= 'unitowner';
        }
        if(Auth::user()->hasRole('normaluser')){
            $str .= 'normaluser';
        }
        return view('home', ['pagetitle'=>'Home', 'pagedesc'=>'Dashboard', 'users'=>$users, 'str'=>$str]);
    }
}
