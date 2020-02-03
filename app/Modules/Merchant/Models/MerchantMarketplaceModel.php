<?php namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MerchantMarketplaceModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='merchant_marketplace';
    protected $primaryKey = 'merchant_marketplace_id';

    protected $fillable =[];

    protected function insertData($marketplaceid,$parentid) {

        $this->merchant_id = $parentid;
        $this->marketplace_id = $marketplaceid;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->merchant_id = $data['merchant_id'];
        $item->marketplace_id = $data['marketplace_id'];
        $item->save();

    }

    public function marketplace(){

        return $this->belongsTo('App\Modules\Merchant\Models\MasterMerchantMarketplaceModel','marketplace_id');

    }

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

    }

}
