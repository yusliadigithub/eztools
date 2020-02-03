@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')


	<div class="box">

		<div class="box box-primary">
	    	<div class="box-header with-border">
	        	<h3 class="box-title">{{ __('Product::product.dailytransaction') }} {!!  date( 'F', strtotime('2018-'.$data->movement->product_stock_movement_month.'-01') ).' '.$data->movement->ledger->product_stock_ledger_year !!}</h3>
	    	</div>
	    	<form class="form-horizontal">
	    	<div class="box-body">
    			<div class="row">
        			<div class="col-md-6">
        				<div class="form-group">
	                        <label class="col-sm-3 control-label text-right">{{ __('Product::product.product') }}</label>
	                        <div class="col-sm-7">
	                            <input class="form-control" type="text" value="{{ $stock->product->product_name }}" disabled>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-sm-3 control-label text-right">{{ __('Product::product.variant') }}</label>
	                        <div class="col-sm-7">
	                            <input class="form-control" type="text" value="{{ $stock->product_stock_description }}" disabled>
	                        </div>
	                    </div>
        			</div>
        			<div class="col-md-6">
        				<div class="form-group">
	                        <label class="col-sm-3 control-label text-right">{{ __('Product::product.quantityleft') }}</label>
	                        <div class="col-sm-4">
	                            <input class="form-control" type="text" value="{{ number_format($stock->product_stock_quantity) }}" disabled>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-sm-3 control-label text-right">{{ __('Admin::base.month') }}</label>
	                        <div class="col-sm-4">
	                            <input class="form-control" type="text" value="{!!  date( 'F', strtotime('2018-'.$data->movement->product_stock_movement_month.'-01') ).' '.$data->movement->ledger->product_stock_ledger_year !!}" disabled>
	                        </div>
	                    </div>
        			</div>
        		</div>
            </div>	
            </form>	
	    </div>

		<div class="box-body">
		 	{{ csrf_field() }}

			<div class="table-responsive">
				<table class="table table-striped jambo_table bulk_action table-bordered">
					<thead>
						<tr class="headings" bgcolor="#172f93">
							<th class="column-title text-center" width="15%"><font color="#FFFFFF">{{ __('Admin::base.date') }}</font></th>
							<th class="column-title text-center" width="20%"><font color="#FFFFFF">{{ __('Admin::base.referenceno') }}</font></th>
							<th class="column-title text-center"><font color="#FFFFFF">{{ __('Admin::base.remark') }}</font></th>
							<th class="column-title text-center" width="15%"><font color="#FFFFFF">{{ __('Product::product.quantityin') }}</font></th>
							<th class="column-title text-center" width="15%"><font color="#FFFFFF">{{ __('Product::product.quantityout') }}</font></th>
						</tr>
					</thead>
					<tbody>
						@if($transactions != '')
						@foreach($transactions as $t)
						<tr>
							<td class="text-center">{!!  date( 'd/m/Y', strtotime($t->created_at) ) !!}</td>
							<td>
								@if($t->product_stock_transaction_model == 'CartModel')
									<a href="{!! url('order/showquotation/'.Crypt::encrypt($t->product_stock_transaction_model_id)) !!}" target="_blank">{!! $t->product_stock_transaction_reference !!}</a>
								@else
									{!! $t->product_stock_transaction_reference !!}
								@endif
							</td>
							<td>{!! $t->product_stock_transaction_remark !!}</td>
							<td class="text-right">{!! number_format($t->product_stock_transaction_quantity_in) !!}</td>
							<td class="text-right">{!! number_format($t->product_stock_transaction_quantity_out) !!}</td>
						</tr>
						@endforeach
						@else
						<tr><td colspan="5">No result(s)</td></tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

@stop

@section('footer')


<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

<script type="text/javascript">

$( document ).ready(function() {

});

</script>
@stop