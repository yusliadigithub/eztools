<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class UsersDetailModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='users_detail';
    protected $primaryKey = 'users_detail_id';

    protected $fillable =[];

    public function user() {

        return $this->belongsTo('App\Modules\Admin\Models\UserModel', 'user_id');

    }

    public function country() {

        return $this->belongsTo('App\Modules\Admin\Models\MasterCountryModel', 'country_id');

    }

    public function gender() {

        return $this->belongsTo('App\Modules\Admin\Models\MasterGenderModel', 'gender_id');

    }

    public function salutation() {

        return $this->belongsTo('App\Modules\Admin\Models\MasterSalutationModel', 'salutation_id');

    }

}
