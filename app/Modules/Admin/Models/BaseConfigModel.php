<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class BaseConfigModel extends Model implements AuditableContract {

	use Auditable;

	public $timestamps = false;
    protected $table='base_config';
    protected $primaryKey = 'config_id';

}