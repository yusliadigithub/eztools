<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class UserModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='users';
    protected $primaryKey = 'id';

    protected $fillable =['status', 'locale'];

    public function insertNewUser($request) {

    	$this->username = $request->input('username');
		$this->password = Hash::make($request->input('password'));
		$this->email = $request->input('email');
		$this->name = $request->input('fullname');
		$this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
		$this->save();
			
    }

    public function updateUser($userid, $data=[]) {
    	
    	$user = self::find($userid);
    	$user->update($data);

    }

    public function deleteUser($userid) {
    	
    	$user = self::find($userid);
    	$user->delete();
    }

    public function property() {
        
        return $this->hasMany('App\Modules\Property\Models\PropertiesModel','properties_ownerid','id');
    }

    public function detail(){

        return $this->belongsTo('App\Modules\Admin\Models\UsersDetailModel','id','user_id');

    }

    public function userunit(){

        return $this->hasMany('App\Modules\Resident\Models\UsersUnitModel','user_id','id');

    }

    public function masteruserunit(){

        return $this->hasMany('App\Modules\Resident\Models\UsersUnitModel','user_id','id')->where('users_unit_isinternal','1');

    }

    public function externaluserunit(){

        return $this->hasMany('App\Modules\Resident\Models\UsersUnitModel','user_id','id')->where('users_unit_isinternal','0');

    }

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

    }

    /*public function role(){

        return $this->hasMany('Spatie\Permission\Models\Role','user_id','id');

    }*/

    public static function checkDataExist($column, $value, $created_id='') {

        if($created_id!=''):
            $count = UserModel::where('id','!=',$created_id)->where($column, $value)->count();
        else:
            $count = UserModel::where($column, $value)->count();
        endif;

        if($count > 0):
            return true;
        else:
            return false;
        endif;

    }

}
