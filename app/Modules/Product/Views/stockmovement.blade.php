@extends('layouts.adminLTE.master')

@section('header')
<script src="{!! asset('js/daterangepicker.min.js') !!}"></script>
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')


	<div class="box">
		<div class="box-header with-border">
			<div class="row">
				<div class="pull-left col-sm-12 col-md-6 col-lg-6">
					&nbsp;	
				</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		<div class="box-body">
		 	<div id="searchbox" class="collapse in well">
			{{ Form::open(['action'=>['\App\Modules\Product\Controllers\ProductController@stockmovement',Crypt::encrypt($stock->product_stock_id)], 'method'=>'get', 'class'=>'form-horizontal', 'id'=>'searchform']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-2 text-right">{{ __('Product::product.daterange') }}</label>
			  		<div class="col-sm-12 col-md-4">
						<div class="input-group">		
							<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
							<input class="form-control pull-right" id="daterangeinput" name="daterangeinput" type="text" value="{{ (Input::has('daterangeinput')) ? Input::get('daterangeinput') : '' }}" readonly>
						</div>
				  	</div>
			  	</div>
			{{ Form::close() }}
			</div>

			<div class="box box-primary">
		    	<div class="box-header with-border">
		        	<h3 class="box-title">{{ __('Product::product.stockmovement') }} ({{ $daterangestr }})</h3>
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
		                        <div class="col-sm-7">
		                            <input class="form-control" type="text" value="{{ number_format($stock->product_stock_quantity) }}" disabled>
		                        </div>
		                    </div>
            			</div>
            		</div>
                </div>	
                </form>	
		    </div>

		 	{{ csrf_field() }}

			<div class="table-responsive">
				<table class="table table-striped jambo_table bulk_action table-bordered">
					<thead>
						<tr class="headings" bgcolor="#172f93">
							<th class="column-title text-center" rowspan="2" width="15%"><font color="#FFFFFF">{{ __('Admin::base.date') }}</font></th>
							<th class="column-title text-center" rowspan="2" width="15%"><font color="#FFFFFF">{{ __('Admin::base.referenceno') }}</font></th>
							<th class="column-title text-center" rowspan="2"><font color="#FFFFFF">{{ __('Admin::base.remark') }}</font></th>
							<th class="column-title text-center" colspan="3"><font color="#FFFFFF">{{ __('Product::product.quantity') }}</font></th>
						</tr>
						<tr class="headings" bgcolor="#172f93">
							<th class="column-title text-center" width="15%"><font color="#FFFFFF">{{ __('Product::product.before') }}</font></th>
							<th class="column-title text-center" width="15%"><font color="#FFFFFF">{{ __('Product::product.movement') }}</font></th>
							<th class="column-title text-center" width="15%"><font color="#FFFFFF">{{ __('Product::product.after') }}</font></th>
						</tr>
					</thead>
					<tbody>
						@if($movements != '')
							@foreach($movements as $m)
							<tr>
								<td class="text-center">{!!  date( 'd/m/Y H:i:s', strtotime($m->created_at) ) !!}</td>
								<td class="text-center">
									@if($m->product_stock_quantity_movement_model == 'CartModel')
										<a href="{!! url('order/showquotation/'.Crypt::encrypt($m->product_stock_quantity_movement_model_id)) !!}" target="_blank">{!! $m->product_stock_quantity_movement_reference !!}</a>
									@else
										{!! $m->product_stock_quantity_movement_reference !!}
									@endif
								</td>
								<td>{!! $m->product_stock_quantity_movement_remark !!}</td>
								<td class="text-right">{!! number_format($m->product_stock_quantity_movement_before) !!}</td>
								<td class="text-right">{!! number_format($m->product_stock_quantity_movement_movement) !!}</td>
								<td class="text-right">{!! number_format($m->product_stock_quantity_movement_after) !!}</td>
							</tr>
							@endforeach
						@else
						<tr><td colspan="6">No result(s)</td></tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

@stop

@section('footer')

<link rel="stylesheet" type="text/css" href="{!! asset('css/daterangepicker.css') !!}" />
<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

<script type="text/javascript">

$( document ).ready(function() {
	//$('input[name="daterangeinput"]').daterangepicker();
	$('input[name="daterangeinput"]').daterangepicker({
		opens: 'left'
		}, function(start, end, label) {
		console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
	});
});

	$('#daterangeinput').on('change', function(){

		if($(this).val() != ''){
			$('#searchform').submit();
		}

	});

</script>
@stop