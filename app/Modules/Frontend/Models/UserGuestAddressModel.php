<?php

namespace App\Modules\Frontend\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGuestAddressModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;

	public $timestamps = false;
    protected $table='users_guest_address';
    protected $primaryKey = 'guest_address_id';

    public function owner() {
        return $this->belongsTo('App\Modules\Frontend\Models\UserGuestModel','guest_id', 'guest_id');
    }

    public function district() {
        return $this->belongsTo('App\Modules\Admin\Models\MasterDistrictModel','district_id', 'district_id');
    }

    public function state() {
        return $this->belongsTo('App\Modules\Admin\Models\MasterStateModel','state_id', 'state_id');
    }

}
