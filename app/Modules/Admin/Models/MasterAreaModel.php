<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class MasterAreaModel extends Model implements AuditableContract {

	use Auditable;

	public $timestamps = false;
    protected $table='master_area';
    protected $primaryKey = 'area_id';


    public function country(){

    	return $this->belongsTo('App\Modules\Admin\Models\MasterCountryModel','country_id');

    }

    public function state(){

    	return $this->hasMany('App\Modules\Admin\Models\MasterStateModel','area_id');

    }

}