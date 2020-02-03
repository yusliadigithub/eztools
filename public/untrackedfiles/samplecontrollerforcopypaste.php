<?php

namespace App\Modules\Order\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use DB;
use Redirect;
use Session;
use Exception;
use Config;
use Input;
use Validator;
use Auth;
use Globe;
use Carbon\Carbon;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( !Auth::user()->can('order.index') ):
            abort(403);
        endif;

        //$query = OrderModel::query();

        if(Input::has('search') && Input::has('keyword')) {

            $query->where(Input::get('search'), 'LIKE', '%'.Input::get('keyword').'%');

        }

        $orders = $query->orderBy('created_at','desc')->paginate(Config::get('constants.common.paginate'));

        return view("Order::index", ['pagetitle'=>__('Order::order.order'), 'pagedesc'=>__('Admin::base.list'), 'orders'=>$orders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("Order::create", ['pagetitle'=>__('Order::order.createorder'), 'pagedesc'=>__('Order::order.order')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( !Auth::user()->can('order.store') ):
            abort(403);
        endif;

        DB::beginTransaction();

        try {

            $input = [
                'person_in_charge'=>$request->input('merchant_person_incharge'),
            ];

            $rules = [
                'person_in_charge'=>'required|max:100',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
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

            DB::commit();
            return Redirect('order')->with('flash_success', 'Successfully!');
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('order/create')->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
            
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
        //$order = OrderModel::findOrFail($id);

        return view("Order::create", ['pagetitle'=>__('Order::order.orderdetail'), 'pagedesc'=>__('Order::order.order'), 'order'=>$order]);
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
        if( !Auth::user()->can('order.delete') ):
            abort(403);
        endif;

        DB::beginTransaction();
        try {

            if(!empty($id)):

                $data = MerchantModel::findOrFail($id);

                if($data->delete()):
                    //
                endif;
                
            else:

                throw new Exception("You didn't select any data to delete");  

            endif;

            DB::commit();
            return Redirect()->back()->with('flash_success', 'Data(s) has been deleted!');
            
        } catch (Exception $e) {
            DB::rollback();
            return Redirect::back()->withInput()->with('flash_error', $e->getMessage());
        }
    }
}
