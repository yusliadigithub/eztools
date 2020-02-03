@extends('layouts.adminLTE.master')

@section('header')
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
			{{ Form::open(['action'=>['\App\Modules\Product\Controllers\ProductController@stockqtyledger',Crypt::encrypt($stock->product_stock_id)], 'method'=>'get', 'class'=>'form-horizontal', 'id'=>'searchform']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::base.year') }}</label>
			  		<div class="col-sm-12 col-md-4">
					  	<select class="form-control input-sm" name="ledgeryear" id="ledgeryear">
					  		<option value="">{{ __('Admin::base.please_select') }}</option>
					  		@if(count($years) > 0)
					  			@foreach($years as $year)
					  			<option value="{{ $year->product_stock_ledger_year }}" {{ (Input::get('ledgeryear') == $year->product_stock_ledger_year) ? 'selected' : '' }}>{{ $year->product_stock_ledger_year }}</option>
					  			@endforeach
					  		@endif
					  	</select>
				  	</div>
			  	</div>
			{{ Form::close() }}
			</div>

			<div class="box box-primary">
		    	<div class="box-header with-border">
		        	<h3 class="box-title">{{ __('Product::product.stockledger').' '.$ledgeryear }}</h3>
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
            			<div class="col-md-6">
            				<div class="form-group">
		                        <label class="col-sm-3 control-label text-right">{{ __('Admin::base.year') }}</label>
		                        <div class="col-sm-7">
		                            <input class="form-control" type="text" value="{{ $ledgeryear }}" disabled>
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
							<th class="column-title" width="30%"><font color="#FFFFFF">{{ __('Admin::base.month') }}</font></th>
							<th class="column-title text-center" width="30%"><font color="#FFFFFF">{{ __('Product::product.quantityin') }}</font></th>
							<th class="column-title text-center" width="30%"><font color="#FFFFFF">{{ __('Product::product.quantityout') }}</font></th>
							<th class="column-title text-center" width="10%"><font color="#FFFFFF">{{ __('Product::product.transaction') }}</font></th>
						</tr>
					</thead>
					<tbody>
						@if($movements != '')
							@foreach($movements->movement as $m)
							<tr>
								<td>{!!  date( 'F', strtotime('2018-'.$m->product_stock_movement_month.'-01') ) !!}</td>
								<td class="text-right">{!! number_format($m->product_stock_movement_quantity_in) !!}</td>
								<td class="text-right">{!! number_format($m->product_stock_movement_quantity_out) !!}</td>
								<td class="text-center">
									@if(count($m->transaction)>0)
									<a data-toggle="tooltip" title="{{ __('Product::product.dailytransaction') }}" href="{!! route('product.stockqtytrans',Crypt::encrypt($m->product_stock_movement_id)) !!}" class="btn btn-xs btn-primary"><i class="fa fa-book"></i></a>
									@else
										<span class="btn btn-xs btn-danger">{{ __('Admin::base.norecord') }}</span>
									@endif
								</td>
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

	$('#ledgeryear').on('change', function(){

		if($(this).val() != ''){
			$('#searchform').submit();
		}

	});

</script>
@stop