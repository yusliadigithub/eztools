<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class MasterPaymentMethodModel extends Model implements AuditableContract {

	use Auditable;

	public $timestamps = false;
    protected $table='master_payment_method';
    protected $primaryKey = 'payment_method_id';    

}