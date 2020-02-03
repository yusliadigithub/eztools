<?php

namespace App\Modules\Frontend\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserGuestSubscriptionModel extends Authenticatable implements AuditableContract {

    use Notifiable;
    use Auditable;
	use SoftDeletes;

	public $timestamps = false;
    protected $table='users_guest_subscription';
    protected $primaryKey = 'guest_subscription_id';

    protected $fillable =['guest_id', 'guest_subscription_status', 'guest_subscription_email'];

    

}
