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

class ProductStockDetailModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='product_stock_detail';
    protected $primaryKey = 'product_stock_detail_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->product_stock_id = $data['product_stock_id'];
        $this->attribute_id = $data['attribute_id'];
        $this->attribute_value_id = $data['attribute_value_id'];
        //$this->created_by = Auth::user()->id;
        $this->created_at = Carbon::now();
        $this->updated_at = Carbon::now();
        $this->save();

        return $this->product_stock_detail_id;
            
    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

    public static function existdata($stock,$attr){

        return ProductStockDetailModel::where('product_stock_id',$stock)->where('attribute_id',$attr)->count();

    }

    public static function existdatawithvalue($stock,$attr,$val){

        return ProductStockDetailModel::where('product_stock_id',$stock)->where('attribute_id',$attr)->where('attribute_value_id',$val)->count();

    } 

    public static function existdatavaluenull($stock,$attr){

        return ProductStockDetailModel::where('product_stock_id',$stock)->where('attribute_id',$attr)->whereNull('attribute_value_id')->count();

    }

    public function stock(){

        return $this->belongsTo('App\Modules\Product\Models\ProductStockModel','product_stock_id');

    }

    public function attribute(){

        return $this->belongsTo('App\Modules\Product\Models\MasterProductAttributeModel','attribute_id');

    }

    public function value(){

        return $this->belongsTo('App\Modules\Product\Models\MasterProductAttributeValueModel','attribute_value_id');

    }

}
