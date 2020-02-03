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

class MasterMerchantSubPackageModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='master_merchant_subpackage';
    protected $primaryKey = 'merchant_subpackage_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->merchant_subpackage_desc = strtoupper($data['merchant_subpackage_desc']);
        $this->merchant_subpackage_api = $data['merchant_subpackage_api'];
        $this->created_by = Auth::user()->id;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->merchant_subpackage_desc = strtoupper($data['merchant_subpackage_desc']);
        $item->merchant_subpackage_api = $data['merchant_subpackage_api'];
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

    public static function existData($id,$desc){

        return MasterMerchantSubPackageModel::where('merchant_subpackage_desc',TRIM(strtoupper($desc)))->where('merchant_subpackage_id','!=',$id)->count();

    }

}
