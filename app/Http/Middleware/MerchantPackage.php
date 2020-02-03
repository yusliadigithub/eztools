<?php

namespace App\Http\Middleware;

use Auth, Session, Closure, Config;
use App\Modules\Merchant\Models\MerchantModel;
use App\Modules\Merchant\Models\MerchantConfigurationModel;
// use Illuminate\Http\Request;

class MerchantPackage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 'sendmail'   => "/usr/sbin/sendmail -bs",

        // get the domain name
        $domain = $_SERVER['SERVER_NAME'];
        $merchant = MerchantModel::where('merchant_domain', $domain)
                    ->select('merchant_id','merchant_name','merchant_uuid','merchant_domain')
                    ->first();

        $merchant_config = MerchantConfigurationModel::where('merchant_id', $merchant->merchant_id)->first();

        // set  email configuration for merchant
        if ($merchant_config) //checking if table is not empty
        {
            $config = array(
                'driver'     => 'smtp',
                'host'       => $merchant_config->merchant_config_smtp_host,
                'port'       => $merchant_config->merchant_config_smtp_port,
                'from'       => array('address' => $merchant_config->merchant_config_smtp_username, 'name' => $merchant_config->merchant->merchant_name),
                'encryption' => $merchant_config->merchant_config_smtp_encryption,
                'username'   => $merchant_config->merchant_config_smtp_username,
                'password'   => $merchant_config->merchant_config_smtp_password,
                'sendmail'   => '/usr/sbin/sendmail -bs',
                'pretend'    => false,
            );
            Config::set('mail', $config);
        }


        // set session for merchant based on entered URL if session not exist
        if(! $request->session()->exists($merchant->merchant_domain)) {
            $request->session()->put($domain, $merchant);

            // todo - get merchant template, package from table and inject into session
        }

        // set the ecommerce language session for first time
        if(! Session::has('locale') || !Auth::guard('users_guest')->check() ) {
            $request->session()->put('frontend_locale', $merchant_config->merchant_config_locale);
            App()->setLocale( Session('frontend_locale') );
        } 

        return $next($request);
    }
}
