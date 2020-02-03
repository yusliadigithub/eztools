<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class MasterCountryModel extends Model implements AuditableContract {

	use Auditable;

	public $timestamps = false;
    protected $table='master_country';
    protected $primaryKey = 'country_id';

    public function area(){

    	return $this->hasMany('App\Modules\Admin\Models\MasterAreaModel','country_id');

    }

}