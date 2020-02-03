<?php namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class ProductStockTransactionModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='product_stock_transaction';
    protected $primaryKey = 'product_stock_transaction_id';

    protected $fillable =[];

    public function movement(){

        return $this->belongsTo('App\Modules\Product\Models\ProductStockMovementModel','product_stock_movement_id');

    }

}
