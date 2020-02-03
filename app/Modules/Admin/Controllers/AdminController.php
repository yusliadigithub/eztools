<?php 

namespace App\Modules\Admin\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Admin\Models\MasterStateModel;
use App\Modules\Admin\Models\MasterDistrictModel;
use Illuminate\Http\Request;
use Input;
use Exception;
use DB;
use Config;
use App\Modules\Admin\Models\BaseConfigModel;
use OwenIt\Auditing\Models\Audit;

class AdminController extends Controller {


	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except'=> ['getDistrict','getStateDistrict']]);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view("Admin::index", ['pagetitle'=>trans('Admin::base.config_dashboard')]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		dd("THIS IS ADMIN CONTROLLER");
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	/**
	 * return configuraton page
	 * @return Response
	 */
	public function configuration()	{
		return view('Admin::configuration', ['pagetitle'=>'System Configuration', 'pagedesc'=>'global system settings']);
	}

	public function updateConfiguration() {

		DB::beginTransaction();

		try {

			$data = ['config_system_longname' => Input::get('confsystemname'),
				 'config_system_shortname' => Input::get('confshortname'),
				 'config_language' => Input::get('conflanguage'),
				 'config_auto_logout' => Input::get('confenablelogout'),
				 'config_idle_minutes' => Input::get('confidletime')*60,
				 'config_maintenance' => Input::get('confmaintenance'),
				 'config_client_name' => Input::get('confclientname')
				];

			foreach ($data as $key => $value) {
				
				$base = BaseConfigModel::where('config_attribute', $key)->first();
				$base->config_value = $value;
				$base->save();
			}

			DB::commit();
			return Redirect(route('admin.config'))->with('flash_success', 'Configuration saved!');
			
		} catch (Exception $e) {
			DB::rollback();
			return Redirect(route('admin.config'))->with('flash_error', $e->getMessage());
		}
		
		
	}

	public function auditTrail()
	{
		$query = Audit::query();

		if(Input::has('search') && Input::has('keyword')) {

			// $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

			if(Input::has('search') != 'name') :
				$query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');		
			else:
				$query->whereHas('user', function($q) {
					$q->where('name', 'LIKE', '%'.Input::get('keyword').'%');
				});		
			endif;

		}

		$audits = $query->with('user')->orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));
		return view('Admin::audittrail', ['pagetitle'=>__('Admin::base.audit_trail'), 'pagedesc'=>__('Admin::base.audit_trail_desc'), 'audits'=>$audits]);
	}

	public function clearAuditTrail() {
		Audit::truncate();
		return Redirect(route('admin.auditrail'))->with('flash_success', __('Admin::base.audit_success_delete'));
	}


	public function getDistrict($id){

		$districts = MasterDistrictModel::where('state_id',$id)->pluck('district_desc','district_id');

        return json_encode($districts);

    }

    public function getStateDistrict($postcode){

    	$districts = MasterDistrictModel::where('district_postcode',trim($postcode))->first();

    	if(!empty($districts)){
    		$states = MasterStateModel::where('state_id',$districts->state_id)->first();
    	}else{
    		$states = NULL;
    	}

    	return json_encode(['districts'=>$districts, 'states'=>$states]);

    }

}
