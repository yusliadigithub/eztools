@extends('layouts.frontend.electro')

@section('breadcrumb')
<h3 class="breadcrumb-header">{{ __('Frontend::frontend.shopping_cart') }}</h3>
@stop

@section('content')
<div class="container">
	@if( $cartItems->count() > 0 )
	<div class="row">
		<div class="col-sm-8">
		{{-- @if( $cartItems->count() > 0 ) --}}
			<table class="table table-striped table-responsive">
				<thead>
					<tr>
						<th>{{ __('Frontend::frontend.product_name') }}</th>
						<th width="10%" class="text-center">{{ __('Frontend::frontend.product_qty') }}</th>
						<th class="text-right">{{ __('Frontend::frontend.unit_price') }}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($cartItems as $item)
						<tr>
							<td>{{ $item->name }} {{-- Globe::itemcost($item->id, $item->quantity)['finalamount'] --}}</td>
							<td class="text-center">									
								<div class="qty-label">
									<div class="input-number">
										<input type="number" class="item-quantity" name="item-quantity" readonly="" value="{{ $item->quantity }}">
										<span class="qty-up item-amend" data-action="+" data-id="{{ encrypt($item->id) }}">+</span>
										<span class="qty-down item-amend" data-action="-" data-id="{{ encrypt($item->id) }}">-</span>
									</div>
								</div>
							</td>
							<td class="text-right">{{ Globe::moneyFormat($item->price) }}</td>
							<td><a data-id="{{ encrypt($item->id) }}" href="javascript:;" class="text-warning item-remove"><i class="fa fa-trash"></i></div></td>
							<!-- <td class="text-right">{{-- Globe::moneyFormat(Cart::get($item->id)->getPriceSum()) --}}</td> -->
						</tr>
					@endforeach
				</tbody>
			</table>
		{{-- @else --}}
			<!-- <center>{{-- __('Frontend::frontend.no_item_in_cart') --}}</center> -->
		{{-- @endif --}}
			<br>
		</div>
		<div class="col-sm-4 order-details">
			
				<div class="section-title text-center"><h3 class="title">{{ __('Frontend::frontend.order_summary') }}</h3></div>
				<!-- <div class="panel-body"> -->

					@if(Auth::guard('users_guest')->check())
					<div class="order-summary">
						<div>{{ __('Frontend::frontend.ship_to') }} {!! !empty($ship_address) ? '<small><a class="text-info" href="javascript:;">('.__('Frontend::frontend.change').')</a></small>' : '' !!}</div>
						@if(!empty($ship_address))
						<div>
							<small>{{ $ship_address->owner->guest_fullname }}</small><input type="hidden" name="shippingAddress" value="{{ $ship_address->guest_address_id }}">
							<div><small>{{ strtoupper($ship_address->guest_address_one.' '.$ship_address->guest_address_two.' '.$ship_address->guest_address_three).' '.$ship_address->guest_address_postcode.' '.$ship_address->district->district_desc.' '.$ship_address->state->state_desc }}</small></div>
						</div>
						@else 
							<small><a class="text-danger" href="{{ route('frontend.manage.address') }}"><i class="fas fa-exclamation-triangle"></i> {{__('Frontend::frontend.no_shipping_address')}}, {{__('Frontend::frontend.click_here')}}</a></small>
						@endif
					</div>
					<div class="order-summary">
						<div><small>{{ __('Frontend::frontend.bill_to_same_address') }}</small><input type="hidden" name="billingAddress" value=""></div>
					</div>
					<hr>
					@endif

					<table cellpadding="5" width="100%" class="summary order-summary">
						<tr>
							<td width="70%" class="text-info">{{ __('Frontend::frontend.subtotal') }} <span class="total_items">{{Cart::session('cart')->getTotalQuantity()}}</span> {{ __('Frontend::frontend.item') }}</td>
							<td class="text-right subtotal">{{ Globe::moneyFormat(Cart::session('cart')->getSubTotal()) }}</td>
						</tr>
						<tr>
							<td width="70%" class="text-info">{{ __('Frontend::frontend.total_gst') }}</td>
							<td class="text-right gsttotal">{{ Globe::moneyFormat( $total_gst ) }}</td>
						</tr>
						<tr>
							<td width="70%" class="text-info">{{ __('Frontend::frontend.shipping_fee') }}</td>
							<td class="text-right shippingfee">{{ Globe::moneyFormat($shipping_cost) }}</td>
						</tr>
						<!-- <tr class="total">
							<td width="70%"><strong>{{-- __('Frontend::frontend.total') --}}</strong></td>
							<td class="text-right"><strong class="order-total">{{-- Globe::moneyFormat($grand_total) --}}</strong></td>
						</tr> -->
					</table>

					<!-- grand total -->
					<div class="order-summary">
						<div class="order-col">
							<div><strong>{{ __('Frontend::frontend.total') }}</strong></div>
							<div><strong class="order-total">{{ Globe::moneyFormat($grand_total) }}</strong></div>
						</div>
					</div>
					<!-- gst word -->
					<div class="order-summar">
						<div class="order-col">
							<div><small>{{ __('Frontend::frontend.gst_included') }}</small></div>
						</div>
					</div>

					@if(Auth::guard('users_guest')->check())
					<!-- payment options -->
					<div class="order-summary">
						<div class="payment-method">
							@if(!empty($directPayment))
							<div class="input-radio">
								<input type="radio" name="payment" value="direct" id="payment-1">
								<label for="payment-1">
									<span></span>{{ __('Frontend::frontend.direct_payment_transfer') }}
								</label>
								<div class="caption">
									<p>{{ __('Frontend::frontend.direct_payment_transfer_msg') }}.</p>
								</div>
							</div>
							@endif

							@if(!empty($onlinePayment))
							<div class="input-radio">
								<input type="radio" name="payment" value="gateway" id="payment-2">
								<label for="payment-2">
									<span></span>{{ __('Frontend::frontend.online_payment') }}
								</label>
							</div>
							@endif
						</div>
					</div>
					@endif

				<!-- </div> -->
				@if(!Auth::guard('users_guest')->check())
					<br><small><a href="{{ route('frontend.checkout.cart') }}" class="primary-btn order-submit"><i class="fas fa-key"></i> {{ __('Frontend::frontend.please_login_place_order') }}</a></small>
				@else 
					<a href="javascript:;" class="primary-btn order-submit order-checkout-submit">{{ __('Frontend::frontend.proceed_to_pay') }}</a>
				@endif
		</div>
	</div>

	@else

	<center><h1><i class="fa fa-shopping-cart"></i></h1>{{ __('Frontend::frontend.no_item_in_cart') }}</center>

	@endif

	{{-- \Cart::session('cart')->getContent() --}}
