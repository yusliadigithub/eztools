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
					@if(Auth::user()->can('merchant.template.store'))
					<a class="btn btn-sm btn-primary adddata"><i class="fa fa-plus-circle"></i> {{ __('Merchant::template.addtemplate') }}</a>
					@endif		
				</div>
				<div class="box-tools col-sm-12 col-md-6 col-lg-6">
		            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
				</div>
			</div>
		</div>
		<div class="box-body">
		 	<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			{{ Form::open(['action'=>'\App\Modules\Merchant\Controllers\TemplateController@index', 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group">
			  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
			  		<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="template_name">{{ __('Merchant::merchant.template') }}</option>
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
							<th class="column-title" width="15%">{{ __('Merchant::template.websitetemplate') }}</th>
							<th class="column-title text-center">{{ __('Admin::base.description') }}</th>
							<th class="column-title text-center">{{ __('Merchant::template.templateurl') }}</th>
							<th class="column-title text-center" width="15%">{{ __('Admin::base.createddate') }}</th>
							<th class="column-title text-center" width="10%">Status</th>
							<th class="column-title text-center" width="15%">{{ __('Admin::base.action') }}</th>
						</tr>
					</thead>
					<tbody>
						@if(count($types) > 0)
						@foreach($types as $type)
						<tr>
							<td>{!! $type->template_name !!}</td>
							<td>{!! $type->template_description !!}</td>
							<td><a href="{!! $type->template_url !!}" target="_blank">{!! $type->template_url !!}</a></td>
							<td class="text-center">{!! date( 'd F Y, h:i:s', strtotime($type->created_at)) !!}</td>
							<td class="text-center">
								{!! ($type->template_status=='1') ? '<span class="label label-success">'.__('Admin::base.active').'</span>' : '<span class="label label-danger">'.__('Admin::base.inactive').'</span>' !!}
							</td>
							<td class="text-center">
								<a data-toggle="tooltip" title="{{ __('Merchant::template.edittemplate') }}" data-id="{!! $type->template_id !!}" class="btn btn-xs btn-info infodata"><i class="fa fa-edit"></i></a>
								<a data-toggle="tooltip" title="{{ __('Admin::user.disable') }}" class="btn btn-xs btn-primary {{ $type->template_status==0 ? 'disabled' : '' }} enabledata" data-askmsg="{{ __('Admin::base.askdisable') }}" value="{{ route('merchant.template.disable', $type->template_id) }}"><i class="fa fa-minus-circle"></i></a>
								@if($type->template_status == 0)
								<a data-toggle="tooltip" title="{{ __('Admin::user.enable') }}" class="btn btn-xs btn-success enabledata" data-askmsg="{{ __('Admin::base.askenable') }}" value="{{ route('merchant.template.enable', $type->template_id) }}"><i class="fa fa-check-circle"></i></a>
								@endif
								<a class="btn btn-xs btn-danger deletedata" value="{{ route('merchant.template.delete',$type->template_id) }}"><i class="fa fa-times-circle"></i></a>
							</td>
						</tr>
						@endforeach
						@else
						<tr><td colspan="7">No result(s)</td></tr>
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
				<h4 class="modal-title">{!! trans('Merchant::template.addtemplate') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			        <!--span class="close" data-dismiss="alert">×</span-->
			    </div>
			@endif

			{!! Form::open(['action'=>'\App\Modules\Merchant\Controllers\TemplateController@store', 'method'=>'post', 'class'=>'form-horizontal form-label-left','id'=>'form1']) !!}
			<input type="hidden" name="template_id" id="template_id" class="modaldata" value="{{ old('template_id') }}">
			<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Merchant::template.templatename') }}</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<input class="form-control modaldata" type="text" value="{{ old('template_name') }}" name="template_name" id="template_name" placeholder="{{ __('Merchant::template.templatename') }}" />
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::base.description') }}</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<textarea class="form-control modaldata" name="template_description" id="template_description" placeholder="{{ __('Admin::base.description') }}">{{ old('template_description') }}</textarea>
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Merchant::template.templateurl') }}</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<input class="form-control modaldata" type="text" value="{{ old('template_url') }}" name="template_url" id="template_url" placeholder="{{ __('Merchant::template.templateurl') }}" />
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Merchant::template.templateprice') }}</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="input-group">			
			                  	<div class="input-group-addon">MYR</div>
			                  	<input class="form-control modaldata" value="{{ old('template_price') }}" id="template_price" type="number" name="template_price" />
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
	@endif

	$('.adddata').on('click', function(){
		$('.modaldata').val('');
		$('#modal-1').modal('show');
	});

	$('.infodata').on('click', function(){
		$('.modaldata').val('');
		var id = $(this).data('id');

		$.ajax({
            url: '{{ URL::to("merchant/template/getInfo") }}/'+id,
            type: 'get',
            dataType: 'json',
            success:function(data) {

            	$('#template_id').val(data.template_id);
                $('#template_name').val(data.template_name);
                $('#template_description').val(data.template_description);
                $('#template_url').val(data.template_url);
                $('#template_price').val(data.template_price);

            }
        });

		$('#modal-1').modal('show');
	});

	$('.addbtn').on('click', function() {

    	if($('#template_price').val()=='' || $('#template_name').val()=='' || $('#template_url').val()==''){
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