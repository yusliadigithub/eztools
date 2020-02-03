<?php namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;
use Globe;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class CartModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='cart';
    protected $primaryKey = 'cart_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->merchant_id = Auth::user()->merchant_id;
        $this->created_by = Auth::user()->id;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();

        return $this->cart_id;
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

        return $item->cart_id;

    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

    }

    public function guest(){

        return $this->belongsTo('App\Modules\Frontend\Models\UserGuestModel','guest_id');

    }

    public function address(){

        return $this->belongsTo('App\Modules\Frontend\Models\UserGuestAddressModel','guest_address_id');

    }

    public function detail(){

        return $this->hasMany('App\Modules\Order\Models\CartDetailModel','cart_id');

    }

    public function paymentslip(){

        return $this->hasOne('App\Modules\Admin\Models\UploadModel','upload_model_id','cart_id')->where('upload_model','CartModel');

    }

    public function paymentslips(){

        return $this->hasMany('App\Modules\Admin\Models\UploadModel','upload_model_id','cart_id')->where('upload_model','CartModel');

    }

}
