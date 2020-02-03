<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MenuModel extends Model implements AuditableContract {

	use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='base_menu';
    protected $primaryKey = 'menu_id';


    public function parent() {

        return $this->hasOne('App\Modules\Admin\Models\MenuModel', 'menu_id', 'parent_id')->orderBy('menu_sort','asc');

    }

    public function children() {

        return $this->hasMany('App\Modules\Admin\Models\MenuModel', 'parent_id', 'menu_id')->orderBy('menu_sort','asc');

    }  

    public static function tree($level=4) {

        return static::with(implode('.', array_fill(0, $level, 'children')))->where('parent_id', '=', 0)->where('menu_status','1')->orderBy('menu_sort','asc')->get();

    }

}
