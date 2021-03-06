<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class MasterGenderModel extends Model implements AuditableContract {

	use Auditable;

	public $timestamps = false;
    protected $table='master_gender';
    protected $primaryKey = 'gender_id';

}