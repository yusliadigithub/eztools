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
		 	<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			{{ Form::open(['action'=>['\App\Modules\Product\Controllers\ProductController@productmovement',Crypt::encrypt($merchantid)], 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="product_name">{{ __('Product::product.productname') }}</option>
					  	</select>
				  	</div>
				  	<div class="col-sm-12 col-md-6"><input type="text" class="input-sm form-control" value="{{ Input::get('keyword') }}" placeholder="{{ __('Admin::base.keyword') }}" name="keyword"></div>
				  	<div class="col-sm-12 col-md-3 text-center">
				  		<button class="btn btn-sm btn-success"><i class="fa fa-search"></i> {{ __('Admin::base.search') }}</button>
				  		<a href="{{ URL::to('product/type') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> {{ __('Admin::base.reset') }}</a>
				  	</div>
			  	</div>
			{{ Form::close() }}
			</div>
		 	{{ csrf_field() }}

			<div class="table-responsive">
				<table class="table table-striped jambo_table bulk_action table-bordered">
					<thead>
						<tr class="headings">
							<th class="column-title">{{ __('Product::product.product') }}</th>
							<th class="column-title text-center" width="18%">{{ __('Product::product.before') }}</th>
							<th class="column-title text-center" width="18%">{{ __('Product::product.movement') }}</th>
							<th class="column-title text-center" width="18%">{{ __('Product::product.after') }}</th>
							<th class="column-title text-center" width="10%">{{ __('Admin::base.action') }}</th>
						</tr>
					</thead>
					<tbody>
						@if(count($types) > 0)
						@foreach($types as $type)
						<?php 
							$quantity = Globe::stockmovementdisplay($type->product_id,'product');
						?>
						<tr>
							<td>{!! $type->product_name !!}</td>
							<td class="text-center">{!! $quantity['before'] !!}</td>
							<td class="text-center">{!! $quantity['movement'] !!}</td>
							<td class="text-center">{!! $quantity['after'] !!}</td>
							<td class="text-center">
								<a data-toggle="tooltip" title="{{ __('Product::product.stockmovementbyproductvariant') }}" href="{{ route('product.productvariantmovement',Crypt::encrypt($type->product_id)) }}" class="btn btn-xs btn-warning"><i class="fa fa-chart-line"></i></a>
							</td>
						</tr>
						@endforeach
						@else
						<tr><td colspan="8">No result(s)</td></tr>
						@endif
					</tbody>
				</table>
				{{ $types->appends(Request::only('search'))->appends(Request::only('keyword'))->links() }}
			</div>
		</div>
	</div>

@stop

@section('footer')

<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

<script type="text/javascript">

</script>
@stop