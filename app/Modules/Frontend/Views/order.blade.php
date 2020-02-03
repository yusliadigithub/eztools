@extends('layouts.frontend.electro')


@section('breadcrumb')
<h3 class="breadcrumb-header">{{ __('Frontend::frontend.order_list') }}</h3>
@stop()

@section('content')
<div class="container">
	
	@if(count($orders) > 0)
	<table class="table table-responsive table-striped">
		<thead>
			<tr>
				<th width="15%" scope="col">{{ __('Frontend::frontend.payment_status') }}</th>
				<th scope="col">{{ __('Frontend::frontend.order_no') }}</th>
				<th scope="col">{{ __('Frontend::frontend.shipping_status') }}</th>				
				<th class="text-right" scope="col">{{ __('Frontend::frontend.total_gst') }}</th>				
				<th class="text-right" scope="col">{{ ucfirst(strtolower(__('Frontend::frontend.total'))) }}</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($orders as $order)
			<tr>
				<td>{!! ($order->cart_payment_status == 1) ? '<span class="badge badge-success">'.__('Frontend::frontend.paid').'</span>' : '<span class="badge badge-warning">'.__('Frontend::frontend.unpaid').'</span>' !!}</td>
				<td><a href="{{ route('order.printdoc',[Crypt::encrypt($order->cart_id),'inv']) }}">{{ $order->cart_orderno }}</a></td>
				<td>
					{!! ($order->cart_isshipping == 1) ? '<span class="badge badge-success"><i class="fa fa-check-circle"></i></span>' : 'N/A' !!}
					{{ ($order->cart_courrier_status == 1) ? $order->cart_courrierno : '' }}
				</td>
				<td class="text-right">{{ Globe::moneyFormat($order->cart_gst_amount) }}</td>
				<td class="text-right">{{ Globe::moneyFormat($order->cart_final_amount) }}</td>
				<td class="text-right"><a href="{{ route('frontend.order.detail', encrypt($order->cart_id) ) }}" class=""><i class="fa fa-file"></i></a></td>
			</tr>
			@endforeach
		</tbody>
	</table>
	@else
		<center><h1><i class="fas fa-file"></i></h1>{{ __('Frontend::frontend.no_order_list') }}</center>
	@endif

</div>
@stop()