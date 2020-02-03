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

class ProductModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='product';
    protected $primaryKey = 'product_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $slug = Globe::checkslugvalue($data['merchant_id'],'product','product_slug',strtoupper($data['product_name']));

        $this->merchant_id = $data['merchant_id'];
        $this->product_type_id = $data['product_type_id'];
        $this->product_name = strtoupper($data['product_name']);
        $this->product_slug = $slug;
        //$this->product_description = $data['product_description'];
        $this->taxsupply_id = $data['taxsupply_id'];
        $this->taxpurchase_id = $data['taxpurchase_id'];
        $this->product_isvariable = ($data['product_isvariable']=='1') ? '1' : '0';
        $this->product_isstockcontrol = ($data['product_isstockcontrol']=='1') ? '1' : '0'; 
        $this->product_isdownloadable = ($data['product_isdownloadable']=='1') ? '1' : '0';
        $this->product_downloadurl = $data['product_downloadurl'];
        $this->created_by = Auth::user()->id;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();

        return $this->product_id;
            
    }

    protected function updateData($id, $data=[]) {

        $slug = Globe::checkslugvalue($data['merchant_id'],'product','product_slug',strtoupper($data['product_name']),$id,'product_id');
        
        $item = self::find($id);
        $item->merchant_id = $data['merchant_id'];
        $item->product_type_id = $data['product_type_id'];
        $item->product_name = strtoupper($data['product_name']);
        $item->product_slug = $slug;
        //$item->product_description = $data['product_description'];
        $item->taxsupply_id = $data['taxsupply_id'];
        $item->taxpurchase_id = $data['taxpurchase_id'];
        $item->product_isvariable = ($data['product_isvariable']=='1') ? '1' : '0';
        $item->product_isstockcontrol = ($data['product_isstockcontrol']=='1') ? '1' : '0';
        $item->product_isdownloadable = ($data['product_isdownloadable']=='1') ? '1' : '0';
        $item->product_downloadurl = $data['product_downloadurl'];
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

        return $item->product_id;

    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

    }

    public function image(){

        return $this->hasOne('App\Modules\Admin\Models\UploadModel','upload_model_id','product_id')->where('upload_model','ProductModel')->where('upload_type','1');

    }

    /*public function multiimage(){

        return $this->hasOne('App\Modules\Admin\Models\UploadModel','upload_model_id','product_id')->where('upload_model','ProductModel')->where('upload_type','2');

    }*/

    public function type(){

        return $this->belongsTo('App\Modules\Product\Models\ProductTypeModel','product_type_id');

    }

    public function attribute(){

        return $this->hasMany('App\Modules\Product\Models\ProductAttributeModel','product_id');

    }

    public function taxsupply(){

        return $this->belongsTo('App\Modules\Admin\Models\MasterTaxModel','taxsupply_id');

    }

    public function taxpurchase(){

        return $this->belongsTo('App\Modules\Admin\Models\MasterTaxModel','taxpurchase_id');

    }

    public function stock(){

        return $this->hasMany('App\Modules\Product\Models\ProductStockModel','product_id');

    }

    public function activeStock($limit=0){

        if($limit==0) :
            return $this->hasMany('App\Modules\Product\Models\ProductStockModel','product_id')
                ->where('product_stock_status', 1)->orderBy('product_stock_sale_price', 'asc');
        else :
            return $this->hasMany('App\Modules\Product\Models\ProductStockModel','product_id')
                ->where('product_stock_status', 1)->orderBy('product_stock_sale_price', 'asc')->first();
        endif;  

    }

    public function reviews() {
        return $this->hasMany('App\Modules\Product\Models\ProductReviewModel','product_id');
    }

}