</div>
@stop

@section('footer')
<script type="text/javascript">
	
	// delete cart item
	$('.item-remove').on('click', function(){

		swal({
			  width: 400,
			  text: "{{ __('Frontend::frontend.delete_cart_item') }}",
			  // type: 'warning',
			  allowOutsideClick: false,
			  showCancelButton: true,
			  confirmButtonColor: '#226b12',
			  cancelButtonColor: '#d33',
        	  cancelButtonText: "{{ __('Admin::base.cancel') }}",
			}).then((result) => {
			  if (result) {
			    
			  	$.ajax({

					type: "GET",
					url : '{{ URL::to("frontend/cart/remove") }}',
					data: {spid: $(this).data('id') },
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

	// add/remove cart item
	$('span.item-amend').on('click', function(){

		var quantity = $(this).closest('div.input-number').find('input[name=item-quantity]').val();

		$.ajax({

			type: "GET",
			url : '{{ URL::to("frontend/cart/amend") }}',
			data: {spid: $(this).data('id'), action: $(this).data('action'), qty: quantity },
			dataType: "json",
			cache:false,
			success:
			function(data) {
				if(data.status == 'OK') {
					// window.location.reload();
					$('.subtotal').text('').text(data.subtotal);
					$('.total_items').text('').text(data.total_items);
					$('.gsttotal').text('').text(data.total_gst);
					$('.shippingfee').text('').text(data.shipping_cost);
					$('.order-total').text('').text(data.grand_total);
					$('.cart-qty').text('').text(data.total_items);
				}
			}

		});

	});


	// checkout
	$('.order-checkout-submit').click(function(){

		var shippingAddress = $('input[name=shippingAddress]').val();
		var billingAddress = $('input[name=billingAddress]').val();
		var paymentOption = $('input[name=payment]:checked').val();

		swal({
		  // title: "",
		  text: "{{ __('Frontend::frontend.processing') }}",
		  showCancelButton: false,
		  showConfirmButton: false
		});

		$.ajax({

			type: "GET",
			url : '{{ URL::to("frontend/cart/checkout") }}',
			data: {shippingAddress: shippingAddress, billingAddress: billingAddress, paymentOption: paymentOption },
			dataType: "json",
			cache:false,
			success:
			function(data) {
				if(data.status == 'OK') {
					// window.location.reload();
					window.location = data.redirect;
				}

				if(data.status == 'INVALID') {

					swal({
						title: '{{ __("Admin::base.error") }}',
						text: data.errors,
						type: "error",
					});
				}
			}

		});

	});

</script>
@stop()