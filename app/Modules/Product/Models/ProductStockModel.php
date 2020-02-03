<?php namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;
use Globe;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class ProductStockModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='product_stock';
    protected $primaryKey = 'product_stock_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->product_stock_id = $data['product_stock_id'];
        $this->product_id = $data['product_id'];
        //$this->attribute_id = $data['attribute_id'];
        //$this->attribute_value_id = $data['attribute_value_id'];
        //$this->created_by = Auth::user()->id;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();

        return $this->product_stock_id;
            
    }

    protected function updatestockname($id,$val) {

        $slug = Globe::checkslugvalue('product_stock','product_stock_slug',$val);

        $stock = ProductStockModel::findOrFail($id);
        $stock->product_stock_name = $val;
        $stock->product_stock_slug = $slug; 
        $stock->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $stock->save();

    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

    public static function existid($id){

        return ProductStockModel::where('product_stock_id',$id)->count();

    }

    public function product(){

        return $this->belongsTo('App\Modules\Product\Models\ProductModel','product_id');

    }

    public function detail(){

        return $this->hasMany('App\Modules\Product\Models\ProductStockDetailModel','product_stock_id');

    }

    public function attribute(){

        return $this->belongsTo('App\Modules\Product\Models\MasterProductAttributeModel','attribute_id');

    }

    public function value(){

        return $this->belongsTo('App\Modules\Product\Models\MasterProductAttributeValueModel','attribute_value_id');

    }

    public function image(){

        return $this->hasOne('App\Modules\Admin\Models\UploadModel','upload_model_id','product_stock_id')->where('upload_model','ProductStockModel')->where('upload_type','1');

    }

    public function multiimage(){

        return $this->hasMany('App\Modules\Admin\Models\UploadModel','upload_model_id','product_stock_id')->where('upload_model','ProductStockModel')->where('upload_type','2');

    }

}
