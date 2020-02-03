@extends('layouts.frontend.electro')


@section('breadcrumb')
<div class="row">
	<div class="col-sm-8">
		<h3 class="breadcrumb-header">{{ __('Frontend::frontend.order_no').' '.$order->cart_orderno }}</h3>
	</div>
	<div class="col-sm-4">
		<a href="{{ route('frontend.order') }}" class="pull-right btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> {{ __('Frontend::frontend.go_back') }}</a>
		<a href="{{ route('order.printdoc',[Crypt::encrypt($order->cart_id),'inv']) }}" class="btn btn-sm btn-warning margin-right-5 pull-right"><i class="fa fa-print"></i> {{ __('Admin::base.print') }}</a>		
	</div>
</div>

@stop()

@section('content')
<div class="container">
	
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-8">
					{!! ($order->cart_payment_status == 1) ? '<span class="badge badge-success">'.__('Frontend::frontend.paid').'</span>' : '<span class="badge badge-warning">'.__('Frontend::frontend.unpaid').'</span>' !!}
					<small>{{ __('Frontend::frontend.placed_on').' '.date('d M Y', strtotime($order->created_at)) }}</small>
				</div>
				<div class="col-sm-4"><span class="pull-right"><strong>{{ __('Frontend::frontend.total').' : '.Globe::moneyFormat($order->cart_final_amount) }}</strong></span></div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4">
			
			<!-- billing address -->
			<div class="panel panel-default">
				<div class="panel-body">
					<small>
					<div><strong>{{ __('Frontend::frontend.billing_address') }}</strong></div>
					{{ strtoupper(Globe::readMeta($order->cart_metadata, 'billing')['name']) }}<br />
					{!! strtoupper(Globe::readMeta($order->cart_metadata, 'billing')['address']) !!}
					</small>
				</div>
			</div>

			<!-- shipping address -->
			<div class="panel panel-default">
				<div class="panel-body">
					<small>
					<div><strong>{{ __('Frontend::frontend.shipping_address') }}</strong></div>
					{{ strtoupper(Globe::readMeta($order->cart_metadata, 'shipping')['name']) }}<br />
					{!! strtoupper(Globe::readMeta($order->cart_metadata, 'shipping')['address']) !!}
					</small>
				</div>
			</div>

			@if( $order->cart_payment_status == 1 )
			<div class="panel panel-default">
				<div class="panel-body">
					<small>
						<!-- <div><strong>{{-- __('Frontend::frontend.payment_info') --}}</strong></div> -->
						@if( Globe::readMeta($order->cart_metadata, 'payment_type') == 'direct' )
							<b>{{ __('Frontend::frontend.payment_method') }}</b><div>{{ __('Frontend::frontend.direct_payment_transfer') }}</div>
						@else
							<b>{{ __('Frontend::frontend.payment_method') }}</b><div>{{ __('Frontend::frontend.online_payment') }}</div><br>
							<b>{{ __('Frontend::frontend.payment_datetime') }}</b> 
							<div>{{ date('d M Y, h:i:s A', strtotime( Globe::readMeta($order->cart_metadata, 'payment_paydate') )) }}</div>
							{{ Globe::readMeta($order->cart_metadata, 'payment_channel') }}
						@endif
					</small>
				</div>
			</div>
			@endif

			@if($order->cart_payment_status == 0)
			<div class="panel panel-default">
				<div class="panel-body">
					<small>
					<div><strong>{{ __('Frontend::frontend.payment_method') }}</strong></div>

					<?php 

						if(Globe::readMeta($order->cart_metadata, 'payment_type') == 'direct') {
							$direct = 'checked=""';
							$gateway = '';
						} else {
							$direct = '';
							$gateway = 'checked=""';
						}

					?>

					<!-- payment options -->
					<div class="order-summary">
						<div class="payment-method">
							@if(!empty($directPayment))
							<div class="input-radio">
								<input type="radio" name="payment" {{$direct}} value="direct" id="payment-1">
								<label for="payment-1">
									<span></span>{{ __('Frontend::frontend.direct_payment_transfer') }}
								</label>
								<div class="caption">
									@if( count($order->paymentslips) < Config::get('constants.common.paymentslip_limit') )
									<div class="ol-sm-12 fileuploadform">
									  <input type="hidden" class="getid" name="getid" value="{{ encrypt($order->cart_id) }}">	
							          <input id="paymentslip" name="paymentslip" type="file"><br>
							          <div class="row">
							            <div class="col-sm-3"><input class="btn btn-xs btn-default" onclick="uploadFile()" value="{{ __('Frontend::frontend.upload') }}" type="button" id="submitbtn"></div>
							            <div class="col-sm-9 pull-right">
							              <div id="progress-wrp" class="progress progress-sm">
							                <div class="progress-bar progress-bar-info">0%</div >
							              </div>
							            </div>
							          </div>
							          <div id="outputupload"><!-- error or success results --></div>                                            
							        </div>
							        @endif

							        @if( count($order->paymentslips) > 0)
							        	
							        		@foreach($order->paymentslips as $slip)
							        		<i class="fas fa-file"></i> {{ $slip->upload_filename }} <a href="javascript:;" data-id="{{ encrypt($slip->upload_id) }}" class="file-delete text-secondary"><i class="fa fa-times-circle"></i></a><br />
							        		@endforeach
							        @endif
							        <hr>
								</div>
							</div>
							@endif

							@if(!empty($onlinePayment))
							@if( count($order->paymentslips) == 0)
							<div class="input-radio">
								<input type="radio" name="payment" {{$gateway}} value="gateway" id="payment-2">
								<label for="payment-2">
									<span></span>{{ __('Frontend::frontend.online_payment') }}
								</label>
								<div class="caption">
									<a href="{{ URL::to('frontend/online/payment', ['request', encrypt($order->cart_id)]) }}" class="primary-btn order-submit order-checkout-submit">{{ __('Frontend::frontend.proceed_to_pay') }}</a>
								</div>
							</div>
							@endif
							@endif
						</div>
					</div>
					</small>

				</div>
			</div>
			@endif

		</div>
		<div class="col-sm-8">
			<div class="panel panel-default">
				<div class="panel-body">
					<table class="table table-hover" width="100%">
						<thead>
						<tr>
							<th></th>
							<!-- <th class="text-right">GST</th> -->
							<th class="text-right">{{ __('Frontend::frontend.product_price') }}</th>
						</tr>
						</thead>
						@foreach($order->detail as $detail)
						<tr>
							<td width="80%">
								<div>{{ $detail->stock->product->product_name }}</div>
								<div><small>{{ Globe::readStockAttribute($detail->stock->product_stock_variant) }} | {{ __('Frontend::frontend.product_qty').' : '.$detail->cart_detail_quantity }}</small></div>
							</td>
							<td class="text-right">{{ Globe::moneyFormat($detail->cart_detail_actual_amount) }}</td>
						</tr>
						@endforeach

						<tfoot>
							<tr>
								<td class="text-right"><strong>{{ __('Frontend::frontend.subtotal') }}</strong></td>
								<td class="text-right">{{ Globe::moneyFormat($order->cart_actual_amount) }}</td>
							</tr>
							<tr>
								<td class="text-right"><strong>{{ __('Frontend::frontend.shipping_fee') }}</strong></td>
								<td class="text-right">{{ Globe::moneyFormat($order->cart_shipping_amount) }}</td>
							</tr>
							<tr>
								<td class="text-right"><strong>{{ __('Frontend::frontend.total_gst') }}</strong></td>
								<td class="text-right">{{ Globe::moneyFormat($order->cart_gst_amount) }}</td>
							</tr>
							<tr>
								<td class="text-right"><strong>{{ __('Frontend::frontend.total') }}</strong></td>
								<td class="text-right">{{ Globe::moneyFormat($order->cart_final_amount) }}</td>
							</tr>
						</tfoot>

					</table>
				</div>
			</div>
		</div>
	</div>

