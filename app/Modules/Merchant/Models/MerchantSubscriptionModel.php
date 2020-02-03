<?php namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MerchantSubscriptionModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='merchant_subscription';
    protected $primaryKey = 'merchant_subscription_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->merchant_id = $data['merchant_id'];
        $this->merchant_subscription_startdate = $data['merchant_subscription_startdate'];
        $this->merchant_subscription_enddate = $data['merchant_subscription_enddate'];
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->merchant_id = $data['merchant_id'];
        $item->merchant_subscription_startdate = $data['merchant_subscription_startdate'];
        $item->merchant_subscription_enddate = $data['merchant_subscription_enddate'];
        $item->save();

    }

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

    }

}
