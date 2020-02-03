<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Modules\Admin\Models\UserModel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'permission:home']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /*if(Auth::user()->hasanyrole('admin','superadmin')){

            $emergencycount = EmergencyModel::where('emergency_status','1')->count();
            $complaintcount = ComplaintModel::where('complaint_parent_id',0)->where('complaint_status','1')->count();
            $usercount      = UserModel::count();

            $data = ['complaintcount'=>$complaintcount, 'emergencycount'=>$emergencycount, 'usercount'=>$usercount];

            return view('home', ['pagetitle'=>'Dashboard', 'pagedesc'=>'Administrator', 'data'=>$data]);

        }*/

        return view('home', ['pagetitle'=>__('Home'), 'pagedesc'=>'User\'s dashboard']);

    }
}
