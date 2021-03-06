<?php namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class ProductStockMovementModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='product_stock_movement';
    protected $primaryKey = 'product_stock_movement_id';

    protected $fillable =[];

    public function ledger(){

        return $this->belongsTo('App\Modules\Product\Models\ProductStockLedgerModel','product_stock_ledger_id');

    }

    public function transaction(){

        return $this->hasMany('App\Modules\Product\Models\ProductStockTransactionModel','product_stock_movement_id');

    }

}
