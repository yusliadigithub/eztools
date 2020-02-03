<?php namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\Models\UserModel;
use App\Modules\Frontend\Models\UserGuestModel;
use App\Modules\Merchant\Models\MerchantConfigurationModel;
use Auth, Session, Redirect;

class LanguageController extends Controller
{
	
	function __construct() 
	{
		// $this->middleware('auth');
	}


	public function setLocale($locale)
	{
		// if(Auth::check()){
	 //    	$user = UserModel::find(Auth::user()->id);
	 //     	$user->update(['locale'=>$locale]);

	 //  	} else {
	 //    	Session::put('locale',$locale);
	 //  	}

		if(Auth::check()){
	    	$user = UserModel::find(Auth::user()->id);
	     	$user->update(['locale'=>$locale]);
	  	} else {
	  		Session::put('locale',$locale);
	  	}

	  	if(Auth::guard('users_guest')->check()){

            $user = UserGuestModel::find( Auth::guard('users_guest')->user()->guest_id );
            $user->update(['guest_locale'=>$locale]);
            Session::put('frontend_locale',$locale);
        } else {
        	$merchant_config = MerchantConfigurationModel::where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id )->first();
        	Session::put('forntend_locale', $merchant_config->merchant_config_language);
            App()->setLocale( Session('frontend_locale') );
        }

	  	return Redirect()->back();
	}
}