<?php

namespace App\Modules\StaffDetails\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\StaffDetails\Models\StaffDetails;
use Carbon\Carbon;
use Validator;
use Config;
use Auth;
use Exception;
use Illuminate\Support\Facades\DB;

class StaffDetailsController extends Controller
{/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(Auth::user()->hasrole('admin')) {
            $staffdetails = StaffDetails::orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));
        } else {
            $staffdetails = StaffDetails::where('user_id', Auth::user()->id)->orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));
        }

        return view("StaffDetails::index", ['pagetitle'=>'Staff Details', 'pagedesc'=>'List'])->with(['staffdetails' => $staffdetails ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("StaffDetails::create", ['pagetitle'=>'Staff Details', 'pagedesc'=>'Create']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $input = [
                // 'staff_id'=>$request->input('staff_ID'),
                'email'=>$request->input('staff_email'),
                'username'=>$request->input('username'),
                'password'=>$request->input('password'),

            ];

            $rules = [
                // 'staff_id'=>'required|staff_id|unique:users',
                'email'=>'required|email|unique:users',
                'password'=>'required|min:6',
                'username'=>'required|min:4|unique:users'
            ];

            $messages = [
                'required' => __('Admin::user.required'),

                // 'email.unique' => __('Admin::user.staff_idunique'),

                'email' => __('Admin::user.emailvalid'),
                'email.unique' => __('Admin::user.emailusernameunique'),
                'password.min' => __('Admin::user.passwordmin'),
                'username.min' => __('Admin::user.usernamemin'),
                'username.unique' => __('Admin::user.emailusernameunique'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            if($validator->fails()) {
                
                $errors = $validator->messages();
                $err = '';
                foreach ( $errors->all() as $error ) {
                    $err .= '<br />'.$error;
                }

                throw new exception( $err );
            }

            $post2 = new User;
            $post2->staff_id = $request->input('staff_ID') ;
            $post2->name = $request->input('name') ;
            $post2->username = $request->input('username') ;
            $post2->password = bcrypt($request->input('password'));
            $post2->email = $request->input('staff_email') ;
            $post2->mobileno = $request->input('mobile_no') ;
            $post2->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
            // $post2->save() ;

            if($post2->save()){
                $post = new StaffDetails;
                $post->user_id = $post2->id ;
                $post->staff_name = $request->input('name') ;
                $post->staff_username = $request->input('username') ;
                $post->staff_password = bcrypt($request->input('password'));
                $post->staff_mobileno = $request->input('mobile_no') ;

                $post->staff_id = $request->input('staff_ID') ;
                $post->gender = $request->input('staff_gender') ;
                $post->birth_date = $request->input('birth_date') ;
                $post->nationality = $request->input('Nationality') ;
                $post->passport_no = $request->input('passport_no') ;
                $post->etnicity = $request->input('etnicity') ;
                $post->religion = $request->input('religion') ;
                $post->date_joined = $request->input('date_joined') ;
                $post->end_of_probation = $request->input('endofprobation') ;
                $post->job_pos = $request->input('jobposition') ;
                $post->line_supervision = $request->input('line_supervision') ;
                $post->job_type = $request->input('jobtype') ;
                $post->job_status = $request->input('jobstatus') ;
                $post->workdays = $request->input('WorkDays') ;

                $post->salary_effective_date = $request->input('salary_effective_date') ;
                $post->permitt = $request->input('permit') ;
                $post->permit_effective_date = $request->input('permitEffectiveDate') ;
                $post->permit_from = $request->input('permitFrom') ;
                $post->permit_to = $request->input('permitTo') ;
                $post->level_star = $request->input('level_star') ;
                $post->level_chief = $request->input('level_Chief') ;
                $post->level_point = $request->input('level_point') ;

                $post->staff_email = $request->input('staff_email') ;
                $post->staff_mobileno = $request->input('mobile_no') ;
                $post->address = $request->input('address') ;
                $post->nric = $request->input('nric') ;

                $post->level = $request->input('level') ;
                $post->salary = $request->input('salary') ;
                $post->salary_next_date = $request->input('salary_nextdate') ;
                $post->payment_bank = $request->input('payment_bank') ;
                $post->payment = $request->input('payment') ;
                $post->bank_acc_no = $request->input('bank_accNo') ;
                $post->payment_method = $request->input('payment_method') ;
                $post->employer_epf_rate = $request->input('employerEpfRate') ;
                $post->epf_membership_no = $request->input('epfMembershipNo') ;
                $post->tax_reff_no = $request->input('taxReffNo') ;
                $post->child_relief = $request->input('childRelief') ;
                $post->eis_contribution = $request->input('EisContribution') ;
                $post->socso_category = $request->input('socsoCategory') ;
                $post->tax_status = $request->input('taxStatus') ;
                $post->muslim_zakat = $request->input('muslimZakatFund') ;
                $post->skill = $request->input('skill') ;
                $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                $post->save() ;


                DB::table('user_has_roles')->insert(['role_id'=>'3','user_id'=>$post2->id]);

                // DB::table('users')->insert(['staff_id'=>$post2->staff_id,'name'=>$post2->name,'password'=>$post2->password,'email'=>$post2->email,'username'=>$post2->username]);
                
                return Redirect()->route('staffdetails.page')->with(['flash_success'=> 'Data Saved']);

            }
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect()->route('staffdetails.page')->with( [ 'flash_error' => $error->getMessage(), 'modal'=>true ]);
            
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $StaffDetails = StaffDetails::find($id);
        return view("StaffDetails::show", ['pagetitle'=>'Staff Details', 'pagedesc'=>'Update'])->with( ['staffdetails' => $StaffDetails] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = StaffDetails::find($id);
        $post->staff_id = $request->input('staff_ID') ;
        $post->gender = $request->input('staff_gender') ;
        $post->birth_date = $request->input('birth_date') ;
        $post->nationality = $request->input('Nationality') ;
        $post->passport_no = $request->input('passport_no') ;
        $post->etnicity = $request->input('etnicity') ;
        $post->religion = $request->input('religion') ;
        $post->date_joined = $request->input('date_joined') ;
        $post->end_of_probation = $request->input('endofprobation') ;
        $post->job_pos = $request->input('jobposition') ;
        $post->line_supervision = $request->input('line_supervision') ;
        $post->job_type = $request->input('jobtype') ;
        $post->job_status = $request->input('jobstatus') ;
        $post->workdays = $request->input('WorkDays') ;

        $post->salary_effective_date = $request->input('salary_effective_date') ;
        $post->permitt = $request->input('permit') ;
        $post->permit_effective_date = $request->input('permitEffectiveDate') ;
        $post->permit_from = $request->input('permitFrom') ;
        $post->permit_to = $request->input('permitTo') ;
        $post->level_star = $request->input('level_star') ;
        $post->level_chief = $request->input('level_Chief') ;
        $post->level_point = $request->input('level_point') ;

        $post->staff_email = $request->input('staff_email') ;
        $post->staff_mobileno = $request->input('mobile_no') ;
        $post->address = $request->input('address') ;
        $post->nric = $request->input('nric') ;

        $post->level = $request->input('level') ;
        $post->salary = $request->input('salary') ;
        $post->salary_next_date = $request->input('salary_nextdate') ;
        $post->payment_bank = $request->input('payment_bank') ;
        $post->payment = $request->input('payment') ;
        $post->bank_acc_no = $request->input('bank_accNo') ;
        $post->payment_method = $request->input('payment_method') ;
        $post->employer_epf_rate = $request->input('employerEpfRate') ;
        $post->epf_membership_no = $request->input('epfMembershipNo') ;
        $post->tax_reff_no = $request->input('taxReffNo') ;
        $post->child_relief = $request->input('childRelief') ;
        $post->eis_contribution = $request->input('EisContribution') ;
        $post->socso_category = $request->input('socsoCategory') ;
        $post->tax_status = $request->input('taxStatus') ;
        $post->muslim_zakat = $request->input('muslimZakatFund') ;
        $post->skill = $request->input('skill') ;
        $post->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
        $post->save() ;
        return Redirect()->route('staffdetails.page')->with(['flash_success'=> 'Data Saved']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $StaffDetails = StaffDetails::find($id);
        $StaffDetails->delete();

        return Redirect()->route('staffdetails.page')->with(['flash_success'=> 'Data Deleted']);
    }

    public function checkStaffId($code){

        $staff_id = User::where('staff_id',$code)->first();

        if(empty($staff_id)){
            $message = ("")->first();
        }else{
            $message = NULL;
        }

        return array('message'=>$message);

    }

    public function checkStaffEmail($code){

        $staff_id = User::where('email',$code)->first();

        if(empty($staff_id)){
            $message = ("")->first();
        }else{
            $message = NULL;
        }

        return array('message'=>$message);

    }
}
