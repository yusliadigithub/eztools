<?php

namespace App\Modules\Frontend\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserGuestPhoneModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;

	public $timestamps = false;
    protected $table='users_guest_phone';
    protected $primaryKey = 'guest_phone_id';


    public function owner(){

        return $this->belongsTo('App\Modules\Frontend\Models\UserGuestModel','guest_id', 'guest_id');
    }

}
