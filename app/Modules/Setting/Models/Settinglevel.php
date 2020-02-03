<?php

namespace App\Modules\Setting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;
use Globe;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class SettingLevel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='setting_level';
    protected $primaryKey = 'level_id';

    protected $fillable =[];
}
