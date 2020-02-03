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

class ProductTypeModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='product_type';
    protected $primaryKey = 'product_type_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $slug = Globe::checkslugvalue($data['merchant_id'],'product_type','product_type_slug',strtoupper($data['product_type_desc']));

        $this->merchant_id = $data['merchant_id'];
        $this->product_type_parent_id = ($data['product_type_parent_id']!='') ? $data['product_type_parent_id'] : 0;
        $this->product_type_desc = strtoupper($data['product_type_desc']);
        $this->product_type_slug = $slug;
        $this->created_by = Auth::user()->id;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();

        return $this->product_type_id;
            
    }

    protected function updateData($id, $data=[]) {

        $slug = Globe::checkslugvalue($data['merchant_id'],'product_type','product_type_slug',strtoupper($data['product_type_desc']),$id,'product_type_id');
        
        $item = self::find($id);
        $item->merchant_id = $data['merchant_id'];
        $item->product_type_parent_id = ($data['product_type_parent_id']!='') ? $data['product_type_parent_id'] : 0;
        $item->product_type_desc = strtoupper($data['product_type_desc']);
        $item->product_type_slug = $slug;
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

        return $item->product_type_id;

    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

    }

    public function image(){

        return $this->hasOne('App\Modules\Admin\Models\UploadModel','upload_model_id','product_type_id')->where('upload_model','ProductTypeModel');

    }

    public function parent(){

        return $this->belongsTo('App\Modules\Product\Models\ProductTypeModel','product_type_parent_id');

    }

}
