<?php namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MerchantScheduleDayModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='merchant_schedule_day';
    protected $primaryKey = 'merchant_schedule_day_id';

    protected $fillable =[];

    protected function insertData($parentid,$dayno) {

        $this->merchant_id = $parentid;
        $this->merchant_schedule_day_no = $dayno;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();
            
    }

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

    }

    public function times(){

        return $this->hasMany('App\Modules\Merchant\Models\MerchantScheduleTimeModel','merchant_schedule_day_id');

    }

    public function dayname(){

        return $this->belongsTo('App\Modules\Admin\Models\MasterNameModel','master_name_id','merchant_schedule_day_no');

    }

}
