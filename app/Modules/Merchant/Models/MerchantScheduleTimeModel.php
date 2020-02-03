<?php namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MerchantScheduleTimeModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='merchant_schedule_time';
    protected $primaryKey = 'merchant_schedule_time_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->merchant_schedule_day_id = $data['merchant_schedule_day_id'];
        $this->merchant_schedule_time_start = $data['merchant_schedule_time_start'];
        $this->merchant_schedule_time_end = $data['merchant_schedule_time_end'];
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();
            
    }

    public function days(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantScheduleDayModel','merchant_schedule_day_id');

    }

}
