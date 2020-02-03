<?php namespace App\Http\Middleware;

use Closure, Session, Auth;
use App\Modules\Admin\Controllers\LanguageController;

class LocaleMiddleware {


    public function handle($request, Closure $next)
    {
        if(Auth::user()){
            app()->setLocale(Auth::user()->locale);
        }elseif($locale = Session::has('locale')){
            app()->setLocale($locale);
        }

        if(Auth::guard('users_guest')->check()){
			app()->setLocale( Auth::guard('users_guest')->user()->guest_locale );
        } 


        return $next($request);
    }

}