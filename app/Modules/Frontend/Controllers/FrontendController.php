<?php

namespace App\Modules\Frontend\Controllers;

use URL;
use Crypt;
use View;
use DB;
use Config;
use Input;
use Validator;
use Exception;
use Session;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Merchant\Models\MerchantModel;
use App\Modules\Product\Models\ProductStockModel;
use App\Modules\Product\Models\ProductModel;
use App\Modules\Frontend\Models\UserGuestModel;
use App\Modules\Frontend\Models\UserGuestPhoneModel;
use App\Modules\Frontend\Models\UserGuestAddressModel;
use App\Modules\Merchant\Models\MerchantPageModel;
use App\Modules\Merchant\Models\MerchantConfigurationModel;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Library\Globe;
use App\Library\IPay88;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Modules\Order\Models\CartModel;
use App\Modules\Order\Models\CartDetailModel;
use App\Modules\Admin\Models\UploadModel;
use App\Modules\Frontend\Models\UserGuestSubscriptionModel;
use App\Modules\Product\Models\ProductReviewModel;
use App\Modules\Merchant\Models\MerchantPaymentMethodModel;

use App\Mail\SendSubscribeNotification;
use App\Mail\VerifyAccount;

class FrontendController extends Controller
{
    use AuthenticatesUsers;
    // private $merchant_detail;
    // private $merchant_pages;

    public function __construct(Request $request) {
        
        $domain = $_SERVER['SERVER_NAME'];
        $merchant = MerchantModel::where('merchant_domain', $domain)->first();
        $merchant_detail = MerchantModel::where( 'merchant_uuid', $merchant->merchant_uuid )->first();

        $merchant_pages = MerchantPageModel::where('merchant_page_status', 1)
                                ->select('merchant_page_slug','merchant_page_title')
                                ->where('merchant_id', $merchant->merchant_id)->get();

        $merchant_config = MerchantConfigurationModel::where('merchant_id', $merchant->merchant_id)->first();

        View::share( 'cart', $request->cookie('cart') );
        View::share( 'merchant_pages', $merchant_pages );
        View::share( 'merchant_detail', $merchant_detail );
        View::share( 'merchant_config', $merchant_config ); // get all config, meta desc, keyword and etc
    }

    /**
     *
     * @return property guard use for login
     *
     */
    public function guard() {
        return Auth::guard('user_guests');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = ProductModel::query();

        // search function goes here
        // if(Input::has('search')) {

        // }

        $products = $query->whereHas('stock', function($q){
                        $q->where('product_stock_status', 1)->where('product_stock_quantity', '>', 0)
                        ->orWhere('product_isstockcontrol', 0);
                    })->where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id )
                    ->paginate(Config::get('constants.common.paginate'));

        return view( "Frontend::index",['pagetitle'=>'frontend commerce',
                                        'products'=> $products
                                       ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug) {

        // get product's detail
         $product = ProductModel::where('product_slug', $slug)->where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id )
                    ->first(); 

        $reviews = ProductReviewModel::where('product_id', $product->product_id)->where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id )->orderBy('created_at', 'desc')->paginate(10);

