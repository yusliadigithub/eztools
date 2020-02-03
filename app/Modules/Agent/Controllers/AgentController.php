<?php

namespace App\Modules\Agent\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Modules\Admin\Models\MasterSalutationModel;
use App\Modules\Admin\Models\MasterCountryModel;
use App\Modules\Admin\Models\UsersDetailModel;
//use App\Modules\Admin\Models\MasterDistrictModel;
use App\Modules\Admin\Models\MasterStateModel;
use App\Modules\Admin\Models\UploadModel;
use App\User;
use DB;
use Redirect;
use Session;
use Exception;
use Config;
use Input;
use Validator;
use Auth;
use Carbon\Carbon;
use App\Modules\Admin\Models\UserModel;

use Illuminate\Validation\ValidationException;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("Agent::index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $salutations = MasterSalutationModel::get();
        $states = MasterStateModel::get();

        return view('Agent::register', ['salutations'=>$salutations, 'states'=>$states]);
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
                'salutation'=>$request->input('salutation_id'),
                'name'=>$request->input('name'),
                'email'=>$request->input('email'),
                'password'=>$request->input('password'),
                'identification_no'=>$request->input('users_detail_idno'),
                'address'=>$request->input('users_detail_address1'),
                'postcode'=>$request->input('users_detail_postcode'),
                'district'=>$request->input('district_id'),
                'state'=>$request->input('state_id'),
                'contact_no'=>$request->input('users_detail_mobileno'),
            ];

            $rules = [
                'salutation'=>'required',
                'name'=>'required|max:100',
                //'email'=>'required|string|email|max:100|unique:users',
                'email'=>'required|string|email|max:100',
                //'password' => 'required|string|min:6|confirmed',
                'password' => 'required|string|min:6',
                'identification_no'=>'required|numeric',
                'address'=>'required|max:100',
                'postcode'=>'required|numeric',
                'district'=>'required',
                'state'=>'required',
                'contact_no'=>'required|numeric',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
                'email' => __('Admin::user.emailvalid'),
                //'numeric' => __('Admin::base.postcodenumericonly'),
                'contact_no.numeric' => __('Admin::user.numericonly'),
                'identification_no.numeric' => __('Admin::user.numericonly'),
                'postcode.numeric' => __('Admin::user.numericonly'),
                'password.min' => __('Admin::user.passwordmin'),
                'email.unique' => __('Admin::user.emailusernameunique'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            $validator->after(function($validator) use($request) {
                
                if ( UserModel::checkDataExist('email', $request->input('email'), '') ) {
                    $validator->errors()->add('emailexist', __('Admin::user.emailexists'));
                }

                // if ( UserModel::checkDataExist('username', $request->input('username'), '') ) {
                //     $validator->errors()->add('usernameexist', __('Admin::user.usernameexists'));
                // }

            });

            if($validator->fails()) {
                
                $errors = $validator->messages();
                $err = '';
                foreach ( $errors->all() as $error ) {
                    $err .= '<br />'.$error;
                }

                throw new exception( $err );
            }

            if($request->input('password')!=$request->input('password_confirmation')){
                throw new exception(__('Admin::user.passwordnotmatch'));
            }

            $user = new UserModel;
            $user->name = strtoupper($request->input('name'));
            $user->email = strtolower($request->input('email'));
            $user->password = bcrypt($request->input('password'));
            $user->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
            $user->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));

            if($user->save()){
                $detail = new UsersDetailModel;
                $detail->user_id = $user->id;
                $detail->users_detail_name = strtoupper($request->input('name'));
                $detail->users_detail_idno = $request->input('users_detail_idno');
                $detail->users_detail_email = strtolower($request->input('email'));
                $detail->users_detail_address1 = strtoupper($request->input('users_detail_address1'));
                $detail->users_detail_address2 = strtoupper($request->input('users_detail_address2'));
                $detail->users_detail_address3 = strtoupper($request->input('users_detail_address3'));
                $detail->users_detail_postcode = $request->input('users_detail_postcode');
                $detail->district_id = $request->input('district_id');
                $detail->state_id = $request->input('state_id');
                $detail->users_detail_mobileno = $request->input('users_detail_mobileno');
                $detail->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                $detail->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));

                if($detail->save()){
                    DB::table('user_has_roles')->insert(['role_id'=>'3','user_id'=>$user->id]);
                }
            }

            //uploadfile
            if($request->hasfile('personalphoto')) {
                $validext = ['jpg','png','jpeg'];
                $path = public_path().'/uploads/users/';
                foreach($request->file('personalphoto') as $file){
                    //$name=$file->getClientOriginalName();
                    $ext=$file->getClientOriginalExtension();

                    if(!in_array($ext,$validext)){
                        throw new exception(__('Admin::base.photoformatnotvalid'));
                    }

                    $name = $user->id.'_'.date('YmdHis').'.'.$ext;
                    $file->move($path, $name); 
                    $upload = new UploadModel;
                    $upload->upload_model = 'UserModel';
                    $upload->upload_model_id = $user->id;
                    $upload->upload_path = '/uploads/users/';
                    $upload->upload_filename = $name;
                    $upload->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                    $upload->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                    $upload->save();  
                }
            }

            DB::commit();
            //return Redirect('agent/create')->with('flash_success', 'You are successfully registered! Your registration will be approved within 24 hours.');
            return Redirect()->back()->with('flash_success', 'You are successfully registered! Your registration will be approved within 24 hours.');
            
        } catch (Exception $error) {
            DB::rollback();
            //return Redirect('agent/create')->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            return Redirect()->back()->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
