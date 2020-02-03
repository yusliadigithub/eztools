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

class MerchantSupplierModel extends Model implements AuditableContract {

    use Auditable;
	use SoftDeletes;
    use HasRoles;
    public $timestamps = false;
    protected $table='merchant_supplier';
    protected $primaryKey = 'merchant_supplier_id';

    protected $fillable =[];

    protected function insertData($data=[]) {

        $this->merchant_id = $data['merchant_id'];
        $this->merchant_supplier_email = strtolower($data['merchant_supplier_email']);
        $this->merchant_supplier_name = strtoupper($data['merchant_supplier_name']);
        $this->merchant_supplier_ssmno = strtoupper($data['merchant_supplier_ssmno']);
        $this->merchant_supplier_address1 = strtoupper($data['merchant_supplier_address1']);
        $this->merchant_supplier_address2 = strtoupper($data['merchant_supplier_address2']);
        $this->merchant_supplier_address3 = strtoupper($data['merchant_supplier_address3']);
        $this->merchant_supplier_postcode = $data['merchant_supplier_postcode'];
        $this->district_id = $data['district_id'];
        $this->state_id = $data['state_id'];
        $this->merchant_supplier_person_incharge = strtoupper($data['merchant_supplier_person_incharge']);
        $this->merchant_supplier_officeno = $data['merchant_supplier_officeno'];
        $this->merchant_supplier_faxno = $data['merchant_supplier_faxno'];
        $this->merchant_supplier_mobileno = $data['merchant_supplier_mobileno'];
        $this->created_by = Auth::user()->id;
        $this->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $this->save();

        $data2 = ['merchant_supplier_id'=>$this->merchant_supplier_id, 
                     'merchant_id'=>$this->merchant_id];

        return $data2;
            
    }

    protected function updateData($id, $data=[]) {
        
        $item = self::find($id);
        //$item->merchant_id = $data['merchant_id'];
        $item->merchant_supplier_email = strtolower($data['merchant_supplier_email']);
        $item->merchant_supplier_name = strtoupper($data['merchant_supplier_name']);
        $item->merchant_supplier_ssmno = strtoupper($data['merchant_supplier_ssmno']);
        $item->merchant_supplier_address1 = strtoupper($data['merchant_supplier_address1']);
        $item->merchant_supplier_address2 = strtoupper($data['merchant_supplier_address2']);
        $item->merchant_supplier_address3 = strtoupper($data['merchant_supplier_address3']);
        $item->merchant_supplier_postcode = $data['merchant_supplier_postcode'];
        $item->district_id = $data['district_id'];
        $item->state_id = $data['state_id'];
        $item->merchant_supplier_person_incharge = strtoupper($data['merchant_supplier_person_incharge']);
        $item->merchant_supplier_officeno = $data['merchant_supplier_officeno'];
        $item->merchant_supplier_faxno = $data['merchant_supplier_faxno'];
        $item->merchant_supplier_mobileno = $data['merchant_supplier_mobileno'];
        $item->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $item->save();

        return $item->merchant_supplier_id;

    }

    public function deleteData($id) {
    	
    	$data = self::find($id);
    	$data->delete();

    }

    public function merchant(){

        return $this->belongsTo('App\Modules\Merchant\Models\MerchantModel','merchant_id');

    }

    public function user(){

        return $this->belongsTo('App\Modules\Admin\Models\UserModel','created_by');

    }

}
