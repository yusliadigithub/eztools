<?php namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MasterProductAttributeModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='master_product_attribute';
    protected $primaryKey = 'attribute_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->merchant_id = $data['merchant_id'];
        $this->attribute_desc = strtoupper($data['attribute_desc']);
        //$this->attribute_symbol = strtoupper($data['attribute_symbol']);
        $this->created_by = Auth::user()->id;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();

        return $this->attribute_id;
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->merchant_id = $data['merchant_id'];
        $item->attribute_desc = strtoupper($data['attribute_desc']);
        //$item->attribute_symbol = strtoupper($data['attribute_symbol']);
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

        return $item->attribute_id;

    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

    public function value(){

        return $this->hasMany('App\Modules\Product\Models\MasterProductAttributeValueModel','attribute_id');

    }

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

    }

}
