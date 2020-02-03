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

class MasterMerchantPackageModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='master_merchant_package';
    protected $primaryKey = 'merchant_package_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->merchant_package_name = TRIM(strtoupper($data['merchant_package_name']));
        $this->merchant_package_description = strtoupper($data['merchant_package_description']); 
        $this->merchant_package_max_product = strtoupper($data['merchant_package_max_product']);
        /*$this->merchant_package_possystem = ($data['merchant_package_possystem']!='') ? '1' : '0' ;
        $this->merchant_package_ecommerce = ($data['merchant_package_ecommerce']!='') ? '1' : '0' ;
        $this->merchant_package_paymentgateway = ($data['merchant_package_paymentgateway']!='') ? '1' : '0' ;
        $this->merchant_package_lazada = ($data['merchant_package_lazada']!='') ? '1' : '0' ;
        $this->merchant_package_11street = ($data['merchant_package_11street']!='') ? '1' : '0' ;
        $this->merchant_package_zalora = ($data['merchant_package_zalora']!='') ? '1' : '0' ;*/
        $this->merchant_package_price = $data['merchant_package_price'];
        $this->merchant_package_renew_price = $data['merchant_package_renew_price'];
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();

        return $this->merchant_package_id;
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->merchant_package_name = TRIM(strtoupper($data['merchant_package_name']));
        $item->merchant_package_description = strtoupper($data['merchant_package_description']);
        $item->merchant_package_max_product = strtoupper($data['merchant_package_max_product']);
        /*$item->merchant_package_possystem = ($data['merchant_package_possystem']!='') ? '1' : '0' ;
        $item->merchant_package_ecommerce = ($data['merchant_package_ecommerce']!='') ? '1' : '0' ;
        $item->merchant_package_paymentgateway = ($data['merchant_package_paymentgateway']!='') ? '1' : '0' ;
        $item->merchant_package_lazada = ($data['merchant_package_lazada']!='') ? '1' : '0' ;
        $item->merchant_package_11street = ($data['merchant_package_11street']!='') ? '1' : '0' ;
        $item->merchant_package_zalora = ($data['merchant_package_zalora']!='') ? '1' : '0' ;*/
        $item->merchant_package_price = $data['merchant_package_price'];
        $item->merchant_package_renew_price = $data['merchant_package_renew_price'];
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

        return $item->merchant_package_id;

    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

    public static function existData($id,$desc){

        return MasterMerchantPackageModel::where('merchant_package_name',TRIM(strtoupper($desc)))->where('merchant_package_id','!=',$id)->count();

    }

    public function subpackage(){

        return $this->hasMany('App\Modules\Merchant\Models\MasterMerchantPackageSubModel','merchant_package_id');

    }

}
