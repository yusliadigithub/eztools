<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class BaseIncModel extends Model implements AuditableContract {

	use Auditable;

	public $timestamps = false;
    protected $table='base_inc';
    protected $primaryKey = 'inc_id';

    public static function getNewNumber($prefix){

    	$inc = BaseIncModel::where('inc_prefix',$prefix)->first();
    	$newno = $inc->inc_currentno + 1;

    	BaseIncModel::where('inc_prefix',$prefix)->update(['inc_currentno'=>$newno]);

    	return $newno;

    }

}