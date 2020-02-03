<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class MasterNameModel extends Model implements AuditableContract {

	use Auditable;

	public $timestamps = false;
    protected $table='master_name';
    protected $primaryKey = 'master_name_id';

}