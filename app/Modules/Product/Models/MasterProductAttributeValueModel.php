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

class MasterProductAttributeValueModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='master_product_attribute_value';
    protected $primaryKey = 'attribute_value_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->attribute_id = $data['attribute_id'];
        $this->attribute_value_desc = strtoupper($data['attribute_value_desc']);
        //$this->attribute_value_symbol = strtoupper($data['attribute_value_symbol']);
        $this->created_by = Auth::user()->id;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();

        return $this->attribute_value_id;
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->attribute_id = $data['attribute_id'];
        $item->attribute_value_desc = strtoupper($data['attribute_value_desc']);
        //$item->attribute_value_symbol = strtoupper($data['attribute_value_symbol']);
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

        return $item->attribute_value_id;

    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

    public function attribute(){

        return $this->belongsTo('App\Modules\Product\Models\MasterProductAttributeModel','attribute_id');

    }

}
