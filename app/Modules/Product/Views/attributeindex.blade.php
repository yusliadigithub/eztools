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
					@if(Auth::user()->can('product.attribute.store'))
					<a class="btn btn-sm btn-primary adddata"><i class="fa fa-plus-circle"></i> {{ __('Product::product.addvariant') }}</a>
					@endif		
				</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		<div class="box-body">
		 	<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			{{ Form::open(['action'=>'\App\Modules\Product\Controllers\AttributeController@index', 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="attribute_desc">{{ __('Product::product.variant') }}</option>
					  	</select>
				  	</div>
				  	<div class="col-sm-12 col-md-6"><input type="text" class="input-sm form-control" value="{{ Input::get('keyword') }}" placeholder="{{ __('Admin::base.keyword') }}" name="keyword"></div>
				  	<div class="col-sm-12 col-md-3 text-center">
				  		<button class="btn btn-sm btn-success"><i class="fa fa-search"></i> {{ __('Admin::base.search') }}</button>
				  		<a href="{{ URL::to('product/attribute') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> {{ __('Admin::base.reset') }}</a>
				  	</div>
			  	</div>
			{{ Form::close() }}
			</div>
		 	{{ csrf_field() }}

			<div class="table-responsive">
				<table class="table table-striped jambo_table bulk_action table-bordered">
					<thead>
						<tr class="headings">
							<th class="column-title">{{ __('Product::product.variant') }}</th>
							<th class="column-title text-center">{{ __('Product::product.value') }}</th>
							<th class="column-title text-center">{{ __('Product::product.merchant') }}</th>
							<th class="column-title text-center" width="15%">{{ __('Admin::base.createddate') }}</th>
							<th class="column-title text-center" width="10%">{{ __('Admin::base.action') }}</th>
						</tr>
					</thead>
					<tbody>
						@if(count($types) > 0)
						@foreach($types as $type)
						<tr>
							<td>{!! $type->attribute_desc !!}</td>
							<td>
								@if(count($type->value)>0)
									@foreach($type->value as $key=>$value)
										{!! ($key==0) ? '' : '<br>' !!}
										{!! '<i class="fa fa-dot-circle"></i>  '.$value->attribute_value_desc !!}
									@endforeach
								@else
								-
								@endif
							</td>
							<td>{!! $type->merchant->merchant_name !!}</td>
							<td class="text-center">{!! date( 'd F Y, h:i:s', strtotime($type->created_at)) !!}</td>
							<td class="text-center">
								<a data-toggle="tooltip" title="{{ __('Product::product.editvariant') }}" data-id="{!! $type->attribute_id !!}" data-desc="{!! $type->attribute_desc !!}" data-mid="{!! $type->merchant_id !!}" class="btn btn-xs btn-info infodata"><i class="fa fa-edit"></i></a>
								@if(Auth::user()->can('product.attribute.delete'))
								<a class="btn btn-xs btn-danger deletedata" value="{{ route('product.attribute.delete',$type->attribute_id) }}"><i class="fa fa-times-circle"></i></a>
								@endif
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
<div class="modal modal-default fade" id="modal-1">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{!! trans('Product::product.addvariant') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			        <!--span class="close" data-dismiss="alert">×</span-->
			    </div>
			@endif

			{!! Form::open(['action'=>'\App\Modules\Product\Controllers\AttributeController@store', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form1', 'files'=>true]) !!}
			<input type="hidden" name="attribute_id" id="attribute_id" class="modaldata" value="">
			<input type="hidden" name="merchant_id" id="merchant_id" value="{{ $merchants->merchant_id }}">
			<div class="modal-body">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.variant') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control modaldata" type="text" value="" name="attribute_desc" id="attribute_desc" placeholder="{{ __('Product::product.attribute') }}" />
					</div>					
				</div>
				<div class="form-group valuediv">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Product::product.value') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<table class="table">
	                        <tbody class="valuebody"></tbody>
	                    </table>
					</div>					
				</div>
			</div>
			
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
				@if(Auth::user()->can('product.attribute.store'))
				<button type="button" class="btn btn-sm btn-success addbtn"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
				@endif
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div> 


<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

<script type="text/javascript">

	@if( Session::get('modal') )
		$('#modal-1').modal( {backdrop: 'static', keyboard: false} ); 
	@endif

	$(document).on('click', '.addrow', function() {

        $('.valuebody').append('<tr><td><input class="form-control" type="text" name="attvalue[]" value=""></td><td width="30%"><a class="btn btn-xs btn-danger removerow"><i class="fa fa-times-circle"></i></a></td></tr>');

    });

	$(document).on('click', '.removerow', function() {
        $(this).parent().parent().remove();
    });

	$('.adddata').on('click', function(){
		$('.modaldata').val('');
		$('.valuebody').empty();

		$('.valuebody').append('<tr><td><input class="form-control" type="text" name="attvalue[]" value=""></td><td width="30%"><a class="btn btn-xs btn-primary addrow"><i class="fa fa-plus-circle"></i></a></td></tr>');

		$('#modal-1').modal('show');
	});

	$('.infodata').on('click', function(){

		$('.modaldata').val('');  
		$('.valuebody').empty();  
		$('#attribute_id').val($(this).data('id'));
		$('#attribute_desc').val($(this).data('desc'));
		$('#merchant_id').val($(this).data('mid'));

		var id = $(this).data('id');

		if(id!=''){
			$.ajax({
	            url: '{{ URL::to("product/attribute/getInfo") }}/'+id,
	            type: 'get',
	            dataType: 'json',
	            success:function(data) {
	            	$('.valuebody').append(data);
	            }
	        });
	    }

		$('#modal-1').modal('show');

	});

	$('.addbtn').on('click', function() {

    	if($('#attribute_desc').val()=='' || $('#merchant_id').val()==''){
    		swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
    		return false;
    	}

      	swal({
	        title: '{{ __("Admin::base.confirmsubmission") }}',
	        //text: "{{ __('Admin::base.inadjustable') }}",
	        type: 'warning',
	        showCancelButton: true,
	        cancelButtonText: '{{ __("Admin::base.cancel") }}',
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: '{{ __("Admin::base.yes") }}',
	      
	        preConfirm: function() {
	            return new Promise(function(resolve) {

	                $("#form1").submit();

	            });
	        },

        }).then(function () {
            swal(
              '{{ __("Admin::base.success") }}!',
              '',
              'success'
            )
        });

    });

  	$('.deletedata').on('click', function() {
  	
  		var url = $(this).attr('value');

	  	swal({
		  	title: '{{ __("Admin::base.areyousure") }}',
		  	text: "{{ __('Admin::base.norevert') }}",
		  	type: 'warning',
		  	showCancelButton: true,
		  	cancelButtonText: '{{ __("Admin::base.cancel") }}',
		  	confirmButtonColor: '#3085d6',
		  	cancelButtonColor: '#d33',
		  	confirmButtonText: '{{ __("Admin::base.yesdeleteit") }}',
		  
		  	preConfirm: function() {
     			return new Promise(function(resolve) {

     			 window.location.href = url;

     			});
      		},

		}).then(function () {
		  	swal(
			    '{{ __("Admin::base.deleted") }}!',
			    '{{ __("Admin::base.deletedtext") }}.',
			    'success'
		  	)
		});

  	});

</script>
@stop