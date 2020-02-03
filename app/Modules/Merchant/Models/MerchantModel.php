<?php namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Modules\Merchant\Models\MerchantMobileModel;
use App\Modules\Merchant\Models\MerchantEmailModel;
use App\Modules\Merchant\Models\MerchantScheduleDayModel;
use App\Modules\Merchant\Models\MerchantConfigurationModel;

use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Auth;
use Config;

//class MerchantModel extends Model implements AuditableContract {
class MerchantModel extends Model {

    //use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='merchant';
    protected $primaryKey = 'merchant_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->merchant_uuid = \Webpatser\Uuid\Uuid::generate(3,$data['merchant_ssmno'],\Webpatser\Uuid\Uuid::NS_DNS);
        $this->merchant_person_incharge = strtoupper($data['merchant_person_incharge']);
        $this->merchant_username = strtolower($data['merchant_username']);
        $this->merchant_email = strtolower($data['merchant_email']);
        $this->merchant_name = strtoupper($data['merchant_name']);
        $this->merchant_ssmno = strtoupper($data['merchant_ssmno']);
        $this->merchant_gstno = strtoupper($data['merchant_gstno']);
        $this->merchant_type_id = $data['merchant_type_id'];
        $this->merchant_description = strtoupper($data['merchant_description']);
        $this->merchant_address1 = strtoupper($data['merchant_address1']);
        $this->merchant_address2 = strtoupper($data['merchant_address2']);
        $this->merchant_address3 = strtoupper($data['merchant_address3']);
        $this->merchant_postcode = $data['merchant_postcode'];
        $this->district_id = $data['district_id'];
        $this->state_id = $data['state_id'];
        $this->merchant_latitude = $data['merchant_latitude'];
        $this->merchant_longitude = $data['merchant_longitude'];
        $this->merchant_website = $data['merchant_website'];
        $this->merchant_officeno = $data['merchant_officeno'];
        $this->merchant_faxno = $data['merchant_faxno'];
        $this->merchant_mobileno = $data['merchant_mobileno'];

        $this->template_id = $data['template_id'];
        $this->merchant_package_id = $data['merchant_package_id'];
        
        $this->merchant_expirydate = date('Y-m-d');
        $this->created_by = ($data['agent_id']=='') ? Auth::user()->id : $data['agent_id'];
        $this->created_by_ori = Auth::user()->id;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        
        if($this->save()){

            MerchantMobileModel::insertData($this->merchant_id,$data['merchant_mobileno'],'1');
            MerchantEmailModel::insertData($this->merchant_id,$data['merchant_email'],'1');

            for($i=1;$i<8;$i++){
                MerchantScheduleDayModel::insertData($this->merchant_id,$i);
            }

            MerchantConfigurationModel::insertData($this->merchant_id,$data);

        }

        return $this->merchant_id;
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->merchant_person_incharge = strtoupper($data['merchant_person_incharge']);
        $item->merchant_username = strtolower($data['merchant_username']);
        $item->merchant_email = strtolower($data['merchant_email']);
        $item->merchant_name = strtoupper($data['merchant_name']);
        $item->merchant_ssmno = strtoupper($data['merchant_ssmno']);
        $item->merchant_gstno = strtoupper($data['merchant_gstno']);
        $item->merchant_type_id = strtoupper($data['merchant_type_id']);
        $item->merchant_description = strtoupper($data['merchant_description']);
        $item->merchant_address1 = strtoupper($data['merchant_address1']);
        $item->merchant_address2 = strtoupper($data['merchant_address2']);
        $item->merchant_address3 = strtoupper($data['merchant_address3']);
        $item->merchant_postcode = $data['merchant_postcode'];
        $item->district_id = $data['district_id'];
        $item->state_id = $data['state_id'];
        $item->merchant_latitude = $data['merchant_latitude'];
        $item->merchant_longitude = $data['merchant_longitude'];
        $item->merchant_website = $data['merchant_website'];
        $item->merchant_officeno = $data['merchant_officeno'];
        $item->merchant_faxno = $data['merchant_faxno'];
        $item->merchant_mobileno = $data['merchant_mobileno'];

        $item->template_id = $data['template_id'];
        $item->merchant_package_id = $data['merchant_package_id'];
        //$item->merchant_expirydate = date('Y-m-d');
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        if($item->save()){

            MerchantMobileModel::where('merchant_id',$id)->where('merchant_mobile_type','1')->update(['merchant_mobile_no'=>$data['merchant_mobileno']]);
            MerchantEmailModel::where('merchant_id',$id)->where('merchant_email_type','1')->update(['merchant_email_address'=>$data['merchant_email']]);

            if($item->user->status_approve != '1'){
                MerchantConfigurationModel::updateDataFromParent($item->merchant_id,$data);
            }
            
        }

        return $item->merchant_id;

    }

    public function deleteData($id) {
    	
    	$data = self::find($id);
    	$data->delete();

    }

    public static function domainexist($id,$name){

        return MerchantModel::where('merchant_id','!=',$id)->where('merchant_domain',$name)->count();

    }

    public function subscription(){

        return $this->hasMany('App\Modules\Merchant\Models\MerchantSubscriptionModel','merchant_id');

    }

    public function marketplace(){

        return $this->hasMany('App\Modules\Merchant\Models\MerchantMarketplaceModel','merchant_id');

    }

    public function package(){

        return $this->belongsTo('App\Modules\Merchant\Models\MasterMerchantPackageModel','merchant_package_id');

    }

    public function mobileno(){

        return $this->hasMany('App\Modules\Merchant\Models\MerchantMobileModel','merchant_id')->where('merchant_mobile_type','0');

    }

    public function emailaddress(){

        return $this->hasMany('App\Modules\Merchant\Models\MerchantEmailModel','merchant_id')->where('merchant_email_type','0');

    }

    public function configuration(){

        return $this->hasOne('App\Modules\Merchant\Models\MerchantConfigurationModel','merchant_id','merchant_id');

    }

    public function logo(){

        return $this->hasOne('App\Modules\Admin\Models\UploadModel','upload_model_id','merchant_id')->where('upload_model','MerchantModel')->where('upload_type','1');

    }

    public function flyer(){

        return $this->hasOne('App\Modules\Admin\Models\UploadModel','upload_model_id','merchant_id')->where('upload_model','MerchantModel')->where('upload_type','2');

    }

    public function storefront(){

        return $this->hasOne('App\Modules\Admin\Models\UploadModel','upload_model_id','merchant_id')->where('upload_model','MerchantModel')->where('upload_type','3');

    }

    public function background(){

        return $this->hasOne('App\Modules\Admin\Models\UploadModel','upload_model_id','merchant_id')->where('upload_model','MerchantModel')->where('upload_type','4');

    }

    public function schedule(){

        return $this->hasMany('App\Modules\Merchant\Models\MerchantScheduleDayModel','merchant_id');

    }

    public function user(){

        return $this->hasOne('App\Modules\Admin\Models\UserModel','merchant_id','merchant_id')->whereNull('branch_id');

    }

    public function agent(){

        return $this->hasOne('App\Modules\Admin\Models\UserModel','id','created_by');

    }

    public function paymentmethod() {

        return $this->hasMany('App\Modules\Merchant\Models\MerchantPaymentMethodModel','merchant_id');

    }

}
