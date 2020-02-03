<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class MasterStateModel extends Model implements AuditableContract {

	use Auditable;

	public $timestamps = false;
    protected $table='master_state';
    protected $primaryKey = 'state_id';

    public function district(){

    	return $this->hasMany('App\Modules\Admin\Models\MasterDistrictModel','state_id');

    }

}