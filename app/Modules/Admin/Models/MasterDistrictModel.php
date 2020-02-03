<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class MasterDistrictModel extends Model implements AuditableContract {

	use Auditable;

	public $timestamps = false;
    protected $table='master_district';
    protected $primaryKey = 'district_id';

    public function state(){

    	return $this->belongsTo('App\Modules\Admin\Models\MasterStateModel','state_id');

    }

}