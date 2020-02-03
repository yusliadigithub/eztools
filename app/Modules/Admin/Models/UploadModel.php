<?php namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Config;

class UploadModel extends Model implements AuditableContract {

	use Auditable;
	use SoftDeletes;

	public $timestamps = false;
    protected $table='upload';
    protected $primaryKey = 'upload_id';

    public static function insertData($model,$modelid,$path,$filename,$type=NULL){

    	$upload = new UploadModel;
        $upload->upload_model = $model;
        $upload->upload_model_id = $modelid;
        $upload->upload_path = $path;
        $upload->upload_filename = $filename;
        $upload->upload_type = $type;
        $upload->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $upload->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $upload->save();

    }

}