        // get product related
        $query = ProductModel::query();
        $related_products = $query->whereHas('stock', function($q){
                        $q->where('product_stock_status', 1)->where('product_stock_quantity', '>', 0);
                    })->where('product_slug', '!=', $slug)
                            ->where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id )
                            ->where('product_type_id', $product->product_type_id)
                            ->orderBy('updated_at','desc')->limit(4)
                            ->get(); 

        $productWithMinPrice = $product->activeStock(1);

        return view("Frontend::details",['pagetitle'=> $product->product_name, 
                                         'product'=>$product,
                                         'productWithMinPrice'=> $productWithMinPrice,
                                         'related_products'=>$related_products,
                                         'reviews' => $reviews,
                                       ]);
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

    public function page($slug='')
    {
        $page = MerchantPageModel::where('merchant_page_slug', $slug)->first();
        return view( "Frontend::page",['pagetitle'=> $page->merchant_page_title, 
                                        // 'merchant_detail'=>$this->merchant_detail, 
                                        // 'merchant_pages'=>$this->merchant_pages,
                                        'page'=>$page] );
    }

    // public user's login page
    public function login() {

        if( Auth::guard('users_guest')->check() ) {
            return Redirect()->route('frontend');
        } else {
            return view( "Frontend::login",['pagetitle'=>'frontend commerce'] );
        }
    }

    public function processlogin(Request $request) {
        
        try {
            
            $input = [
                'email'=>$request->input('email'),
                'password'=>$request->input('password'),
            ];

            $rules = [
                'email'=>'required|email',
                'password'=>'required',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
                'email' => __('Admin::user.emailvalid'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            $validator->after(function($validator) use($request) {

                $credentials = [
                    'email' => $request->input('email'),
                    'password' => $request->input('password')
                ];

                if(!Auth::guard('users_guest')->attempt($credentials)) {

                    // return Redirect()->route('frontend');
                    $validator->errors()->add('num_opt', 'Invalid username or password!');
                }
                    
                // } else {
                //     $validator->errors()->add('num_opt', 'Invalid username or password!');
                // }

            });

            if($validator->fails()) {
                
                $errors = $validator->messages();
                $err = '';
                foreach ( $errors->all() as $error ) {
                    $err .= $error.'<br />';
                }

                throw new exception( $err );
            }

            // return Redirect()->route('frontend');
            // return Redirect()->intended();

            if(Input::has('redirectTo')):
                return Redirect()->route(Input::get('redirectTo'));
            else:
                return Redirect()->route('frontend');
            endif;

        } catch (Exception $error) {
            return Redirect('frontend/login')->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
        }

    }


    // public user's account page
    public function myaccount() {

        // prevent my account page being open if user not authenticate
        if(!Auth::guard('users_guest')->check()) {
            Session::flash('redirect_url', 'frontend.account');
            return Redirect('frontend/login');
        }

        $guestinfo = UserGuestModel::where('guest_id', Auth::guard('users_guest')->user()->guest_id )->first();

        return view( "Frontend::account",['pagetitle'=>'frontend commerce', 
                                          // 'merchant_detail'=>$this->merchant_detail,
                                          // 'merchant_pages'=>$this->merchant_pages,
                                          'guestinfo'=>$guestinfo] );
    }

    // process update account
    public function updateMyaccount(Request $request) {
        
        DB::beginTransaction();
        try {

            $input = [
                'fullname'=>$request->input('fullname'),
                'gender'=>$request->input('gender'),
                'twitter'=>$request->input('twitter'),
                'google'=>$request->input('google'),
                'facebook'=>$request->input('facebook'),
            ];

            $rules = [
                'fullname'=>'required|max:200',
                'gender'=>'max:1',
                'twitter'=>'max:50',
                'google'=>'max:50',
                'facebook'=>'max:50',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            if($validator->fails()) {
                
                $errors = $validator->messages();
                $err = '';
                foreach ( $errors->all() as $error ) {
                    $err .= '<i class="fas fa-times"></i> '.$error.'<br />';
                }

                throw new exception( $err );
            }

            $guest = UserGuestModel::find( Auth::guard('users_guest')->user()->guest_id );
            $guest->guest_fullname = strtoupper($request->input('fullname'));
            $guest->guest_google = $request->input('google');
            $guest->guest_twitter = $request->input('twitter');
            $guest->guest_facebook = $request->input('facebook');
            $guest->guest_dob = date('Y-m-d', strtotime( $request->input('dob') ));
            $guest->guest_gender = $request->input('gender');
            $guest->save();

            DB::commit();
            return Redirect('frontend/account')->with( [ 'flash_success' => __('Frontend::frontend.update_msg') ]);
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('frontend/account')->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
        }

    }

    // insert public user's phone
    public function insertPhone(Request $request) {
        
        DB::beginTransaction();
        try {

            $input = [
                'phonetype'=>$request->input('phonetype'),
                'phone'=>$request->input('phone'),
            ];

            $rules = [
                'phonetype'=>'required',
                'phone'=>'max:30',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            if($validator->fails()) {
                
                $errors = $validator->messages();
                $err = '';
                foreach ( $errors->all() as $error ) {
                    $err .= '<i class="fas fa-times"></i> '.$error.'<br />';
                }

                throw new exception( $err );
            }

            $guestphone = new UserGuestPhoneModel;
            $guestphone->guest_id = Auth::guard('users_guest')->user()->guest_id;
            $guestphone->guest_phone_type = $request->input('phonetype');
            $guestphone->guest_phone_value = $request->input('phone');
            $guestphone->created_at = Carbon::now(Config::get('constants.common.systemtimezone')); 
            $guestphone->save();

            DB::commit();
            return Redirect('frontend/account')->with( [ 'flash_success' => __('Frontend::frontend.insert_phone_msg') ]);
            
        } catch (Exception $error) {
            DB::rollback();
            return Redirect('frontend/account')->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
        }
    }

    // delete public user's phone
    public function deletePhone($phoneid) {
        
        $phone = UserGuestPhoneModel::find($phoneid);
        $phone->delete();
        return Redirect()->route('frontend.account');
    }

    // manage account page
    public function manageAddress() {
        
        $guestinfo = UserGuestModel::where('guest_id', Auth::guard('users_guest')->user()->guest_id )->first();

        return view( "Frontend::address",['pagetitle'=>'frontend commerce', 
                                          // 'merchant_detail'=>$this->merchant_detail,                                          
                                          // 'merchant_pages'=>$this->merchant_pages,
                                          'guestinfo'=>$guestinfo] );
    }

    public function insertMyAddress(Request $request) {
        
        DB::beginTransaction();
        try {
            
            $input = [
                'addresstype'=>$request->input('addresstype'),
                'name'=>$request->input('name'),
                'phone'=>$request->input('phone'),
                'address'=>$request->input('address'),
                'address2'=>$request->input('address2'),
                'address3'=>$request->input('address3'),
                'postcode'=>$request->input('postcode'),
                'district_id'=>$request->input('district_id'),
                'state_id'=>$request->input('state_id')
            ];

            $rules = [
                'addresstype'=>'required',
                'name'=>'required',
                'phone'=>'required|max:30',
                'address'=>'required|max:120',
                'address2'=>'max:120',
                'address3'=>'max:120',
                'postcode'=>'required',
                'district_id'=>'required',
                'state_id'=>'required'
            ];

            $messages = [
                'required' => __('Admin::user.required'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            $validator->after(function($validator) use($request) {
                
                // custom validation - check guest address already limit or not based on address type
                if ( self::checkGuestAddress( Auth::guard('users_guest')->user()->guest_id, $request->input('addresstype') ) == Config::get('constants.common.address_limit') ) {
                    $validator->errors()->add('num_opt', __( 'Frontend::frontend.address_limit', ['Addrtype'=>$request->input('addresstype')] ) );
                }
            });

            if($validator->fails()) {
                
                $errors = $validator->messages();
                $err = '';
                foreach ( $errors->all() as $error ) {
                    $err .= '<i class="fas fa-times"></i> '.$error.'<br />';
                }

                throw new exception( $err );
            }

            $address = new UserGuestAddressModel;
            $address->guest_address_type = $request->input('addresstype');
            $address->guest_address_name = $request->input('name');
            $address->guest_address_phone = $request->input('phone');
            $address->guest_id = Auth::guard('users_guest')->user()->guest_id;
            $address->guest_address_one = $request->input('address');
            $address->guest_address_two = $request->input('address2');
            $address->guest_addres_three = $request->input('address3');
            $address->guest_address_postcode = $request->input('postcode');
            $address->district_id = $request->input('district_id');
            $address->state_id = $request->input('state_id');

            if ( self::checkGuestAddress( Auth::guard('users_guest')->user()->guest_id, $request->input('addresstype') ) == 0 ) {
                $address->guest_address_default = 1; // set default for first record
            }

            $address->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));

            $address->save();

            DB::commit();
            return Redirect('frontend/manage/address')->with( [ 'flash_success' => __('Frontend::frontend.insert_address_msg') ]);


        } catch (Exception $error) {
            DB::rollback();
            return Redirect('frontend/manage/address')->withInput()->with( [ 'flash_error' => $error->getMessage() ]);
        }
    }

    public function setDefaultAddress(Request $request) {
        
        try {
            
            // decrypt the passed address_id
            $addressid = decrypt($request->aid);

            //set all to non-default
            UserGuestAddressModel::where('guest_id', Auth::guard('users_guest')->user()->guest_id)
                                   ->where('guest_address_type', $request->atype)
                                   ->update(['guest_address_default'=> 0]);

            // then set default address based on address_type
            UserGuestAddressModel::where('guest_id', Auth::guard('users_guest')->user()->guest_id)
                                   ->where('guest_address_type', $request->atype)
                                   ->where('guest_address_id', $addressid)
                                   ->update(['guest_address_default'=> 1]);

            return response()->json(['status' => 'OK']);

        } catch (DecryptException $e) {
            return response()->json(['errors' => $e->getMessage(), 'status' => 'INVALID']);
        }

        

    }

    public function deleteAddress($addressid) {
        $addr = UserGuestAddressModel::find($addressid);
        $addr->delete();
        return Redirect()->route('frontend.manage.address');
    }

    // check address public user exist or not based on type
    private function checkGuestAddress($guestid, $addrtype) {
        $numberofaddress = UserGuestAddressModel::where('guest_id', $guestid)
                            ->where('guest_address_type', $addrtype)->get()
                            ->count();
        // if($numberofaddress == 5):
        //     return true;
        // else:
        //     return false;
        // endif;
        return $numberofaddress;
    }

    // check user already register or not
    private function checkGuestExist($email, $merchantid) {

        $numberofguest = UserGuestModel::where('email', $email)
                        ->where('merchant_id', $merchantid)->get()
                        ->count();

        if($numberofguest > 0):
            return true;
        else:
            return false;
        endif;

    }

    // guest registration process
    public function register(Request $request) {
        
        DB::beginTransaction();
        try {
            
            $input = [
                'fullname'=>$request->input('fullname'),
                'email'=>$request->input('email'),
                'password'=>$request->input('password'),
            ];

            $rules = [
                'fullname'=>'required|max:200',
                'email'=>'required|email',
                'password'=>'required|min:6',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
                'email' => __('Admin::user.emailvalid'),
                'password.min' => __('Admin::user.passwordmin'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            $validator->after(function($validator) use($request) {
                
                // custom validation - check if guest already exist or not based on email n merchant id
                if ( self::checkGuestExist($request->input('email'), session($_SERVER['SERVER_NAME'])->merchant_id) ) {
                    $validator->errors()->add('num_opt', 'This account already exist in our system!');
                }
            });

            if($validator->fails()) {
                
                $errors = $validator->messages();
                $err = '';
                foreach ( $errors->all() as $error ) {
                    $err .= '<i class="fas fa-times"></i> '.$error.'<br />';
                }

                throw new exception( $err );
            }

            $guest = new UserGuestModel;
            $guest->guest_fullname = strtoupper( $request->input('fullname') );
            $guest->email = $request->input('email');
            $guest->password = Hash::make( $request->input('password') );
            $guest->merchant_id = session($_SERVER['SERVER_NAME'])->merchant_id ;
            $guest->created_at = Carbon::now(Config::get('constants.common.systemtimezone'));
            $guest->save();

            // automatic subscribe nesletter
            self::insertSubscriptionNewsletter( $request->input('email'), $guest->guest_id, 1 );

            // send email for account activation
            \Mail::to( $request->input('email') )->send(new VerifyAccount( encrypt( Request('email') ), encrypt($guest->merchant_id) ) );

            DB::commit();
            return response()->json(['msg' => 'Registration complete! <br /> <small>An email has been sent to you.</small>', 'status' => 'SUCCESS']);

            // todo - event & listerner send email to registered user


        } catch (Exception $error) {
            DB::rollback();
            return response()->json(['errors' => $error->getMessage(), 'status' => 'FAILED']);
        }
    }

    public function resetpassword(Request $request) {

        DB::beginTransaction();
        try {
            
            $input = [
                'email'=>$request->input('email'),
            ];

            $rules = [
                'email'=>'required|email',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
                'email' => __('Admin::user.emailvalid'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            $validator->after(function($validator) use($request) {
                
                // custom validation - check if email not exist based on email n merchant id
                if (! self::checkGuestExist($request->input('email'), session($_SERVER['SERVER_NAME'])->merchant_id) ) {
                    $validator->errors()->add('num_opt', 'Email does not exist in a system!');
                }
            });

            if($validator->fails()) {
                
                $errors = $validator->messages();
                $err = '';
                foreach ( $errors->all() as $error ) {
                    $err .= '<i class="fas fa-times"></i> '.$error.'<br />';
                }

                throw new exception( $err );
            }

            $random_password = Globe::generateRandomString(7); // generate the random string from globe
            $guest = UserGuestModel::where('email', $request->input('email'))->first();
            $guest->password = Hash::make($random_password);
            $guest->save();

            DB::commit();
            return response()->json(['msg' => 'Password has been reset! <br /> <small>An email has been sent to you.</small>', 'status' => 'SUCCESS']);

            // todo - event & listerner send email to registered user
            // $data = array('name'=>"E-commerce Admin", "body" => $random_password);
   
            // Mail::send('emails.reset_password', $data, function($message) {
            //     $message->to($request->input('email'), 'Artisans Web')
            //             ->subject('Reset password from '.$this->merchant_detail->merchant_domain.' website.');
            //     $message->from('starksajid@gmail.com','Sajid Sayyad');
            // });


        } catch (Exception $error) {
            DB::rollback();
            return response()->json(['errors' => $error->getMessage(), 'status' => 'FAILED']);
        }
    }

    // set language
    public function setLanguage($locale) {
        
        // if(Auth::guard('users_guest')->check()){

        //     $user = UserGuestModel::find( Auth::guard('users_guest')->user()->guest_id );
        //     $user->update(['guest_locale'=>$locale]);
        // } 

        // Session::put('frontend_language',$locale);
        app('App\Modules\Admin\Controllers\LanguageController')->setLocale($locale);
        // App()->setLocale( $locale );

        return Redirect()->back();
    }


    /**
     * get detail of stock request thru ajax
     **/
    public function getStockDetail(Request $request) {
        
        try {

            $sale_vs_market_price = '';
            $stock_availability = __('Frontend::frontend.out_stock');
            $add_to_cart_btn = '';
            // $stockid = decrypt($request->stock_id);
            // $stock = ProductStockModel::where('product_stock_id', $stockid)->first();
            $stock = ProductStockModel::findOrFail( decrypt($request->stock_id) );

            // sale price vs market price
            if($stock->product_stock_sale_price < $stock->product_stock_market_price) {
                $sale_vs_market_price = ' <del class="product-old-price">'.Globe::moneyFormat($stock->product_stock_market_price).'</del>';
            }

            if($stock->product->product_isstockcontrol == 1) {
                
                // stock availability
                if($stock->product_stock_quantity > 0) {
                    $stock_availability = __('Frontend::frontend.in_stock');
                    $add_to_cart_btn = '<button data-id="'.$request->stock_id.'" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> '.__('Frontend::frontend.add_to_cart').'</button>';
                }
                $price = '<h3 class="product-price">'.Globe::moneyFormat($stock->product_stock_sale_price).$sale_vs_market_price.'</h3><span class="product-available">'.$stock_availability.'</span>';

            } else {
                $add_to_cart_btn = '<button data-id="'.$request->stock_id.'" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> '.__('Frontend::frontend.add_to_cart').'</button>';
                $price = '<h3 class="product-price">'.Globe::moneyFormat($stock->product_stock_sale_price).$sale_vs_market_price.'</h3>';
            }

             // return response()->json(['price' => $price, 'status' => 'OK']);
             return response()->json(['price' => $price, 'add_to_cart_btn'=>$add_to_cart_btn, 'status' => 'OK']);

        } catch (DecryptException $e) {
            return response()->json(['errors' => $e->getMessage(), 'status' => 'INVALID']);
        }

    }

    // show cart
    public function cart() {
        
        $weight = 0.00;
        $shipping_cost = 0.00;
        $stateid = 1; // default to semenanjung if user not login or user not fill up the shipping address
        $grand_total = 0.00;
        $guest_address = '';
        $totalgst = 0.00;
        $cartItems = \Cart::session('cart')->getContent();

        foreach ($cartItems as $item) {

            $cartitem = Globe::itemcost($item->id, $item->quantity);
            $weight += $item->attributes->weight * $item->quantity; // calculate cart item weight x item quantity
            $totalgst += $cartitem['gstcost']; // calculate total gst
        }

        // get the shipping area
        if(Auth::guard('users_guest')->check()) {

            $guest = UserGuestModel::where('guest_id', Auth::guard('users_guest')->user()->guest_id )->first();

            if( $guest->shippingAddress ):
                $stateid = $guest->shippingAddress->state_id;
                $guest_address = $guest->shippingAddress;
            endif;            
        }

        // total shipping cost
        if($cartItems->count() > 0) {
            $shipping_cost = Globe::shippingcost($weight, $stateid, session($_SERVER['SERVER_NAME'])->merchant_id );
        }

        $grand_total = \Cart::session('cart')->getTotal() + $shipping_cost + $totalgst;

        $directPayment = MerchantPaymentMethodModel::where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id )
                         ->where('merchant_payment_method_status', 1)->where('payment_method_id', 1)->first();

        $onlinePayment = MerchantPaymentMethodModel::where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id )
                         ->where('merchant_payment_method_status', 1)->where('payment_method_id','!=', 1)->first();        
        
        return view( "Frontend::cart",['pagetitle'=> __('Frontend::frontend.shopping_cart'),
                                        'cartItems' => \Cart::session('cart')->getContent(),
                                        'shipping_cost' => $shipping_cost,
                                        'grand_total' => $grand_total,
                                        'ship_address' => $guest_address,
                                        'total_gst' => $totalgst,
                                        'directPayment' => $directPayment,
                                        'onlinePayment' => $onlinePayment
                                       ]);
    }

    public function addToCart(Request $request) {
        
        try {            

            // $stockid = decrypt($request->spid);
            // $stock = ProductStockModel::where('product_stock_id', $stockid)->first();
            $stock = ProductStockModel::findOrFail( decrypt($request->spid) );

            \Cart::session('cart')->add($stock->product_stock_id, 
                                         $stock->product_stock_name,
                                         $stock->product_stock_sale_price, 
                                         $request->qty,
                                         ['weight'=>$stock->product_stock_weight]);

            $cartCollection = \Cart::session('cart')->getContent();

            return response()->json(['status' => 'OK', 'cartitems'=>$cartCollection->count() ]);

        } catch (DecryptException $e) {
            return response()->json(['errors' => $e->getMessage(), 'status' => 'INVALID']);
        }       

    }

    public function plusMinusItemCart(Request $request) {
        
        try {

            $weight = 0.00;
            $shipping_cost = 0.00;
            $stateid = 1; // default to semenanjung if user not login or user not fill up the shipping address
            $grand_total = 0.00;
            $guest_address = '';
            $totalgst = 0.00;
            
            
            $stock = ProductStockModel::findOrFail( decrypt($request->spid) );

            \Cart::session('cart')->update( decrypt($request->spid), array(
              'quantity' => array(
                  'relative' => false,
                  'value' => $request->qty
              ),
            ));


            $cartItems = \Cart::session('cart')->getContent();
            foreach ($cartItems as $item) {

                $cartitem = Globe::itemcost($item->id, $item->quantity);
                $weight += $item->attributes->weight * $item->quantity; // calculate cart item weight x item quantity
                $totalgst += $cartitem['gstcost']; // calculate total gst
            }

            // get the shipping area
            if(Auth::guard('users_guest')->check()) {

                $guest = UserGuestModel::where('guest_id', Auth::guard('users_guest')->user()->guest_id )->first();

                if( $guest->shippingAddress ):
                    $stateid = $guest->shippingAddress->state_id;
                    $guest_address = $guest->shippingAddress;
                endif;            
            }

            // total shipping cost
            if($cartItems->count() > 0) {
                $shipping_cost = Globe::shippingcost($weight, $stateid, session($_SERVER['SERVER_NAME'])->merchant_id );
            }

            $grand_total = \Cart::session('cart')->getTotal() + $shipping_cost + $totalgst;

            $total_items = \Cart::session('cart')->getTotalQuantity();

            $subtotal = \Cart::session('cart')->getSubTotal();

            return response()->json(['status' => 'OK', 'subtotal'=>Globe::moneyFormat($subtotal), 'total_items'=>$total_items, 'total_gst'=>Globe::moneyFormat($totalgst), 'shipping_cost'=>Globe::moneyFormat($shipping_cost), 'grand_total'=>Globe::moneyFormat($grand_total), ]);

        } catch (DecryptException $e) {
             return response()->json(['errors' => $e->getMessage(), 'status' => 'INVALID']);
        }
    }

    public function removeItemCart(Request $request) {

        try {
            $stockid = decrypt($request->spid);
            \Cart::session('cart')->remove($stockid);

            // calculate cart item weight x item quantity
            $weight = 0;
            $shipping_cost = 0.00;
            $stateid = 1; // default to semenanjung if user not login or user not fill up the shipping address

            $cartItems = \Cart::session('cart')->getContent();
            foreach ($cartItems as $item) {
                $weight += $item->attributes->weight * $item->quantity;
            }

            // get the shipping area
            if(!Auth::guard('users_guest')->check()) {
                $stateid = 1; // default to semenanjung if user not login
            } else {
                $guest = UserGuestModel::where('guest_id', Auth::guard('users_guest')->user()->guest_id )->first();
                $stateid = $guest->shippingAddress->state_id;
            }

            // total shipping cost
            if($cartItems->count() > 0) {
                $shipping_cost = Globe::shippingcost($weight, $stateid, session($_SERVER['SERVER_NAME'])->merchant_id );
            }

            return response()->json(['status' => 'OK', 'total_weight'=>$weight, 'shipping_cost'=>$shipping_cost ]);

        } catch (DecryptException $e) {
            return response()->json(['errors' => $e->getMessage(), 'status' => 'INVALID']);
        }
    }

    public function checkout() {

        if(!Auth::guard('users_guest')->check()) {
            Session::flash('redirect_url', 'frontend.cart');
            return Redirect('frontend/login');
        }
        
        DB::beginTransaction();

        try {

            // $stateid = 1;
            $weight = 0;
            $totalgst = 0.00;
            $shipping_cost = 0.00;
            $totalactualamount = 0.00;
            $totalfinalamount = 0.00;

            // if cart is empty, return error message - maybe session dah mati. so prevent awal2
            if( \Cart::session('cart')->getTotalQuantity() < 0) {
                throw new Exception( __('Frontend::frontend.no_item_in_cart') );                
            }

            // if shipping address is empty, return error message
            if( empty(Request('shippingAddress')) ) {
                throw new Exception( __('Frontend::frontend.no_shipping_address') ); 
            }

            // if empty payment method, return error message
            if( empty(Request('paymentOption')) ) {
                throw new Exception( __('Frontend::frontend.please_select_payment_method') );
            }

            $guest = UserGuestModel::where('guest_id', Auth::guard('users_guest')->user()->guest_id )->first();

            // $addressid = decrypt(Request('shippingAddress'));            
            $shippingAddress = $guest->addresses->where('guest_address_id', Request('shippingAddress'))->first();

            // if empty billing address, set billing same as shipping
            if( empty(Request('billingAddress')) ) {
                $billingAddress = $shippingAddress;
            } else {
                $billingAddress = $guest->addresses->where('guest_address_id', Request('billingAddress'))->first();
            }

            // create parent data (cart)
            $meta = [ 'shipping' => ['name'=>$shippingAddress->guest_address_name,
                                      'phone'=>$shippingAddress->guest_address_phone,
                                      'address' => $shippingAddress->guest_address_one.'<br />'.$shippingAddress->guest_address_two.'<br />'.$shippingAddress->guest_address_three.''.$shippingAddress->guest_address_postcode.' '.$shippingAddress->district->district_desc.'<br />'.$shippingAddress->state->state_desc
                                       ],

                      'billing'=>['name'=>$billingAddress->guest_address_name,
                                 'phone'=>$billingAddress->guest_address_phone,
                                 'address' => $billingAddress->guest_address_one.'<br />'.$billingAddress->guest_address_two.'<br />'.$billingAddress->guest_address_three.''.$billingAddress->guest_address_postcode.' '.$billingAddress->district->district_desc.'<br />'.$billingAddress->state->state_desc
                                  ],

                      'payment_type'=> Request('paymentOption')
                    ];

            $metadata = serialize($meta);

            $cart = new CartModel;
            $cart->merchant_id = session($_SERVER['SERVER_NAME'])->merchant_id;
            $cart->guest_id = Auth::guard('users_guest')->user()->guest_id;
            $cart->cart_metadata = $metadata;
            $cart->cart_orderno = Globe::running_no( session($_SERVER['SERVER_NAME'])->merchant_id );
            $cart->cart_isinvoice = 1;
            $cart->cart_controller = 'FrontendController';
            $cart->created_by = Auth::guard('users_guest')->user()->guest_id;
            $cart->save();

            $items = \Cart::session('cart')->getContent();
            foreach ($items as $item) {

                $stock = ProductStockModel::where('product_stock_id', $item->id)->where('product_stock_status', 1)->first();

                // if order quantity > stock left, break the transaction
                if($item->quantity > $stock->product_stock_quantity) {
                    throw new Exception( $item->name.' '.__('Frontend::frontend.has_qty_left', ['qty'=>$stock->product_stock_quantity]) );                   
                }

                $cartitem = Globe::itemcost($item->id, $item->quantity);
                $weight += $item->attributes->weight * $item->quantity; // calculate cart item weight x item quantity
                $totalgst += $cartitem['gstcost']; // calculate total gst
                $totalactualamount += $item->getPriceSum(); 
                $totalfinalamount += $cartitem['finalamount'];

                // masukkan dalam cart dan cart detail
                $cartdetail = new CartDetailModel;
                $cartdetail->cart_id = $cart->cart_id;
                $cartdetail->product_stock_id = $item->id;
                $cartdetail->cart_detail_quantity = $item->quantity;
                $cartdetail->cart_detail_price = $item->price;
                $cartdetail->cart_detail_weight = $item->attributes->weight;
                $cartdetail->cart_detail_actual_amount = $item->getPriceSum();
                $cartdetail->cart_detail_gst_percent = $cartitem['gst'];
                $cartdetail->cart_detail_gst_amount = $cartitem['gstcost'];
                $cartdetail->cart_detail_final_amount = $cartitem['finalamount'];
                $cartdetail->save();

                // masukkan dalam stock ledger dan stock movement
                Globe::stockmovementfromcart($cartdetail->cart_detail_id);
                
            }

            $shipping_cost = Globe::shippingcost($weight, $shippingAddress->state_id, session($_SERVER['SERVER_NAME'])->merchant_id );

            $cart_update = CartModel::find($cart->cart_id);
            $cart_update->cart_total_weight = $weight;
            $cart_update->cart_shipping_amount = $shipping_cost;
            $cart_update->cart_actual_amount = $totalactualamount;
            $cart_update->cart_gst_amount = $totalgst;
            $cart_update->cart_final_amount = $totalfinalamount + $shipping_cost;
            $cart_update->save();

            // if direct go to page order list, else go to page payment gateway
            $redirect_page = (Request('paymentOption') == 'direct') ? route('frontend.order') : route('frontend.online.payment', ['request', encrypt($cart->cart_id)]);

            DB::commit();
            \Cart::session('cart')->clear(); // clear the cart
            return response()->json(['status' => 'OK', 'redirect'=>$redirect_page]);
            
        } catch (Exception $e) {
            
            DB::rollback();
            return response()->json(['errors' => $e->getMessage(), 'status' => 'INVALID']);
        }
    }


    public function order() {

        if(!Auth::guard('users_guest')->check()) {
            Session::flash('redirect_url', 'frontend.order');
            return Redirect('frontend/login');
        }
        
        $orders = CartModel::where( 'guest_id', Auth::guard('users_guest')->user()->guest_id )->where('cart_isinvoice', 1)
                    ->where('cart_controller', 'FrontendController')->orderBy('created_at', 'DESC')
                    ->paginate(Config::get('constants.common.paginate'));

        return view('Frontend::order',['pagetitle'=> __('Frontend::frontend.order_list'),
                                       'orders'=>$orders]);

    }

    public function orderDetail($orderid) {

        if(!Auth::guard('users_guest')->check()) {
            Session::flash('redirect_url', 'frontend.order');
            return Redirect('frontend/login');
        }
        
        $orderid = decrypt($orderid);
        $order = CartModel::where('cart_id', $orderid)->where('guest_id', Auth::guard('users_guest')->user()->guest_id)
                ->where('cart_controller', 'FrontendController')->first();

        $directPayment = MerchantPaymentMethodModel::where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id )
                         ->where('merchant_payment_method_status', 1)->where('payment_method_id', 1)->first();

        $onlinePayment = MerchantPaymentMethodModel::where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id )
                         ->where('merchant_payment_method_status', 1)->where('payment_method_id','!=', 1)->first();  

        return view('Frontend::order_detail',['pagetitle'=> __('Frontend::frontend.order_no').' '.$order->cart_orderno,
                                       'order'=>$order,
                                       'onlinePayment' => $onlinePayment,
                                       'directPayment' => $directPayment
                                    ]);

    }

    public function uploadPaymentSlip(Request $request) {
        
        DB::beginTransaction();
        try {         

            $cartid = decrypt($request->cartid);
            $cart = CartModel::findOrFail($cartid);

            $validext = ['jpg','png','jpeg','pdf'];
            $path = public_path().'/uploads/orderpaymentslip/';
            $modelname = 'CartModel';
            $uploadpath = '/uploads/orderpaymentslip/';

            if($request->hasfile('paymentslip')) {

                if( count($cart->paymentslips) > Config::get('constants.common.paymentslip_limit') ) {
                    throw new Exception(__('Frontend::frontend.payslip_limit'));                    
                }

                $file = $request->file('paymentslip');
                $ext=$file->getClientOriginalExtension();

                if(!in_array($ext,$validext)){
                    throw new exception(__('Admin::base.photoformatnotvalid'));
                }

                $name = $cartid.'_'.date('YmdHis').'_slip.'.$ext;
                $file->move($path, $name);

                UploadModel::insertData($modelname,$cartid,$uploadpath,$name,'');

            }

            DB::commit();
            return response()->json(['status' => 'OK']);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['errors' => $e->getMessage(), 'status' => 'INVALID']);
        }
    }

    public function deletePaymentSlip(Request $request) {
        
        DB::beginTransaction();
        try {
            
            $upload_id = decrypt($request->uid);            
            $upload = UploadModel::find($upload_id);
            
            unlink(public_path($upload->upload_path.$upload->upload_filename));
            UploadModel::where('upload_id',$upload_id)->delete();

            DB::commit();
            return response()->json(['status' => 'OK']);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['errors' => $e->getMessage(), 'status' => 'INVALID']);
        }


    }


    public function subscribeNewsletter() {
        
        DB::beginTransaction();
        try {

            $input = [
                'email'=>Request('email'),
            ];

            $rules = [
                'email'=>'required|email',
            ];

            $messages = [
                'required' => __('Admin::user.required'),
                'email' => __('Admin::user.emailvalid'),
            ];

            $validator = Validator::make($input, $rules, $messages);

            if($validator->fails()) {
                
                $errors = $validator->messages();
                $err = '';
                foreach ( $errors->all() as $error ) {
                    // $err .= '<i class="fas fa-times"></i> '.$error.'<br />';
                    $err .= $error;
                }

                throw new exception( $err );
            }
            
            $subscribe = UserGuestSubscriptionModel::where('guest_subscription_email', Request('email'))
                        ->where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id )
                        ->first();

            if( empty($subscribe) ) {
                self::insertSubscriptionNewsletter( Request('email') ); // do insert if email not subscribe
            } elseif($subscribe->guest_subscription_status == 0) {
                // send the email for confirmation
                \Mail::to( Request('email') )->send(new SendSubscribeNotification(encrypt( Request('email') )) );
            }

            DB::commit();
            return response()->json(['status' => 'OK', 'message'=>__('Frontend::frontend.success_subscribe')]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['errors' => $e->getMessage(), 'status' => 'INVALID']);
        }

    }

    private function insertSubscriptionNewsletter($email, $guestid=0, $status=0) {

        $subscription = new UserGuestSubscriptionModel;
        $subscription->merchant_id = session($_SERVER['SERVER_NAME'])->merchant_id;
        $subscription->guest_id = $guestid;
        $subscription->guest_subscription_email = $email;
        $subscription->guest_subscription_status = $status;
        $subscription->save();

        if($status==0) {
            // send the email for confirmation
            \Mail::to($email)->send(new SendSubscribeNotification(encrypt($email)) );
        }
    }

    public function confirmSubscription($encryptedEmail, $type='subscribe') {
        
        DB::beginTransaction();
        try {

            if(empty($encryptedEmail)) {
                abort(404);
            }

            $email = decrypt($encryptedEmail);
            $subscribe = UserGuestSubscriptionModel::where('guest_subscription_email', $email)->first();

            if( empty($subscribe) ) {
                throw new Exception("Error Processing Request");                
            }

            switch ($type) {
                case 'unsubscribe':
                $subscribe->guest_subscription_status = 0;
                $subscribe->save();
                $subscribe->delete();
                break;
                
                default:
                $subscribe->guest_subscription_status = 1;
                $subscribe->save();
                break;
            }

            DB::commit();
            return Redirect()->route('frontend');

        } catch (Exception $e) {
            DB::rollback();
        }
        

    }


    // process guest reviews
    public function reviewProduct($id) {
        
        DB::beginTransaction();
        try {

            if(empty($id)) {
                throw new Exception("Invalid operation!");                
            }

            $product_id = decrypt($id);            
            $review = new ProductReviewModel;
            $review->merchant_id = session($_SERVER['SERVER_NAME'])->merchant_id;
            $review->product_id = $product_id;
            $review->guest_id = Auth::guard('users_guest')->user()->guest_id;
            $review->product_review_remarks = Request('review_remark');
            $review->product_review_rating = Request('review_rating');
            $review->save();

            DB::commit();
            return Redirect::back()->withInput()->with(['flash_success'=>'Thank you. Your review has been submitted!']);

        } catch (Exception $e) {
            DB::rollback();
            return Redirect::back()->withInput()->with(['flash_error'=>$e->getMessage()]);
        }
    }

    /**
        online payment response / return page
     **/
    public function onlinePayment($type, $cart_id) {

        try {            
        

            $cartid = decrypt($cart_id);
            $html = '';

            ####### get the merchant's payment gateway first #######
            $merchant_payment_gateway = MerchantPaymentMethodModel::where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id)
                                        ->where('payment_method_id','!=', 1)->where('merchant_payment_method_status', 1)
                                        ->first();

            if(empty($merchant_payment_gateway)) {
                throw new Exception("Unable to process payment. Invalid payment gateway info!");                
            }

            $meta = unserialize($merchant_payment_gateway->merchant_payment_method_meta);

            ###### get the order #####
            $cart = CartModel::find($cartid);

            switch ($merchant_payment_gateway->payment_method->payment_method_description) {

                case Config::get('constants.common.molpay') :
                    
                    $merchant_id = $meta['merchant_id'];
                    $vkey = $meta['v_key'];
                    $amount = $cart->cart_final_amount;
                    $orderid = $cart->cart_id;
                    $billname = Globe::readmeta($cart->cart_metadata, 'billing')['name'];
                    $billemail = $cart->guest->email;
                    $billmobile = Globe::readmeta($cart->cart_metadata, 'billing')['phone'];
                    $billdesc = $cart->cart_orderno;
                    $country = 'MY';
                    $vcode = md5($amount.$merchant_id.$orderid.$vkey);

                    if( Config::get('constants.common.app_environment') == 'development'):
                        $url = 'https://sandbox.molpay.com/MOLPay/pay/'.$merchant_id.'/';
                    else:
                        $url = 'https://www.onlinepayment.com.my/MOLPay/pay/​'.$merchant_id.'/';
                    endif;

                    $html = '<form name="payment_gateway" action="'.$url.'" method="POST"><input type="hidden" name="amount" value="'.$amount.'" /><input type="hidden" name="orderid" value="'.$orderid.'" /><input type="hidden" name="bill_name" value="'.$billname.'" /><input type="hidden" name="bill_email" value="'.$billemail.'" /><input type="hidden" name="bill_mobile" value="'.$billmobile.'" /><input type="hidden" name="bill_desc" value="'.$billdesc.'" /><input type="hidden" name="country" value="'.$country.'" /><input type="hidden" name="vcode" value="'.$vcode.'" /><input type="hidden" name="returnurl" value="'.route('frontend.payment.response').'" /></form>';

                break;

                case Config::get('constants.common.ipay88') :

                // dd($meta['merchant_key']. ' '.$meta['merchant_code']);
                    
                    $ipay88 = new IPay88($meta['merchant_code']);
                    $ipay88->setMerchantKey($meta['merchant_key']);
                    $ipay88->setField('PaymentId', 16);
                    $ipay88->setField('RefNo', $cart->cart_orderno);
                    $ipay88->setField('Amount', '1'); // $cart->cart_final_amount
                    $ipay88->setField('Currency', 'MYR');
                    $ipay88->setField('ProdDesc', $cart->cart_orderno);
                    $ipay88->setField('UserName', Globe::readmeta($cart->cart_metadata, 'billing')['name']);
                    $ipay88->setField('UserEmail', $cart->guest->email);
                    $ipay88->setField('UserContact', Globe::readmeta($cart->cart_metadata, 'billing')['phone']);
                    $ipay88->setField('Remark', 'Some remarks here..');
                    $ipay88->setField('Lang', 'utf-8');
                    $ipay88->setField('ResponseURL', route('frontend.payment.response'));
                    $ipay88->setField('BackendURL', route('frontend.payment.response'));                    
                    $ipay88->generateSignature();
                    $ipay88_fields = $ipay88->getFields();

                    // prepare the form
                    $html = '<form name="ePayment" action="'.Ipay88::$epayment_url.'" method="post">';
                    foreach ($ipay88_fields as $key => $val):
                        $html .= '<input type="text" name="'.$key.'" value="'.$val.'" />';
                    endforeach;

                    $html .= '<INPUT type="submit" value="Proceed with Payment" name="Submit"></form>';

                break;

                case Config::get('constants.common.senangpay') :
                # code...
                break;
            }

            return view( "Frontend::response_return",['pagetitle'=>'payment',
                                            'html'=> $html,
                                            'type'=>$type]);

        } catch (Exception $e) {

            return Redirect()->back()->withInput()->with(['flash_error'=> $e->getMessage()]);
            
        }

    }

    public function paymentResponse(Request $request)
    {

        try {

            $type='';
            $html='';
            $status = $request->status;

            ####### get the merchant's payment gateway first #######
            $merchant_payment_gateway = MerchantPaymentMethodModel::where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id)
                                        ->where('payment_method_id','!=', 1)->where('merchant_payment_method_status', 1)
                                        ->first();
            $meta = unserialize($merchant_payment_gateway->merchant_payment_method_meta);

            ###### get the order #####
            $cart = CartModel::find($request->orderid);


            switch ($merchant_payment_gateway->payment_method->payment_method_description) {

                case Config::get('constants.common.molpay') :
                    
                    $skey = $meta['s_key'];
                    $key0 = md5( $request->tranID.$request->orderid.$status.$request->domain.$request->amount.$request->currency );
                    $key1 = md5( $request->paydate.$request->domain.$key0.$request->appcode.$skey );

                    if( $request->skey != $key1 ) $status = -1;

                    switch ($request->status) {
                        case '00':
                            $html = '<div class="container"><h3 class="text-success"><i class="fas fa-info"></i> Success</h3></div>';

                            $cartMeta = [ 'shipping' => ['name'=>Globe::readMeta($cart->cart_metadata, 'shipping')['name'],
                                                          'phone'=>Globe::readMeta($cart->cart_metadata, 'shipping')['phone'],
                                                          'address' => Globe::readMeta($cart->cart_metadata, 'shipping')['address']
                                                           ],

                                          'billing'=>['name'=>Globe::readMeta($cart->cart_metadata, 'billing')['name'],
                                                     'phone'=>Globe::readMeta($cart->cart_metadata, 'billing')['phone'],
                                                     'address' => Globe::readMeta($cart->cart_metadata, 'billing')['address']
                                                      ],

                                          'payment_type'=> 'gateway', 'payment_transid'=>$request->tranID, 'payment_paydate'=>$request->paydate, 'payment_channel'=>$request->channel];

                            $cart->cart_payment_status = 1;
                            $cart->cart_metadata = serialize($cartMeta);
                            $cart->updated_at = Carbon::now(Config::get('constants.common.systemtimezone'));
                            $cart->save();

                            DB::commit();
                            return Redirect()->route('frontend.order.detail',[encrypt($cart->cart_id)])->withInput()->with(['flash_success'=>'Payment received!']);

                        break;

                        case '11':
                        case '22':
                            return Redirect()->route('frontend.order.detail',[encrypt($cart->cart_id)])->withInput()->with(['flash_error'=>'Payment failed! '.$request->error_desc]);
                        break;
                    }

                break;

                case Config::get('constants.common.ipay88') :
                    // dd($request->all());

                    if($request->status == 1) {
                        return Redirect()->route('frontend.order.detail',[encrypt($cart->cart_id)])->withInput()->with(['flash_success'=>'Payment received!']);
                        exit;
                    } else {
                        return Redirect()->route('frontend.order.detail',[encrypt($cart->cart_id)])->withInput()->with(['flash_error'=>'Payment failed! '.$request->ErrDesc]);
                        exit;
                    }

                break;

                case Config::get('constants.common.senangpay') :
                # code...
                break;
            }


            
        } catch (Exception $e) {
            
            DB::rollback();
            return Redirect()->back()->withInput()->with(['flash_error'=>$e->getMessage()]);
        }
        
        
    }

    public function logout(Request $request) {
        
        Auth::guard('users_guest')->logout();
        
        $request->session()->forget('guest_id');
        $request->session()->forget('frontend_language');
        return Redirect('frontend');

    }

}
