<?php namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Auth;
use Globe;
use Config;

class MerchantPageModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='merchant_page';
    protected $primaryKey = 'merchant_page_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $slug = Globe::checkslugvalue($data['merchant_id'],'merchant_page','merchant_page_slug',strtoupper($data['merchant_page_title']));

        $this->merchant_id = $data['merchant_id'];
        $this->merchant_page_parent_id = ($data['merchant_page_parent_id']=='') ? 0 : $data['merchant_page_parent_id'];
        $this->merchant_page_slug = $slug;
        $this->merchant_page_title = $data['merchant_page_title'];
        $this->merchant_page_content = $data['merchant_page_content'];
        $this->merchant_page_order = $data['merchant_page_order'];
        $this->merchant_page_status = ($data['merchant_page_status']=='') ? 0 : $data['merchant_page_status'];
        $this->merchant_page_date = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->created_by = Auth::user()->id;
        $this->modified_by = Auth::user()->id;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();

        return $this->merchant_page_id;
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);

        $slug = Globe::checkslugvalue($item->merchant_id,'merchant_page','merchant_page_slug',strtoupper($data['merchant_page_title']),$id,'merchant_page_id');

        $item->merchant_page_parent_id = ($data['merchant_page_parent_id']=='') ? 0 : $data['merchant_page_parent_id'];
        $item->merchant_page_slug = $slug;
        $item->merchant_page_title = $data['merchant_page_title'];
        $item->merchant_page_content = $data['merchant_page_content'];
        $item->merchant_page_order = $data['merchant_page_order'];
        

        if($data['merchant_page_status']!=''){
            $item->merchant_page_status = ($data['merchant_page_status']=='') ? 0 : $data['merchant_page_status'];
            $item->merchant_page_date = Carbon::now(Config::get('constants.common.systemtimezone'));
        }

        $item->modified_by = Auth::user()->id;
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

        return $item->merchant_page_id;

    }

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

    }

}
