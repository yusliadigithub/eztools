<?php namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Auth;

class MerchantPaymentMethodModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='merchant_payment_method';
    protected $primaryKey = 'merchant_payment_method_id';

    protected $fillable =[];

    public function merchant() {
    	return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel', 'merchant_id');
    }

    public function payment_method() {
        return $this->belongsTo('App\Modules\Admin\Models\MasterPaymentMethodModel', 'payment_method_id');
    }

    
}