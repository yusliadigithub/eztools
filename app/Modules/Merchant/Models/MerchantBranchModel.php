<?php namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Config;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Auth;

class MerchantBranchModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='merchant_branch';
    protected $primaryKey = 'merchant_branch_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        //$this->merchant_branch_uuid = \Webpatser\Uuid\Uuid::generate();
        $this->merchant_id = $data['merchant_id'];
        $this->merchant_branch_person_incharge = strtoupper($data['merchant_branch_person_incharge']);
        $this->merchant_branch_username = strtolower($data['merchant_branch_username']);
        $this->merchant_branch_email = strtolower($data['merchant_branch_email']);
        $this->merchant_branch_name = strtoupper($data['merchant_branch_name']);
        $this->merchant_branch_ssmno = strtoupper($data['merchant_branch_ssmno']);
        $this->merchant_branch_address1 = strtoupper($data['merchant_branch_address1']);
        $this->merchant_branch_address2 = strtoupper($data['merchant_branch_address2']);
        $this->merchant_branch_address3 = strtoupper($data['merchant_branch_address3']);
        $this->merchant_branch_postcode = $data['merchant_branch_postcode'];
        $this->district_id = $data['district_id'];
        $this->state_id = $data['state_id'];
        $this->merchant_branch_latitude = $data['merchant_branch_latitude'];
        $this->merchant_branch_longitude = $data['merchant_branch_longitude'];
        $this->merchant_branch_officeno = $data['merchant_branch_officeno'];
        $this->merchant_branch_faxno = $data['merchant_branch_faxno'];
        $this->merchant_branch_mobileno = $data['merchant_branch_mobileno'];
        $this->created_by = Auth::user()->id;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();

        $data2 = ['merchant_branch_id'=>$this->merchant_branch_id, 
                     'merchant_id'=>$this->merchant_id];

        return $data2;
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        //$item->merchant_id = $data['merchant_id'];
        $item->merchant_branch_person_incharge = strtoupper($data['merchant_branch_person_incharge']);
        $item->merchant_branch_username = strtolower($data['merchant_branch_username']);
        $item->merchant_branch_email = strtolower($data['merchant_branch_email']);
        $item->merchant_branch_name = strtoupper($data['merchant_branch_name']);
        $item->merchant_branch_ssmno = strtoupper($data['merchant_branch_ssmno']);
        $item->merchant_branch_address1 = strtoupper($data['merchant_branch_address1']);
        $item->merchant_branch_address2 = strtoupper($data['merchant_branch_address2']);
        $item->merchant_branch_address3 = strtoupper($data['merchant_branch_address3']);
        $item->merchant_branch_postcode = $data['merchant_branch_postcode'];
        $item->district_id = $data['district_id'];
        $item->state_id = $data['state_id'];
        $item->merchant_branch_latitude = $data['merchant_branch_latitude'];
        $item->merchant_branch_longitude = $data['merchant_branch_longitude'];
        $item->merchant_branch_officeno = $data['merchant_branch_officeno'];
        $item->merchant_branch_faxno = $data['merchant_branch_faxno'];
        $item->merchant_branch_mobileno = $data['merchant_branch_mobileno'];
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

        return $item->merchant_branch_id;

    }

    public function deleteData($id) {
    	
    	$data = self::find($id);
    	$data->delete();

    }

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

    }

    public function user(){

        return $this->belongsTo('App\Modules\Admin\Models\UserModel','branch_id','merchant_branch_id');

    }

    public function storefront(){

        return $this->hasOne('App\Modules\Admin\Models\UploadModel','upload_model_id','merchant_branch_id')->where('upload_model','MerchantBranchModel');

    }

}
