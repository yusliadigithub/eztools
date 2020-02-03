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

class MerchantConfigurationModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='merchant_configuration';
    protected $primaryKey = 'merchant_config_id';

    protected $fillable =[];

    protected function insertData($parentid,$data=[]) {

        $this->merchant_id = $parentid;
        $this->merchant_config_address1 = strtoupper($data['merchant_address1']);
        $this->merchant_config_address2 = strtoupper($data['merchant_address2']);
        $this->merchant_config_address3 = strtoupper($data['merchant_address3']);
        $this->merchant_config_postcode = $data['merchant_postcode'];
        $this->merchant_config_email = strtolower($data['merchant_email']);
        $this->district_id = $data['district_id'];
        $this->state_id = $data['state_id'];

        $this->merchant_config_website = $data['merchant_website'];
        $this->merchant_config_officeno = $data['merchant_officeno'];
        $this->merchant_config_faxno = $data['merchant_faxno'];
        $this->merchant_config_mobileno = $data['merchant_mobileno'];
        $this->merchant_config_latitude = $data['merchant_latitude'];
        $this->merchant_config_longitude = $data['merchant_longitude'];
        
        $this->created_by = Auth::user()->id;
        $this->modified_by = Auth::user()->id;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();
            
    }

    protected function updateDataFromParent($parentid,$data=[]) {

        $item = self::where('merchant_id',$parentid)->first();
        $item->merchant_id = $parentid;
        $item->merchant_config_address1 = strtoupper($data['merchant_address1']);
        $item->merchant_config_address2 = strtoupper($data['merchant_address2']);
        $item->merchant_config_address3 = strtoupper($data['merchant_address3']);
        $item->merchant_config_postcode = $data['merchant_postcode'];
        $item->merchant_config_email = strtolower($data['merchant_email']);
        $item->district_id = $data['district_id'];
        $item->state_id = $data['state_id'];

        $item->merchant_config_website = $data['merchant_website'];
        $item->merchant_config_officeno = $data['merchant_officeno'];
        $item->merchant_config_faxno = $data['merchant_faxno'];
        $item->merchant_config_mobileno = $data['merchant_mobileno'];
        $item->merchant_config_latitude = $data['merchant_latitude'];
        $item->merchant_config_longitude = $data['merchant_longitude'];
        
        $item->modified_by = Auth::user()->id;
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        $item->merchant_config_appname = $data['merchant_config_appname'];
        $item->merchant_config_android = $data['merchant_config_android'];
        $item->merchant_config_ios = $data['merchant_config_ios'];

        $item->merchant_config_address1 = strtoupper($data['merchant_config_address1']);
        $item->merchant_config_address2 = strtoupper($data['merchant_config_address2']);
        $item->merchant_config_address3 = strtoupper($data['merchant_config_address3']);
        $item->merchant_config_postcode = $data['merchant_config_postcode'];
        $item->merchant_config_email = strtolower($data['merchant_config_email']);
        $item->district_id = $data['district_id'];
        $item->state_id = $data['state_id'];

        $item->merchant_config_website = $data['merchant_config_website'];
        $item->merchant_config_officeno = $data['merchant_config_officeno'];
        $item->merchant_config_faxno = $data['merchant_config_faxno'];
        $item->merchant_config_mobileno = $data['merchant_config_mobileno'];
        $item->merchant_config_latitude = $data['merchant_config_latitude'];
        $item->merchant_config_longitude = $data['merchant_config_longitude'];

        $item->merchant_config_voucher1 = ($data['merchant_config_voucher1']=='') ? 0 : $data['merchant_config_voucher1'];
        $item->merchant_config_voucher2 = ($data['merchant_config_voucher2']=='') ? 0 : $data['merchant_config_voucher2'];
        $item->merchant_config_voucher3 = ($data['merchant_config_voucher3']=='') ? 0 : $data['merchant_config_voucher3'];
        $item->merchant_config_voucher4 = ($data['merchant_config_voucher4']=='') ? 0 : $data['merchant_config_voucher4'];
        $item->merchant_config_voucher5 = ($data['merchant_config_voucher5']=='') ? 0 : $data['merchant_config_voucher5'];
        $item->merchant_config_voucher6 = ($data['merchant_config_voucher6']=='') ? 0 : $data['merchant_config_voucher6'];
        $item->merchant_config_termsconditions = $data['merchant_config_termsconditions'];

        $item->merchant_config_ship_status = ($data['merchant_config_ship_status']=='') ? 1 : $data['merchant_config_ship_status'];
        $item->merchant_config_ship_west_upto_weight = $data['merchant_config_ship_west_upto_weight'];
        $item->merchant_config_ship_west_upto_price = $data['merchant_config_ship_west_upto_price'];
        $item->merchant_config_ship_west_add_weight = $data['merchant_config_ship_west_add_weight'];
        $item->merchant_config_ship_west_add_price = $data['merchant_config_ship_west_add_price'];
        $item->merchant_config_ship_east_upto_weight = $data['merchant_config_ship_east_upto_weight'];
        $item->merchant_config_ship_east_upto_price = $data['merchant_config_ship_east_upto_price'];
        $item->merchant_config_ship_east_add_weight = $data['merchant_config_ship_east_add_weight'];
        $item->merchant_config_ship_east_add_price = $data['merchant_config_ship_east_add_price'];

        $item->merchant_config_meta_description = $data['merchant_config_meta_description'];
        $item->merchant_config_meta_keyword = $data['merchant_config_meta_keyword'];
        $item->merchant_config_og_title = $data['merchant_config_og_title'];
        $item->merchant_config_og_url = $data['merchant_config_og_url'];
        $item->merchant_config_og_description = $data['merchant_config_og_description'];
        $item->merchant_config_og_sitename = $data['merchant_config_og_sitename'];

        $item->merchant_config_whatsapp = $data['merchant_config_whatsapp'];
        $item->merchant_config_telegram = $data['merchant_config_telegram'];
        $item->merchant_config_facebook = $data['merchant_config_facebook'];
        $item->merchant_config_wechat = $data['merchant_config_wechat'];
        $item->merchant_config_line = $data['merchant_config_line'];

        //$item->merchant_config_language = $data['merchant_config_language'];

        $item->merchant_config_smtp_host = $data['merchant_config_smtp_host'];
        $item->merchant_config_smtp_username = $data['merchant_config_smtp_username'];
        $item->merchant_config_smtp_password = $data['merchant_config_smtp_password'];
        $item->merchant_config_smtp_encryption = $data['merchant_config_smtp_encryption'];
        $item->merchant_config_smtp_port = ($data['merchant_config_smtp_encryption'] == 'ssl') ? '465' : '587';
        
        $item->modified_by = Auth::user()->id;
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

        //return $item->merchant_config_id;
        return $item;

    }

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

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

    public function banner(){

        return $this->hasOne('App\Modules\Admin\Models\UploadModel','upload_model_id','merchant_id')->where('upload_model','MerchantModel')->where('upload_type','5');

    }

    public function mobileno(){

        return $this->hasMany('App\Modules\Merchant\Models\MerchantMobileModel','merchant_id','merchant_id')->where('merchant_mobile_type','0');

    }

    public function emailaddress(){

        return $this->hasMany('App\Modules\Merchant\Models\MerchantEmailModel','merchant_id','merchant_id')->where('merchant_email_type','0');

    }

    /*public function mobileno(){

        return $this->hasMany('App\Modules\Merchant\Models\MerchantMobileModel','merchant_id')->where('merchant_mobile_type','0');

    }

    public function emailaddress(){

        return $this->hasMany('App\Modules\Merchant\Models\MerchantEmailModel','merchant_id')->where('merchant_email_type','0');

    }*/

    public function state(){
        return $this->belongsTo('App\Modules\Admin\Models\MasterStateModel','state_id');
    }

    public function district(){
        return $this->belongsTo('App\Modules\Admin\Models\MasterDistrictModel','district_id');
    }

}
