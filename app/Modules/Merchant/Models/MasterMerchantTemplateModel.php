<?php namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class MasterMerchantTemplateModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='master_merchant_template';
    protected $primaryKey = 'template_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->template_name = TRIM(strtoupper($data['template_name']));
        $this->template_description = $data['template_description'];
        $this->template_url = $data['template_url'];
        $this->template_price = $data['template_price'];
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->template_name = TRIM(strtoupper($data['template_name']));
        $item->template_description = $data['template_description'];
        $item->template_url = $data['template_url'];
        $item->template_price = $data['template_price'];
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

    }

    public function deleteData($id) {
        
        $data = self::find($id);
        $data->delete();

    }

    public static function existData($desc){

        return MasterMerchantTemplateModel::where('template_name',TRIM(strtoupper($desc)))->count();

    }

}
