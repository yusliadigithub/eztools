<?php namespace App\Modules\Order\Models;

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

class CartDetailModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='cart_detail';
    protected $primaryKey = 'cart_detail_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();

        return $this->cart_id;
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

        return $item->cart_id;

    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

    public function stock(){

        return $this->belongsTo('App\Modules\Product\Models\ProductStockModel','product_stock_id');

    }

    public function cart(){

        return $this->belongsTo('App\Modules\Order\Models\CartModel','cart_id');

    }

}
