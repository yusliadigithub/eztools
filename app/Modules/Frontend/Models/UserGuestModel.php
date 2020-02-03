<?php

namespace App\Modules\Frontend\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserGuestModel extends Authenticatable implements AuditableContract {

	use Notifiable;
    use Auditable;
	use SoftDeletes;

	public $timestamps = false;
    protected $table='users_guest';
    protected $primaryKey = 'guest_id';

    protected $fillable =['guest_status', 'guest_locale', 'email', 'password'];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function phones() {
        return $this->hasMany('App\Modules\Frontend\Models\UserGuestPhoneModel','guest_id','guest_id');
    }

    public function addresses() {
        return $this->hasMany('App\Modules\Frontend\Models\UserGuestAddressModel','guest_id','guest_id');
    }

    public function cart(){
        return $this->hasMany('App\Modules\Order\Models\CartModel','guest_id');
    }

    public function shippingAddresses() {
       return $this->hasMany('App\Modules\Frontend\Models\UserGuestAddressModel', 'guest_id')->where('guest_address_type', 'SHIPPING');
    }

    public function billingAddresses() {
       return $this->hasMany('App\Modules\Frontend\Models\UserGuestAddressModel', 'guest_id')->where('guest_address_type', 'BILLING');
    }

    public function shippingAddress() {
       return $this->hasOne('App\Modules\Frontend\Models\UserGuestAddressModel', 'guest_id')->where('guest_address_type', 'SHIPPING')
                    ->where('guest_address_default', 1);
    }

    public function billingAddress() {
       return $this->hasOne('App\Modules\Frontend\Models\UserGuestAddressModel', 'guest_id')->where('guest_address_type', 'BILLING')
                    ->where('guest_address_default', 1);
    }

    public function merchant(){
        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');
    }

    public function state(){
        return $this->belongsTo('App\Modules\Admin\Models\MasterStateModel','state_id');
    }

    public function district(){
        return $this->belongsTo('App\Modules\Admin\Models\MasterDistrictModel','district_id');
    }



}
