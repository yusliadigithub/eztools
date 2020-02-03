@extends('layouts.adminLTE.master')


@section('content')
<div class="box">
	<div class="box-header with-border">
		<div class="row">
			<div class="pull-left col-sm-12 col-md-6 col-lg-6">
				<!--a href="javascript:;" data-toggle="modal" data-target="#modal-1" data-backdrop="static" class="pull-left btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> {{ __('Admin::role.addrole') }}</a-->		
			</div>
			<div class="box-tools col-sm-12 col-md-6 col-lg-6">
	            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
			</div>
		</div>
	</div><!-- end box-header -->

	<div class="box-body">
		<div id="searchbox" class="collapse {{ Input::has('keyword') ? 'in' : '' }} well">
			{{ Form::open(['action'=>'\App\Modules\Admin\Controllers\RoleController@index', 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group text-center">
			  		
				  	<div class="col-sm-12 col-md-6"><input type="text" class="input-sm form-control" value="{{ Input::get('keyword') }}" placeholder="{{ __('Admin::base.keyword') }}" name="keyword"></div>
				  	<div class="col-sm-12 col-md-3 text-center">
				  		<button class="btn btn-sm btn-success"><i class="fa fa-search"></i> {{ __('Admin::base.search') }}</button>
				  		<a href="{{ route('admin.roles') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> {{ __('Admin::base.reset') }}</a>
				  	</div>
			  	</div>
			  {{ Form::close() }}
		</div><!-- end searchbox -->
		
		<div class="table-responsive">
			<table class="table table-striped jambo_table bulk_action table-bordered">
				<thead>
					<tr class="headings">
						<th class="column-title" width="20%">{{ __('Admin::role.rolename') }}</th>
						<th class="column-title">{{ __('Admin::role.roledesc') }}</th>
						<th class="column-title text-center" width="20%">{{ __('Admin::base.lastupdate') }}</th>
						<th class="column-title text-center" width="15%">{{ __('Admin::base.action') }}</th>
					</tr>
				</thead>
				<tbody>
					@if(count($roles) > 0)
					@foreach($roles as $role)
					<tr>
						<td>{!! $role->name !!}</td>
						<td>{!! ($role->description!='') ? $role->description : 'No Description Available' !!}</td>
						<td class="text-center">{!! date( 'd F Y, h:i:s', strtotime($role->updated_at)) !!}</td>
						<td class="text-center">
							<a class="btn btn-xs btn-info editbtn" data-toggle="tooltip" title="{{ __('Admin::role.rolepermission') }}" data-id="{!! $role->id !!}" data-desc="{!! $role->name !!}"><i class="fa fa-edit "></i></a>
						</td>
					</tr>
					@endforeach
					@else
					<tr><td colspan="4">No result(s)</td></tr>
					@endif
				</tbody>
			</table>
		</div>

	</div><!-- end box-body -->

	

</div>
@stop


@section('footer')
<div class="modal fade" id="modal-1">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{!! trans('Admin::role.addrole') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			        <!--span class="close" data-dismiss="alert">×</span-->
			    </div>
			@endif

			{!! Form::open(['action'=>'\App\Modules\Admin\Controllers\RoleController@store', 'method'=>'post', 'class'=>'form-horizontal form-label-left']) !!}
			<div class="modal-body">
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::role.rolename') }} *</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<input class="form-control" type="text" value="{{ old('rolename') }}" name="rolename" placeholder="{{ __('Admin::role.rolename') }}" />
						</div>					
					</div>
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::role.roledesc') }}</label>
						<div class="col-md-7 col-sm-7 col-xs-12">
							<textarea name="description" value="{{ old('description') }}" class="form-control" placeholder="{{ __('Admin::role.roledesc') }}"></textarea>
						</div>					
					</div>
			</div>
			
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
				<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

<div class="modal fade" id="modal-2">
	<div class="modal-dialog" id="modaldialog">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{!! trans('Admin::permission.permission') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			        <!--span class="close" data-dismiss="alert">×</span-->
			    </div>
			@endif

			{!! Form::open(['action'=>'\App\Modules\Admin\Controllers\RoleController@storepermission', 'method'=>'post', 'class'=>'form-horizontal form-label-left', 'id'=>'rolepermissionform']) !!}
			<div class="modal-body" id="displaydiv">
			</div>
			
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-sm btn-default" data-dismiss="modal">{{ __('Admin::base.close') }}</a>
				<button type="button" class="btn btn-sm btn-success savebtn"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div> 

<script type="text/javascript">

	$(document).ready(function() {

		$('.editbtn').on('click', function() {

			var id = $(this).data('id');

			$('#displaydiv').empty();

			$.ajax({
                url: '{{ URL::to("admin/permission/getRolePermission") }}/'+id,
                type: 'get',
                dataType: 'json',
                success:function(data) {

                	$('#displaydiv').append(data.html);
                	$('#modaldialog').attr('style','width:'+data.width+'%;');

                }
            });

            $('#modal-2').modal('show');

		});

		//submiteditpermission
		$('.savebtn').on('click', function() {
    
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
	                $("#rolepermissionform").submit();
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

	});

	function toggleCheck(source) {
		checkboxes = document.getElementsByTagName('input');

		for(var i=0, l=checkboxes.length; i<l; i++) {
			checkboxes[i].checked = source.checked;
		}
	}

</script>
@stop