</div>
@stop()

@section('footer')
<script type="text/javascript">

	$('.file-delete').on('click', function() {
		
		swal({
		  width: 400,
		  text: "{{ __('Frontend::frontend.delete_payment_slip') }}",
		  type: 'warning',
		  allowOutsideClick: false,
		  showCancelButton: true,
    	  cancelButtonText: "{{ __('Admin::base.cancel') }}",
		}).then((result) => {
		  if (result) {
		    
		  	$.ajax({

				type: "POST",
				url : '{{ URL::to("frontend/delete/payment_slip") }}',
				data: {uid: $(this).data('id') },
				dataType: "json",
				cache:false,
				success:
				function(data) {
					if(data.status == 'OK') {
						window.location.reload();
					}
				}

			});

		  }
		})

	});

	// function upload file thru ajax
	function uploadFile() {

		if( document.getElementById("paymentslip").files.length == 0 ){
			swal({
			  // title: "",
			  text: "{{ __('Frontend::frontend.please_select_file') }}",
			  type: 'error',
			});
		} else {

	      var progress_bar_id = '#progress-wrp'; //ID of an element for response output      

	      // membaca data file yg akan diupload, dari komponen 'paymentslip'
	      var file = document.getElementById("paymentslip").files[0];
	      var getid = $('.getid').val();
	      var formdata = new FormData();
	      formdata.append("paymentslip", file);
	      formdata.append("cartid", getid);

	      $('#submitbtn').val("{{ __('Frontend::frontend.please_wait') }}").prop( "disabled", true); //disable submit button

	      $.ajax({
	        url: '{{ URL::action("\App\Modules\Frontend\Controllers\FrontendController@uploadPaymentSlip") }}', // point to server-side PHP script
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formdata,                         
	        type: 'post',
	        xhr: function(){
	          //upload Progress
	          var xhr = $.ajaxSettings.xhr();
	          if (xhr.upload) {
	              xhr.upload.addEventListener('progress', function(event) {
	                  var percent = 0;
	                  var position = event.loaded || event.position;
	                  var total = event.total;
	                  if (event.lengthComputable) {
	                      percent = Math.ceil(position / total * 100);
	                  }
	                  //update progressbar
	                  $(progress_bar_id +" .progress-bar").css("width", + percent +"%");
	                  $(progress_bar_id + " .progress-bar-info").text(percent +"%");
	              }, true);
	          }
	          return xhr;
	        },
	        mimeType:"multipart/form-data",
	        success: function(html) {

	        	var returnedata = JSON.parse(html);
	        	if(returnedata.status == 'INVALID') {

	        		swal({
					  // title: "",
					  text: returnedata.errors,
					  type: 'error',
					});

	        	} else {
	        		window.location.reload();
	        	}

	        }

	      }).done(function(res){ 
	          
	          document.getElementById("paymentslip").value = "";
	          // $(result_output).html(res); //output response from server
	          $('#submitbtn').val("{{ __('Frontend::frontend.upload') }}").prop( "disabled", false); //enable submit button once ajax is done
	      });
	    }

  }

</script>
@stop