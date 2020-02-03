<?php namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MerchantEmailModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='merchant_email';
    protected $primaryKey = 'merchant_email_id';

    protected $fillable =[];

    protected function insertData($parentid,$email,$type='0') {

        $this->merchant_id = $parentid;
        $this->merchant_email_address = $email;
        $this->merchant_email_type = $type;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();
            
    }

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

    }

}
