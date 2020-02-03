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

class ProductReviewModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    // use HasRoles;
    public $timestamps = false;
    protected $table='product_review';
    protected $primaryKey = 'product_review_id';

    protected $fillable =[];

    public function product() {
    	return $this->belongsTo('App\Modules\Merchant\Models\ProductModel','product_id');
    }

    public function guest() {
        return $this->belongsTo('App\Modules\Frontend\Models\UserGuestModel','guest_id');
    }

}