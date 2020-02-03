<?php namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MasterMerchantTypeModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='master_merchant_type';
    protected $primaryKey = 'merchant_type_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->merchant_type_desc = TRIM(strtoupper($data['merchant_type_desc']));
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->merchant_type_desc = TRIM(strtoupper($data['merchant_type_desc']));
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

    public static function existData($desc){

        return MasterMerchantTypeModel::where('merchant_type_desc',TRIM(strtoupper($desc)))->count();

    }

}
