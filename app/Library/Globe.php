<?php 

namespace App\Library;

use Auth;
use App\Modules\Admin\Models\BaseConfigModel;
use App\Modules\Admin\Models\MenuModel;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;
use App\Modules\Merchant\Models\MerchantModel;
use App\Modules\Product\Models\ProductModel;
use App\Modules\Product\Models\ProductStockModel;
use App\Modules\Merchant\Models\MerchantBranchModel;
use App\Modules\Merchant\Models\MerchantPageModel;
use App\Modules\Admin\Models\MasterStateModel;
use App\Modules\Merchant\Models\MerchantConfigurationModel;
use App\Modules\Admin\Models\MasterRunningNumberModel;
use App\Modules\Product\Models\ProductStockLedgerModel;
use App\Modules\Product\Models\ProductStockMovementModel;
use App\Modules\Product\Models\ProductStockTransactionModel;
use App\Modules\Order\Models\CartModel;
use App\Modules\Order\Models\CartDetailModel;
use App\Modules\Product\Models\ProductStockQuantityMovementModel;
use App\Modules\Product\Models\MasterProductAttributeModel;
use App\Modules\Frontend\Models\UserGuestModel;
use App\Modules\Frontend\Models\UserGuestAddressModel;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
* global function place here
*/
class Globe
{
	
	// populate stock attribute on cart
	public static function readStockAttribute($columnValue) {
		
		$newValue = explode(',', $columnValue);
		foreach ($newValue as $value) {

			$attributePosition = strpos($value, ":");
			$attributeValuePosition = strrpos($value, ":");

			$attributeid = substr($value, 0, $attributePosition);  
			$attributevalueid = str_replace(':','', substr($value, $attributeValuePosition ));

			$attribute = MasterProductAttributeModel::where('attribute_id', $attributeid)
					->where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id)->first();

			$attributeValue = $attribute->value->where('attribute_value_id', $attributevalueid)->first();
			$attr = $attribute->attribute_desc.' : '.$attributeValue['attribute_value_desc'];
		}

