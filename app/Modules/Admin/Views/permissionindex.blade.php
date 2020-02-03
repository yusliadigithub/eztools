@extends('layouts.adminLTE.master')


@section('content')
<div class="box">
	<div class="box-header with-border">
		<div class="row">
			<div class="pull-left col-sm-12 col-md-6 col-lg-6">
				<a href="javascript:;" data-toggle="modal" data-target="#modal-1" data-backdrop="static" class="pull-left btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> {{ __('Admin::permission.addpermission') }}</a>
			</div>
			<div class="box-tools col-sm-12 col-md-6 col-lg-6">
	            <button data-toggle="collapse" data-target="#searchbox" class="searchbox btn btn-box-tool pull-right" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
			</div>
		</div>
	</div><!-- end box-header -->

	<div class="box-body">
		<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
		  {{ Form::open(['action'=>'\App\Modules\Admin\Controllers\PermissionController@index', 'method'=>'get', 'class'=>'form-horizontal']) }}
		  	<div class="form-group">
		  		<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
		  		<div class="col-sm-12 col-md-2">
				  	<select class="form-control input-sm" name="search">
				  		<option value="permissionname">{{ __('Admin::permission.permissionname') }}</option>
				  		<option value="namespace">Namespace</option>
				  		<option value="controller">Controller</option>
				  		<option value="function">Function</option>
				  	</select>
			  	</div>
			  	<div class="col-sm-12 col-md-6"><input type="text" class="input-sm form-control" value="{{ Input::get('keyword') }}" placeholder="{{ __('Admin::base.keyword') }}" name="keyword"></div>
			  	<div class="col-sm-12 col-md-3 text-center">
			  		<button class="btn btn-sm btn-success"><i class="fa fa-search"></i> {{ __('Admin::base.search') }}</button>
			  		<a href="{{ URL::to('admin/permission') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> {{ __('Admin::base.reset') }}</a>
			  	</div>
		  	</div>
		  {{ Form::close() }}
		</div><!-- end searchbox -->
		<div class="table-responsive">
			<table class="table table-striped jambo_table bulk_action table-bordered">
				<thead>
					<tr class="headings">
						<th class="column-title">{{ __('Admin::permission.permissionname') }}</th>
						<th class="column-title">{{ __('Admin::base.description') }}</th>
						<th class="column-title">Namespace</th>
						<th class="column-title">Controller</th>
						<th class="column-title">Function</th>
						<!--th class="column-title text-center" width="20%">{{ __('Admin::base.lastupdate') }}</th-->
						<th class="column-title text-center" width="10%">{{ __('Admin::base.action') }}</th>
					</tr>
				</thead>
				<tbody>
					@if(count($permissions) > 0)
					@foreach($permissions as $permission)
					<tr>
						<td>{!! $permission->name !!}</td>
						<td>{!! ($permission->description!='') ? $permission->description : 'No Description Available' !!}</td>
						<td>{!! $permission->namespace !!}</td>
						<td>{!! $permission->controller !!}</td>
						<td>{!! $permission->function !!}</td>
						<td class="text-center">
							<a data-backdrop="static" data-id="{{ $permission->id }}" data-name="{{ $permission->name }}" data-namespace="{{ $permission->namespace }}" data-controller="{{ $permission->controller }}" data-function="{{ $permission->function }}" data-desc="{{ $permission->description }}" class="btn btn-info btn-xs editbtn" href="javascript:;"><i class="fas fa-edit"></i></a>
						</td>
					</tr>
					@endforeach
					@else
					<tr><td colspan="4">No result(s)</td></tr>
					@endif
				</tbody>
			</table>
			{{ $permissions->links() }}
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
				<h4 class="modal-title">{!! trans('Admin::permission.addpermission') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			        <!--span class="close" data-dismiss="alert">×</span-->
			    </div>
			@endif

			{!! Form::open(['action'=>'\App\Modules\Admin\Controllers\PermissionController@store', 'method'=>'post', 'class'=>'form-horizontal form-label-left permissionform']) !!}
			<div class="modal-body">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::permission.permissionname') }} *</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control" type="text" value="{{ old('name') }}" name="name" placeholder="{{ __('Admin::permission.permissionname') }}" />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Namespace *</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control" type="text" value="{{ old('name') }}" name="namespace" placeholder="Namespace" />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Controller *</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control" type="text" value="{{ old('controller') }}" name="controller" placeholder="Controller Name" />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">Function *</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control" type="text" value="{{ old('function') }}" name="function" placeholder="Function Name" />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::base.description') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12"><textarea name="description" value="{{ old('description') }}" class="form-control" placeholder="{{ __('Admin::base.description') }}"></textarea></div>					
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

<script type="text/javascript">

	@if( Session::get('modal') )
		$('#modal-1').modal( {backdrop: 'static', keyboard: false} ); 
	@endif
	
	$('.editbtn').on('click', function() {

		var permsid = $(this).data('id');
		var permsname = $(this).data('name');
		var permsnamespace = $(this).data('namespace');
		var permsdesc = $(this).data('desc');
		var permsfunction = $(this).data('function');
		var persmcontroller = $(this).data('controller');

		$('#modal-1').modal('show');
		
		$("[name='name']").val(permsname);
		$("[name='namespace']").val(permsnamespace);
		$("[name='controller']").val(persmcontroller);
		$("[name='function']").val(permsfunction);
		$("[name='description']").val(permsdesc);

		$('.permissionform').attr('action', '{{ URL::to("admin/permission/update") }}/'+$(this).data('id') );

	});

</script>


@stop