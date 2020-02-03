<?php namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class ProductAttributeModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='product_attribute';
    protected $primaryKey = 'product_attribute_id';

    protected $fillable =[];

    public function product(){

        return $this->belongsTo('App\Modules\Product\Models\ProductModel','product_id');

    }

    public function attribute(){

        return $this->belongsTo('App\Modules\Product\Models\MasterProductAttributeModel','attribute_id');

    }

    public function value(){

        return $this->hasMany('App\Modules\Product\Models\ProductAttributeValueModel','product_attribute_id');

    }

}
