<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class MasterRunningNumberModel extends Model implements AuditableContract {

	use Auditable;

	public $timestamps = false;
    protected $table='master_running_number';
    protected $primaryKey = 'running_number_id';

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

    }

}