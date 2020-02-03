<?php namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class ProductAttributeValueModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='product_attribute_value';
    protected $primaryKey = 'product_attribute_value_id';

    protected $fillable =[];

    public function attribute(){

        return $this->belongsTo('App\Modules\Product\Models\ProductAttributeModel','product_attribute_id');

    }

    public function value(){

        return $this->belongsTo('App\Modules\Product\Models\MasterProductAttributeValueModel','attribute_value_id');

    }

}