		return $attr;
	}

	// read cart_metadata column
	public static function readMeta($metavalue, $metakey)
	{
		$data = unserialize($metavalue);

		if( empty($metavalue) || empty($data[$metakey]) ) {
			return '';
		} else {
			return $data[$metakey];
		}

		
	}

	public static function running_no($merchantid, $prefix='frontend')
    {
    	$result = MasterRunningNumberModel::where('merchant_id', $merchantid)
    				->where('running_number_date', date('Y-m-d'))->first();

    	if(!$result) { // sepatutnya proses ni throw error, tapi cpanel tak suppurt event
    		// throw new Exception("Error Processing Running Number");

    		$result = MasterRunningNumberModel::where('merchant_id', $merchantid)->first();
    		$result->running_number_date = date('Y-m-d');
    		$result->running_number_frontend = 1;
    		$result->running_number_quotation = 1;
    		$result->running_number_invoice = 1;
    		$result->save();

    		switch ($prefix) {
    			case 'inv':
    				$reference = strtoupper($prefix).date('ymd', strtotime($result->running_number_date)).$merchantid.str_pad($result->running_number_invoice, 5, "0", STR_PAD_LEFT);
    				$result->running_number_invoice +=1;
    			break;

    			case 'quo':
    				$reference = strtoupper($prefix).date('ymd', strtotime($result->running_number_date)).$merchantid.str_pad($result->running_number_quotation, 5, "0", STR_PAD_LEFT);
    				$result->running_number_quotation +=1;
    			break;
    			
    			default:
    				$reference = '#'.date('ymd', strtotime($result->running_number_date)).$merchantid.str_pad($result->running_number_frontend, 5, "0", STR_PAD_LEFT);
    				$result->running_number_frontend +=1;
    			break;
    		}

    		$result->save();


    	} else {

    		switch ($prefix) {
    			case 'inv':
    				$reference = strtoupper($prefix).date('ymd', strtotime($result->running_number_date)).$merchantid.str_pad($result->running_number_invoice, 5, "0", STR_PAD_LEFT);
    				$result->running_number_invoice +=1;
    			break;

    			case 'quo':
    				$reference = strtoupper($prefix).date('ymd', strtotime($result->running_number_date)).$merchantid.str_pad($result->running_number_quotation, 5, "0", STR_PAD_LEFT);
    				$result->running_number_quotation +=1;
    			break;
    			
    			default:
    				$reference = '#'.date('ymd', strtotime($result->running_number_date)).$merchantid.str_pad($result->running_number_frontend, 5, "0", STR_PAD_LEFT);
    				$result->running_number_frontend +=1;
    			break;
    		}

    		$result->save();

    	}

        return $reference;
    }

	public static function baseconf($param = '') {
		
		return BaseConfigModel::where('config_attribute', $param)->value('config_value');

	}


	public static function product_category($parent = 0, $level = 0)
	{
		$categories = \App\Modules\Product\Models\ProductTypeModel::where('merchant_id', session($_SERVER['SERVER_NAME'])->merchant_id)->where('product_type_parent_id', $parent)->orderBy('product_type_id','asc')->get();

		foreach ($categories as $category) {
			echo "<option value='".$category->product_type_id."'>".str_repeat("&nbsp;", $level).$category->product_type_desc."</option>";

			self::product_category($category->product_type_id, $level+1);
		}
	}
	

	 public static function generateRandomString($length = 10, $char='0123456%&789abcdefgh#$ijklmnopqrstuvwxyzABCDEFGHI!@JKLMNOPQRSTUVWXYZ') 
	 {
	    $characters = $char;
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public static function RedirectGuestIfNotAuth() {
		
		if(!Auth::guard('users_guest')->check()) {
			redirect('frontend/login');
		}
	}


	public static function checkslugvalue($merchantid,$tbl,$column,$val,$existid='',$pkcolumn=''){

		$newval = strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','-', trim($val)));

		$existslug = '';

		if($existid != ''):
			$existslug = DB::table($tbl)->where('merchant_id',$merchantid)->where($pkcolumn,$existid)->pluck($column)[0];
		endif;

		//dd($existslug.'-'.$newval);

		if($existslug == '' || ($existslug != '' && $existslug != $newval)):

			$count = DB::table($tbl)->where('merchant_id',$merchantid)->where($column,$newval)->whereNull('deleted_at')->count();

			if($count>0):
				$count2 = DB::table($tbl)->where('merchant_id',$merchantid)->where($column,'LIKE',$newval.'-%')->whereNull('deleted_at')->count();

				if($count2>0):
					return $newval.'-'.($count2+1);
				else:
					return $newval.'-1';
				endif;

			else:

				return $newval;

			endif;

		else:

			return $newval;

		endif;		

	}

	public static function shippingcost($weight,$stateid='4',$merchantid){

		$state = MasterStateModel::findOrFail($stateid);
		$merchant = MerchantConfigurationModel::where('merchant_id',$merchantid)->first();
		$charge = 0;

		if($merchant->merchant_config_ship_status == 1){

			if($state->area_id==1){
				$uptokg = $merchant->merchant_config_ship_west_upto_weight;
				$uptoprice = $merchant->merchant_config_ship_west_upto_price;
				$addkg = $merchant->merchant_config_ship_west_add_weight;
				$addprice = $merchant->merchant_config_ship_west_add_price;
			}else{
				$uptokg = $merchant->merchant_config_ship_east_upto_weight;
				$uptoprice = $merchant->merchant_config_ship_east_upto_price;
				$addkg = $merchant->merchant_config_ship_east_add_weight;
				$addprice = $merchant->merchant_config_ship_east_add_price;
			}

			if($weight <= $uptokg){
				$charge = $uptoprice;
			}else{

				$times = ceil( ($weight - $uptokg) / $addkg );
				$charge = $uptoprice + ($addprice * $times);
				
			}
			
		}

		return $charge;

	}

	public static function itemcost($stockid,$quantity){

		$stock = ProductStockModel::findOrFail($stockid);

		$weight = $stock->product_stock_weight*$quantity;
		$price = $stock->product_stock_sale_price;
		$amount = $price*$quantity;
		$gst = $stock->product->taxpurchase->tax_charge;
		$gstcost = ($gst*$amount)/100;
		$finalamount = $amount+$gstcost;

		$data = ['amount'=>$amount, 'gstcost'=>$gstcost, 'weight'=>$weight, 'finalamount'=>$finalamount, 'gst'=>$gst, 'price'=>$price];

		return $data;

	}

	public static function dataisaccessible($tbl,$id){

		$role = Auth::user()->roles->first()->name;

		if($role == 'merchant'){ 

			if($tbl == 'merchant'){

				$merchant = MerchantModel::findOrFail($id);

				if($merchant->user->id != Auth::user()->id){
					//return Redirect('home')->with('flash_error', __('Admin::base.norightaccess'));
					abort(403);
				}

			}elseif($tbl == 'merchant_branch'){

				$branch = MerchantBranchModel::findOrFail($id);

				if($branch->merchant_id != Auth::user()->merchant_id){
					abort(403);
				}

			}elseif($tbl == 'product'){

				$product = ProductModel::findOrFail($id);

				if($product->merchant_id != Auth::user()->merchant_id){
					abort(403);
				}

			}elseif($tbl == 'merchant_page'){

				$page = MerchantPageModel::findOrFail($id);

				if($page->merchant_id != Auth::user()->merchant_id){
					abort(403);
				}

			}

		}elseif($role == 'agent'){ 

			if($tbl == 'merchant'){

				$merchant = MerchantModel::findOrFail($id);

				if($merchant->agent->id != Auth::user()->id){
					abort(403);
				}

			}elseif($tbl == 'merchant_branch'){

				$branch = MerchantBranchModel::findOrFail($id);

				if($branch->merchant->agent->id != Auth::user()->id){
					abort(403);
				}

			}elseif($tbl == 'product'){

				$product = ProductModel::findOrFail($id);

				if($product->merchant->agent->id != Auth::user()->id){
					abort(403);
				}

			}elseif($tbl == 'merchant_page'){

				$page = MerchantPageModel::findOrFail($id);

				if($page->merchant->agent->id != Auth::user()->id){
					abort(403);
				}

			}

		}elseif($role == 'branch'){ 

			if($tbl == 'merchant'){

				$branch = MerchantBranchModel::findOrFail(Auth::user()->branch_id);

				if($branch->merchant_id != $id){
					abort(403);
				}

			}elseif($tbl == 'merchant_branch'){

				if(Auth::user()->branch_id != $id){
					abort(403);
				}

			}elseif($tbl == 'product'){

				$product = ProductModel::findOrFail($id);

				if($product->merchant_id != Auth::user()->merchant_id){
					abort(403);
				}

			}elseif($tbl == 'merchant_page'){

				abort(403);

			}

		}

	}

	// function ni belum complete lagi, akan guna di frontend
	public static function getStockWithMinPrice($productid, $column='product_stock_sale_price')
	{
		$query  = \App\Modules\Product\Models\ProductStockModel::where('product_id', $productid)
					->where('product_stock_quantity','!=',0)
					->where('product_stock_status', 1)
					->min('product_stock_sale_price');

		$minprice = \App\Modules\Product\Models\ProductStockModel::where('product_id', $productid)
					->where('product_stock_sale_price', $query )
					->select('product_stock_sale_price', 'product_stock_id', 'product_stock_market_price' )->first();

		return $minprice[$column];
	}

	public static function truncateString($mytext, $numberofstring=300)
	{
		//Number of characters to show  
		$chars = $numberofstring;  
		$mytext = substr($mytext,0,$chars);  
		$mytext = substr($mytext,0,strrpos($mytext,' '));  
		$mytext = $mytext.'...';
		return $mytext;  
	}

	// function for money formatter
	public static function moneyFormat($value = 0.00, $decimal_point = 2)
    {
        $value = floatval($value);
        $value = number_format($value, $decimal_point);

        if ($value < 0) {
            return '('.str_replace('-', '', $value).')';
        }

        return $value;
    }

    //start deductstockfromcart
    public static function stockmovementfromcart($id, $type='out'){

    	$cartdetail = CartDetailModel::where('cart_detail_id',$id)->first();

    	$stock = ProductStockModel::findOrFail($cartdetail->product_stock_id);

    	$before = $stock->product_stock_quantity;
    	$different = -1*$cartdetail->cart_detail_quantity;
    	$after = ($before-$cartdetail->cart_detail_quantity);

    	$stock->product_stock_quantity = $after;

    	if($stock->save()){

	    	$ledger = ProductStockLedgerModel::where('product_stock_id',$cartdetail->product_stock_id)->where('product_stock_ledger_year',date('Y',strtotime($cartdetail->created_at)))->first();

	    	if(empty($ledger)){

	    		$ledger = new ProductStockLedgerModel;
	    		$ledger->product_stock_id = $cartdetail->product_stock_id;
	    		$ledger->product_stock_ledger_year = date('Y',strtotime($cartdetail->created_at));

	    		if($ledger->save()){
	    			for($i=1; $i<=12; $i++){

	    				$movement = new ProductStockMovementModel;
	    				$movement->product_stock_ledger_id = $ledger->product_stock_ledger_id;
	    				$movement->product_stock_movement_month = $i;
	    				$movement->created_at = Carbon::now();
	    				$movement->save();

	    			}  
	    		}

	    	}

	    	$ledger->product_stock_ledger_quantity_out += $cartdetail->cart_detail_quantity;
	    	$ledger->product_stock_ledger_quantity_out_price += $cartdetail->cart_detail_final_amount;

	    	if($ledger->save()){

	    		$movement = ProductStockMovementModel::where('product_stock_ledger_id',$ledger->product_stock_ledger_id)->where('product_stock_movement_month',str_replace('0','',date('m',strtotime($cartdetail->created_at))))->first();

	    		$movement->product_stock_movement_quantity_out += $cartdetail->cart_detail_quantity;
	    		$movement->product_stock_movement_quantity_out_price += $cartdetail->cart_detail_final_amount;

	    		if($movement->save()){

	    			$transaction =  new ProductStockTransactionModel;
	    			$transaction->product_stock_movement_id = $movement->product_stock_movement_id;
	    			$transaction->product_stock_transaction_model = 'CartModel';
	    			$transaction->product_stock_transaction_model_id = $cartdetail->cart->cart_id;
	    			$transaction->product_stock_transaction_reference = $cartdetail->cart->cart_orderno;
	    			$transaction->product_stock_transaction_remark = $cartdetail->cart->cart_remark;
	    			$transaction->product_stock_transaction_quantity_out = $cartdetail->cart_detail_quantity;
	    			$transaction->product_stock_transaction_quantity_out_price = $cartdetail->cart_detail_final_amount;
	    			
	    			if($transaction->save()){

	    				$stockquantity = new ProductStockQuantityMovementModel;
	    				$stockquantity->product_stock_id = $stock->product_stock_id;
	    				$stockquantity->product_stock_quantity_movement_model = 'CartModel';
	    				$stockquantity->product_stock_quantity_movement_model_id = $cartdetail->cart->cart_id;
	    				$stockquantity->product_stock_quantity_movement_reference = $cartdetail->cart->cart_orderno;
	    				$stockquantity->product_stock_quantity_movement_remark = $cartdetail->cart->cart_remark;
	    				$stockquantity->product_stock_quantity_movement_before = $before;
	    				$stockquantity->product_stock_quantity_movement_movement = $different;
	    				$stockquantity->product_stock_quantity_movement_after = $after;
	    				$stockquantity->save();

	    			}

	    		}

	    	}

	    }

    }
    //end deductstockfromcart

    //start stockmovementfromproduct
    public static function stockmovementfromproduct($id,$qty,$amount,$remark,$reference=''){

    	if($qty != 0){

	    	$stock = ProductStockModel::findOrFail($id);

	    	$before = $stock->product_stock_quantity;
	    	$different = $qty;
	    	$after = $before+$different;

	    	$stock->product_stock_quantity = $after;
	    	$stock->updated_at = Carbon::now();

	    	if($stock->save()){

	    		$transdate = $stock->updated_at;

		    	$ledger = ProductStockLedgerModel::where('product_stock_id',$id)->where('product_stock_ledger_year',date('Y',strtotime($transdate)))->first();

		    	if(empty($ledger)){

		    		$ledger = new ProductStockLedgerModel;
		    		$ledger->product_stock_id = $stock->product_stock_id;
		    		$ledger->product_stock_ledger_year = date('Y',strtotime($transdate));

		    		if($ledger->save()){
		    			for($i=1; $i<=12; $i++){

		    				$movement = new ProductStockMovementModel;
		    				$movement->product_stock_ledger_id = $ledger->product_stock_ledger_id;
		    				$movement->product_stock_movement_month = $i;
		    				$movement->created_at = Carbon::now();
		    				$movement->save();

		    			}  
		    		}

		    	}

		    	if($qty<0){
		    		$ledger->product_stock_ledger_quantity_out += (-1*$qty);
		    		$ledger->product_stock_ledger_quantity_out_price += $amount;
		    	}else{
		    		$ledger->product_stock_ledger_quantity_in += $qty;
		    		$ledger->product_stock_ledger_quantity_in_price += $amount;
		    	}

		    	if($ledger->save()){

		    		$movement = ProductStockMovementModel::where('product_stock_ledger_id',$ledger->product_stock_ledger_id)->where('product_stock_movement_month',str_replace('0','',date('m',strtotime($transdate))))->first();

		    		if($qty<0){
		    			$movement->product_stock_movement_quantity_out += (-1*$qty);
		    			$movement->product_stock_movement_quantity_out_price += $amount;
			    	}else{
			    		$movement->product_stock_movement_quantity_in += $qty;
		    			$movement->product_stock_movement_quantity_in_price += $amount;
			    	}

		    		if($movement->save()){

		    			$transaction =  new ProductStockTransactionModel;
		    			$transaction->product_stock_movement_id = $movement->product_stock_movement_id;
		    			$transaction->product_stock_transaction_reference = ($reference!='') ? $reference : 'MERCHANT'.$stock->product->merchant_id;
		    			$transaction->product_stock_transaction_remark = $remark;
		    			if($qty<0){
		    				$transaction->product_stock_transaction_quantity_out = (-1*$qty);
		    				$transaction->product_stock_transaction_quantity_out_price = $amount;
				    	}else{
				    		$transaction->product_stock_transaction_quantity_in = $qty;
		    				$transaction->product_stock_transaction_quantity_in_price = $amount;
				    	}
		    			
		    			if($transaction->save()){

		    				$stockquantity = new ProductStockQuantityMovementModel;
		    				$stockquantity->product_stock_id = $stock->product_stock_id;
		    				$stockquantity->product_stock_quantity_movement_reference = ($reference!='') ? $reference : 'MERCHANT'.$stock->product->merchant_id;
		    				$stockquantity->product_stock_quantity_movement_remark = $remark;
		    				$stockquantity->product_stock_quantity_movement_before = $before;
		    				$stockquantity->product_stock_quantity_movement_movement = $different;
		    				$stockquantity->product_stock_quantity_movement_after = $after;
		    				$stockquantity->save();

		    			}

		    		}

		    	}

		    }
		}

    }
    //end stockmovementfromproduct

    public static function printreceipt($cartid,$type='inv'){

  //   	$mpdf = new Mpdf(['tempDir' => public_path().'/uploads/print']);
		// $mpdf->WriteHTML('<h1>Hello world!</h1>');
		// $mpdf->Output();

		$data = CartModel::findOrFail($cartid);

		if($type=='inv'){
			$referenceno = __('Order::order.taxinvoice').': '.$data->cart_orderno;
		}elseif($type=='quo'){
			$referenceno = __('Order::order.quotation').': '.$data->cart_orderno;
		}else{
			$referenceno = __('Order::order.deliveryorder').': '.$data->cart_orderno;
		}

		//merchantaddress
		$merchantaddress = '<h3>'.strtoupper($data->guest->merchant->merchant_name).' ('.$data->guest->merchant->merchant_ssmno.')</h3>';
		if($data->guest->merchant->configuration->merchant_config_address1!=''){
			$merchantaddress .= $data->guest->merchant->configuration->merchant_config_address1.'<br>';
		}
		if($data->guest->merchant->configuration->merchant_config_address2!=''){
			$merchantaddress .= $data->guest->merchant->configuration->merchant_config_address2.'<br>';
		}
		if($data->guest->merchant->configuration->merchant_config_address3!=''){
			$merchantaddress .= $data->guest->merchant->configuration->merchant_config_address3.'<br>';
		}
		$merchantaddress .= $data->guest->merchant->configuration->merchant_config_postcode.', '.$data->guest->merchant->configuration->district->district_desc.'<br>'; 
		$merchantaddress .= $data->guest->merchant->configuration->state->state_desc.'<br>'; 
		$merchantaddress .= __('Order::order.contactno').': '.$data->guest->merchant->configuration->merchant_config_mobileno.'<br>'; 
		$merchantaddress .= __('Admin::base.email').': '.$data->guest->merchant->configuration->merchant_config_email.'<br><br>'; 
		$merchantaddress .= __('Admin::base.date').': '.date( 'd/m/Y H:i:s',strtotime($data->created_at));

		//$customerbilladdress = __('Order::order.billingto').'<br><h3>'.strtoupper(Globe::readMeta($data->cart_metadata, 'billing')['name']).'</h3><pre>';
		$customerbilladdress = __('Order::order.billingto').'<br><br>'.strtoupper(Globe::readMeta($data->cart_metadata, 'billing')['name']).'<br>';
		$customerbilladdress .= strtoupper(Globe::readMeta($data->cart_metadata, 'billing')['address']).'<br>';
		$customerbilladdress .= __('Order::order.contactno').': '.strtoupper(Globe::readMeta($data->cart_metadata, 'billing')['phone']).'<br>';
		$customerbilladdress .= __('Admin::base.email').': '.$data->guest->email.'</pre>';

		$customershipaddress = '';
		if($type!='quo'){
			//$customershipaddress = __('Order::order.shippingto').'<br><h3>'.strtoupper(Globe::readMeta($data->cart_metadata, 'shipping')['name']).'</h3><pre>';
			$customershipaddress = __('Order::order.shippingto').'<br><br>'.strtoupper(Globe::readMeta($data->cart_metadata, 'shipping')['name']).'<br>';
			$customershipaddress .= strtoupper(Globe::readMeta($data->cart_metadata, 'shipping')['address']).'<br>';
			$customershipaddress .= __('Order::order.contactno').': '.strtoupper(Globe::readMeta($data->cart_metadata, 'shipping')['phone']).'<br></pre>';
		}

		$thead = '<thead><tr bgcolor="#172f93">
					<th width="3%"><font color="#FFFFFF">#</font></th><th><font color="#FFFFFF">'.__('Product::product.product').'</font></th>';

		if(in_array($type,['inv','quo'])){
            $thead .= '<th align="center" width="12%"><font color="#FFFFFF">'.__('Order::order.price').'</font></th>
                    <th align="center" width="10%"><font color="#FFFFFF">GST (%)</font></th>';
        }
        $thead .= '<th align="center" width="10%"><font color="#FFFFFF">'.__('Order::order.weight').'</font></th>
                    <th align="center" width="10%"><font color="#FFFFFF">'.__('Order::order.quantity').'</font></th>';
        if(in_array($type,['inv','quo'])){
            $thead .= '<th align="center" width="10%"><font color="#FFFFFF">Subtotal</font></th>';
        }
		$thead .= '</tr></thead>';

		$detailrecord = '<tbody>';
		foreach($data->detail as $key=>$detail){

			$detailrecord .= '<tr>';
			$detailrecord .= '<td>'.($key+1).'</td>';
			$detailrecord .= '<td>'.$detail->stock->product->product_name.' - '.$detail->stock->product_stock_description.'</td>';
			if(in_array($type,['inv','quo'])){
				$detailrecord .= '<td align="right">'.number_format($detail->cart_detail_price,2).'</td>';
				$detailrecord .= '<td align="right">'.number_format($detail->cart_detail_gst_percent,2).'</td>';
			}
			$detailrecord .= '<td align="right">'.number_format($detail->cart_detail_weight,2).'</td>';
			$detailrecord .= '<td align="right">'.number_format($detail->cart_detail_quantity).'</td>';
			if(in_array($type,['inv','quo'])){
				$detailrecord .= '<td align="right">'.number_format($detail->cart_detail_actual_amount,2).'</td>';
			}
			$detailrecord .= '</tr>';

		}
		$detailrecord .= '</tbody>';

		$tfoot = '';
		if(in_array($type,['inv','quo'])){
			$tfoot = '<tfoot>
					<tr>
						<td colspan="6" align="right">'.__('Order::order.totalamount').'</td>
						<td align="right">'.number_format($data->cart_actual_amount,2).'</td>
					</tr>
					<tr>
						<td colspan="6" align="right">'.__('Order::order.totalgst').'</td>
						<td align="right">'.number_format($data->cart_gst_amount,2).'</td>
					</tr>
					<tr>
						<td colspan="6" align="right">'.__('Order::order.shippingcost').'</td>
						<td align="right">'.number_format($data->cart_shipping_amount,2).'</td>
					</tr>
					<tr>
						<td colspan="6" align="right">'.__('Order::order.finalamount').'</td>
						<td align="right" class="gray">'.number_format($data->cart_final_amount,2).'</td>
					</tr>
				</tfoot>';
		}

		$html = '<!doctype html>
		<html lang="en">
		<head>
		<meta charset="UTF-8">
		<title>'.$data->cart_orderno.'</title>

		<style type="text/css">
			* {
				font-family: Verdana, Arial, sans-serif;
			}
			table{
				font-size: x-small;
			}
			tfoot tr td{
				font-weight: bold;
				font-size: x-small;
			}
			.gray {
				background-color: lightgray
			}
			p {
			    word-spacing: 30px;
			}
		</style>
		</head>

		<body>
		<table width="100%">
			<tr>
				<!--td valign="top"><img src="'.asset('img/agent.jpg').'" alt="" width="150"/></td-->
				<td valign="bottom" align="left"><b>'.$referenceno.'</b></td>
				<td align="right">'.$merchantaddress.'</td>
			</tr>
		</table>
		<hr>
		<table width="100%">
			<tr>
				<td align="left">'.$customerbilladdress.'</td>
				<td align="right">'.$customershipaddress.'</td>
			</tr>
		</table>

		<br/>

		<table width="100%" cellpadding="5">
			'.$thead.$detailrecord.$tfoot.'
		</table>
		<hr>
		<table width="100%"><tr><td align="right">'.__('Admin::base.printeddate').': '.date('d/m/Y H:i:s').'</td></tr></table>
		
		</body>
		</html>';

		$dompdf = new Dompdf();
		$dompdf->set_option('defaultFont', 'Helvetica');
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream($data->cart_orderno.'.pdf');

		return $dompdf->download('pdfview.pdf');

    }

    public static function guestaddresshtml($guestid,$type,$addid){

    	if($addid == ''){
	    	$guest = UserGuestModel::findOrFail($guestid);

	    	if($type == 'billing'){
	    		$name = $guest->billingAddress->guest_address_name;
	    		$phone = $guest->billingAddress->guest_address_phone;
		    	$addressname = '<strong>'.$guest->billingAddress->guest_address_name.'</strong><br>';
		        $addresscontact = __('Order::order.contactno').': '.$guest->billingAddress->guest_address_phone.'<br>';
		        $addresscontact .= __('Admin::base.email').': '.$guest->email;
		        $newaddresstxt = $guest->billingAddress->guest_address_one.'<br>';
		        if($guest->billingAddress->guest_address_two != ''){
		            $newaddresstxt .= $guest->billingAddress->guest_address_two.'<br>';
		        }
		        if($guest->billingAddress->guest_address_three != ''){
		            $newaddresstxt .= $guest->billingAddress->guest_address_three.'<br>';
		        }
		        $newaddresstxt .= $guest->billingAddress->guest_address_postcode.' '.$guest->billingAddress->district->district_desc.'<br>';
		        $newaddresstxt .= $guest->billingAddress->state->state_desc;
		    }else{
		    	$name = $guest->shippingAddress->guest_address_name;
	    		$phone = $guest->shippingAddress->guest_address_phone;
		    	$addressname = '<strong>'.$guest->shippingAddress->guest_address_name.'</strong><br>';
		        $addresscontact = __('Order::order.contactno').': '.$guest->shippingAddress->guest_address_phone.'<br>';
		        if($type == 'billing'){
		            $addresscontact .= __('Admin::base.email').': '.$guest->email;
		        }

		        $newaddresstxt = $guest->shippingAddress->guest_address_one.'<br>';
		        if($guest->shippingAddress->guest_address_two != ''){
		            $newaddresstxt .= $guest->shippingAddress->guest_address_two.'<br>';
		        }
		        if($guest->shippingAddress->guest_address_three != ''){
		            $newaddresstxt .= $guest->shippingAddress->guest_address_three.'<br>';
		        }
		        $newaddresstxt .= $guest->shippingAddress->guest_address_postcode.' '.$guest->shippingAddress->district->district_desc.'<br>';
		        $newaddresstxt .= $guest->shippingAddress->state->state_desc;
		    }

	    }else{

	    	$useraddress = UserGuestAddressModel::findOrFail($addid);

	    	$name = $useraddress->guest_address_name;
	    	$phone = $useraddress->guest_address_phone;

	    	$addressname = '<strong>'.$useraddress->guest_address_name.'</strong><br>';
	        $addresscontact = __('Order::order.contactno').': '.$useraddress->guest_address_phone.'<br>';
	        if($type == 'billing'){
	            $addresscontact .= __('Admin::base.email').': '.$guest->email;
	        }

	        $newaddresstxt = $useraddress->guest_address_one.'<br>';
	        if($useraddress->guest_address_two != ''){
	            $newaddresstxt .= $useraddress->guest_address_two.'<br>';
	        }
	        if($useraddress->guest_address_three != ''){
	            $newaddresstxt .= $useraddress->guest_address_three.'<br>';
	        }
	        $newaddresstxt .= $useraddress->guest_address_postcode.' '.$useraddress->district->district_desc.'<br>';
	        $newaddresstxt .= $useraddress->state->state_desc;

	    }

	    $html = strtoupper($addressname.$newaddresstxt).'<br>'.$addresscontact;

	    $data = ['html'=>$html, 'address'=>$newaddresstxt, 'phone'=>$phone, 'name'=>$name];

    	return $data;

    }

    public static function stockmovementdisplay($id,$type){

    	$before = 0;
    	$movement = 0;
    	$after = 0;

    	if($type == 'stock'){

    		$stock = ProductStockQuantityMovementModel::where('product_stock_id',$id)->orderBy('created_at','desc')->first();

    		$before += $stock->product_stock_quantity_movement_before;
    		$movement += $stock->product_stock_quantity_movement_movement;
    		$after += $stock->product_stock_quantity_movement_after;

    	}else{ //product

    		$product = ProductModel::findOrFail($id);

    		if(count($product->stock)>0){
    			
    			foreach ($product->stock as $key => $st) {
    				
    				$stock = ProductStockQuantityMovementModel::where('product_stock_id',$st->product_stock_id)->orderBy('created_at','desc')->first();
    				//dd($stock);
		    		$before += $stock['product_stock_quantity_movement_before'];
    				$movement += $stock['product_stock_quantity_movement_movement'];
    				$after += $stock['product_stock_quantity_movement_after'];

    			}

    		}

    	}

    	$data = ['before'=>$before, 'movement'=>$movement, 'after'=>$after];

    	return $data;

    }

}