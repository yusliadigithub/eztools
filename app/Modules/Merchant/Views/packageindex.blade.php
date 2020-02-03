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
					@if(Auth::user()->can('merchant.package.store'))
					<a class="btn btn-sm btn-primary adddata"><i class="fa fa-plus-circle"></i> {{ __('Merchant::package.addpackage') }}</a>
					@endif		
				</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		<div class="box-body">
		 	<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			{{ Form::open(['action'=>'\App\Modules\Merchant\Controllers\PackageController@index', 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="merchant_package_name">{{ __('Merchant::package.packagename') }}</option>
					  	</select>
				  	</div>
				  	<div class="col-sm-12 col-md-6"><input type="text" class="input-sm form-control" value="{{ Input::get('keyword') }}" placeholder="{{ __('Admin::base.keyword') }}" name="keyword"></div>
				  	<div class="col-sm-12 col-md-3 text-center">
				  		<button class="btn btn-sm btn-success"><i class="fa fa-search"></i> {{ __('Admin::base.search') }}</button>
				  		<a href="{{ URL::to('Merchant/type') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> {{ __('Admin::base.reset') }}</a>
				  	</div>
			  	</div>
			{{ Form::close() }}
			</div>
		 	{{ csrf_field() }}
		 	
			<div class="table-responsive">
				<table class="table table-striped jambo_table bulk_action table-bordered">
					<thead>
						<tr class="headings">
							<th class="column-title" width="15%">{{ __('Merchant::package.packagename') }}</th>
							<th class="column-title text-center">{{ __('Admin::base.description') }}</th>
							<th class="column-title text-center">{{ __('Merchant::package.package') }}</th>
							<th class="column-title text-center" width="11%">{{ __('Merchant::package.packageprice') }}</th>
							<th class="column-title text-center" width="11%">{{ __('Merchant::package.packagerenewalprice') }}</th>
							<th class="column-title text-center" width="12%">{{ __('Admin::base.createddate') }}</th>
							<th class="column-title text-center" width="10%">Status</th>
							<th class="column-title text-center" width="13%">{{ __('Admin::base.action') }}</th>
						</tr>
					</thead>
					<tbody>
						@if(count($types) > 0)
						@foreach($types as $type)
						<tr>
							<td>{!! $type->merchant_package_name !!}</td>
							<td>{!! $type->merchant_package_description !!}</td>
							<td>
								@if(count($type->subpackage)>0)
									@foreach($type->subpackage as $key=>$sp)
										@if($key==0)
											{!! '' !!}
										@else
											{!! '<br>' !!}
										@endif
										{!! '<i class="fa fa-dot-circle"></i>  '.$sp->subpackage->merchant_subpackage_desc !!}
									@endforeach
								@endif
								<br><i class="fa fa-dot-circle"></i>  {{ __('Merchant::package.maxproduct') }}: {!! ($type->merchant_package_max_product==0) ? __('Admin::base.unlimited') : number_format($type->merchant_package_max_product) !!}
							</td>
							<td class="text-center">{!! 'MYR '.number_format($type->merchant_package_price,2) !!}</td>
							<td class="text-center">{!! 'MYR '.number_format($type->merchant_package_renew_price,2) !!}</td>
							<td class="text-center">{!! date( 'd F Y, h:i:s', strtotime($type->created_at)) !!}</td>
							<td class="text-center">
								{!! ($type->merchant_package_status=='1') ? '<span class="label label-success">'.__('Admin::base.active').'</span>' : '<span class="label label-danger">'.__('Admin::base.inactive').'</span>' !!}
							</td>
							<td class="text-center">
								<a data-toggle="tooltip" title="{{ __('Merchant::package.editpackage') }}" data-id="{!! $type->merchant_package_id !!}" class="btn btn-xs btn-info infodata"><i class="fa fa-edit"></i></a>
								<a data-toggle="tooltip" title="{{ __('Admin::user.disable') }}" class="btn btn-xs btn-primary {{ $type->merchant_package_status==0 ? 'disabled' : '' }} enabledata" data-askmsg="{{ __('Admin::base.askdisable') }}" value="{{ route('merchant.package.disable', $type->merchant_package_id) }}"><i class="fa fa-minus-circle"></i></a>
								@if($type->merchant_package_status == 0)
								<a data-toggle="tooltip" title="{{ __('Admin::user.enable') }}" class="btn btn-xs btn-success enabledata" data-askmsg="{{ __('Admin::base.askenable') }}" value="{{ route('merchant.package.enable', $type->merchant_package_id) }}"><i class="fa fa-check-circle"></i></a>
								@endif
								<a class="btn btn-xs btn-danger deletedata" value="{{ route('merchant.package.delete',$type->merchant_package_id) }}"><i class="fa fa-times-circle"></i></a>
							</td>
						</tr>
						@endforeach
						@else
						<tr><td colspan="9">No result(s)</td></tr>
						@endif
					</tbody>
				</table>
				{{ $types->appends(Request::only('search'))->appends(Request::only('keyword'))->links() }}
			</div>
		</div>
	</div>

@stop

@section('footer')
<div class="modal modal-info fade" id="modal-1">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{!! trans('Merchant::package.addpackage') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			        <!--span class="close" data-dismiss="alert">×</span-->
			    </div>
			@endif

			{!! Form::open(['action'=>'\App\Modules\Merchant\Controllers\PackageController@store', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form1']) !!}
			<input type="hidden" name="merchant_package_id" id="merchant_package_id" class="modaldata" value="{{ old('merchant_package_id') }}">
			<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Merchant::package.packagename') }}</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<input class="form-control modaldata" type="text" value="{{ old('merchant_package_name') }}" name="merchant_package_name" id="merchant_package_name" placeholder="{{ __('Merchant::package.packagename') }}" />
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::base.description') }}</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<textarea class="form-control modaldata" name="merchant_package_description" id="merchant_package_description" placeholder="{{ __('Admin::base.description') }}">{{ old('merchant_package_description') }}</textarea>
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Merchant::package.maxproduct') }}</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<input class="form-control modaldata" type="number" value="{{ old('merchant_package_max_product') }}" name="merchant_package_max_product" id="merchant_package_max_product" placeholder="{{ __('Merchant::package.ifunlimited') }}" />
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">&nbsp;</label>
						<div class="col-md-6 col-sm-6 col-xs-12 divsubpackage">
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Merchant::package.packageprice') }}</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="input-group">			
			                  	<div class="input-group-addon">MYR</div>
			                  	<input class="form-control modaldata" value="{{ old('merchant_package_price') }}" id="merchant_package_price" type="number" name="merchant_package_price" />
			                </div>
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Merchant::package.packagerenewalprice') }}</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="input-group">			
			                  	<div class="input-group-addon">MYR</div>
			                  	<input class="form-control modaldata" value="{{ old('merchant_package_renew_price') }}" id="merchant_package_renew_price" type="number" name="merchant_package_renew_price" />
			                </div>
						</div>					
					</div>
			</div>
			
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
				<button type="button" class="btn btn-sm btn-success addbtn"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div> 


<link rel="stylesheet" href="{{-- asset('assets/js/zurb-responsive-tables/responsive-tables.css') --}}">

<script type="text/javascript">

	@if( Session::get('modal') )
		$('#modal-1').modal( {backdrop: 'static', keyboard: false} );
		getSubPackage('{{ old("merchant_package_id") }}'); 
	@endif

	function toggleCheck(source) {
    	checkboxes = document.getElementsByTagName('input');
    
    	for(var i=0, l=checkboxes.length; i<l; i++) {
      		checkboxes[i].checked = source.checked;
    	}
  	}

	function cleardata(){

		$('.modaldata').val('');
		$('.divsubpackage').empty();

	}

	function getSubPackage(id){

		$.ajax({
            url: '{{ URL::to("merchant/package/getInfo") }}/'+id,
            type: 'get',
            dataType: 'json',
            success:function(data) {

            	$('#merchant_package_id').val(id);
                $('#merchant_package_name').val(data.package.merchant_package_name); 
                $('#merchant_package_description').val(data.package.merchant_package_description);
                $('#merchant_package_max_product').val(data.package.merchant_package_max_product);
                $('#merchant_package_price').val(data.package.merchant_package_price);
                $('#merchant_package_renew_price').val(data.package.merchant_package_renew_price);
                $('.divsubpackage').append(data.html);
            }
        });

	}

	$('.adddata').on('click', function(){
		cleardata();

		$.ajax({
            url: '{{ URL::to("merchant/package/getSubpackageNew") }}',
            type: 'get',
            dataType: 'json',
            success:function(data) {
            	$('.divsubpackage').append(data);
            }
        });

		$('#modal-1').modal('show');
	});

	$('.infodata').on('click', function(){
		cleardata();
		getSubPackage($(this).data('id'));
		$('#modal-1').modal('show');
	});

	$('.addbtn').on('click', function() {

    	if($('#merchant_package_name').val()=='' || $('#merchant_package_price').val()=='' || $('#merchant_package_renew_price').val()=='' || $('#merchant_package_max_product').val()==''){
    		swal('{{ __("Admin::base.error") }}!', '{{ __("Admin::base.pleasefillupform") }}', 'error');
    		return false;
    	}
        
      	var url = $(this).attr('value');

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

    $('.enabledata').on('click', function() {
  	
  		var url = $(this).attr('value');
  		var askmsg = $(this).data('askmsg');

	  	swal({
		  	title: askmsg,
		  	//text: "{{ __('Admin::base.norevert') }}",
		  	type: 'warning',
		  	showCancelButton: true,
		  	cancelButtonText: '{{ __("Admin::base.cancel") }}',
		  	confirmButtonColor: '#3085d6',
		  	cancelButtonColor: '#d33',
		  	confirmButtonText: '{{ __("Admin::base.yes") }}',
		  
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