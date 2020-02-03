<?php namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MasterMerchantPackageSubModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='master_merchant_package_sub';
    protected $primaryKey = 'master_merchant_package_sub_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->merchant_package_id = $data['merchant_package_id'];
        $this->merchant_subpackage_id = $data['merchant_subpackage_id'];
        $this->created_by = Auth::user()->id;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->merchant_package_id = $data['merchant_package_id'];
        $item->merchant_subpackage_id = $data['merchant_subpackage_id'];
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

    public function package(){

        return $this->belongsTo('App\Modules\Merchant\Models\MasterMerchantPackageModel','merchant_package_id');

    }

    public function subpackage(){

        return $this->belongsTo('App\Modules\Merchant\Models\MasterMerchantSubPackageModel','merchant_subpackage_id');

    }

}
