<?php namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MasterMerchantMarketplaceModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='master_merchant_marketplace';
    protected $primaryKey = 'marketplace_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->marketplace_desc = strtoupper($data['marketplace_desc']);
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->marketplace_desc = strtoupper($data['marketplace_desc']);
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

}
