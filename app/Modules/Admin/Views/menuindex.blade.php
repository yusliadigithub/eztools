@extends('layouts.adminLTE.master')


@section('content')
<div class="box">
	<div class="box-header with-border">
		<div class="row">
			<div class="pull-left col-sm-12 col-md-6 col-lg-6">
				@if(Auth()->user()->can('admin.menus.create'))
				<a href="javascript:;" data-toggle="modal" data-target="#modal-1" data-backdrop="static" class="btn btn-sm btn-primary"><i class="fa fa-plus-circle"></i> {{ __('Admin::menu.add_menu') }}</a>
				@endif
			</div>
			<div class="box-tools col-sm-12 col-md-6 col-lg-6">
	            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
			</div>
		</div>
	</div><!-- end box-header -->

	<div class="box-body">
		<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			{{ Form::open(['action'=>'\App\Modules\Admin\Controllers\MenuController@index', 'method'=>'get', 'class'=>'form-horizontal']) }}
			  	<div class="form-group text-center">
			  		
				  	<div class="col-sm-12 col-md-6"><input type="text" class="input-sm form-control" value="{{ Input::get('keyword') }}" placeholder="{{ __('Admin::base.keyword') }}" name="keyword"></div>
				  	<div class="col-sm-12 col-md-3 text-center">
				  		<button class="btn btn-sm btn-success"><i class="fa fa-search"></i> {{ __('Admin::base.search') }}</button>
				  		<a href="{{ route('admin.menus') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> {{ __('Admin::base.reset') }}</a>
				  	</div>
			  	</div>
			  {{ Form::close() }}
		</div><!-- end searchbox -->

		<div class="table-responsive">
			<table class="table table-striped jambo_table bulk_action table-bordered">
				<thead>
					<tr class="headings">
						<th class="column-title" width="5%">ID</th>
						<th class="column-title" width="20%">{{ __('Admin::menu.menu_name') }}</th>
						<th class="column-title">{{ __('Admin::menu.menu_trans') }}</th>
						<th class="column-title">{{ __('Admin::menu.menu_url') }}</th>
						<th class="column-title text-center" width="15%">{{ __('Admin::base.lastupdate') }}</th>
						<th class="column-title text-center" width="10%">Status</th>
						<th class="column-title text-center" width="10%">{{ __('Admin::base.action') }}</th>
					</tr>
				</thead>
				<tbody>
					@if(count($menus) > 0)
					@foreach($menus as $menu)
					<tr>
						<td>{!! ($menu->parent_id!=0) ? '-' : $menu->menu_id !!}</td>
						<td>{!! $menu->menu_name !!}</td>
						<td>{!! $menu->menu_trans !!}</td>
						<td>{!! $menu->menu_url !!}</td>
						<td class="text-center">{!! date( 'd F Y, h:i:s', strtotime($menu->updated_at) ) !!}</td>
						<td class="text-center">
								{!! ($menu->menu_status=='1') ? '<span class="label label-success">'.__('Admin::base.active').'</span>' : '<span class="label label-danger">'.__('Admin::base.inactive').'</span>' !!}</td>
						<td class="text-center">
							@if(Auth()->user()->can('admin.menus.edit'))
							<a class="btn btn-xs btn-info editbtn" data-id="{!! $menu->menu_id !!}" data-name="{!! $menu->menu_name !!}" data-desc="{{ $menu->menu_desc }}" data-prefix="{{ $menu->menu_trans }}" data-icon="{{ $menu->menu_icon }}" data-sequence="{{ $menu->menu_sort }}" data-parent="{{ $menu->parent_id }}" data-url="{{ $menu->menu_url }}"><i class="fa fa-edit "></i></a>
							@endif
							<a data-toggle="tooltip" title="{{ __('Admin::user.disable') }}" data-askmsg="{{ __('Admin::base.askdisable') }}" class="btn btn-xs btn-primary enabledata {{ $menu->menu_status==0 ? 'disabled' : '' }}" value="{{ route('admin.menus.disable', $menu->menu_id) }}"><i class="fa fa-minus-circle"></i></a>
							@if($menu->menu_status == 0)
							<a data-toggle="tooltip" title="{{ __('Admin::user.enable') }}" data-askmsg="{{ __('Admin::base.askenable') }}" class="btn btn-xs btn-success enabledata" value="{{ route('admin.menus.enable', $menu->menu_id) }}"><i class="fa fa-check-circle"></i></a>
							@endif
							@if(Auth()->user()->can('admin.menus.delete'))
							<a class="btn btn-xs btn-danger delbtn" data-id="{!! $menu->menu_id !!}"><i class="fa fa-trash "></i></a>
							@endif
						</td>
					</tr>
					@endforeach
					@else
					<tr><td colspan="4">No result(s)</td></tr>
					@endif
				</tbody>
			</table>
			{{ $menus->appends(Request::all())->links() }}
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
				<h4 class="modal-title">{!! trans('Admin::menu.add_menu') !!}</h4>
			</div>

			@if (session('flash_error'))
			    <div class="alert alert-block alert-error fade in">
			        <strong>{{ __('Admin::base.error') }}!</strong>
			        {!! session('flash_error') !!}
			        <!--span class="close" data-dismiss="alert">×</span-->
			    </div>
			@endif

			{!! Form::open(['action'=>'\App\Modules\Admin\Controllers\MenuController@store', 'method'=>'post', 'class'=>'form-horizontal form-label-left menuform']) !!}
			<div class="modal-body">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::menu.menu_name') }} *</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control" type="text" value="{{ old('menuname') }}" name="menuname" placeholder="{{ __('Admin::menu.menu_name') }}" />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::menu.menu_desc') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<textarea name="description" value="{{ old('description') }}" class="form-control" placeholder="{{ __('Admin::menu.menu_desc') }}"></textarea>
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::menu.menu_trans') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control" type="text" value="{{ old('menutrans') }}" name="menutrans" placeholder="{{ __('Admin::menu.menu_trans') }}" />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::menu.menu_icon') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control" type="text" value="{{ old('menuicon') }}" name="menuicon" placeholder="{{ __('Admin::menu.menu_icon') }}" />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::menu.menu_sort') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control" type="text" value="{{ old('menusort') }}" name="menusort" placeholder="{{ __('Admin::menu.menu_sort') }}" />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::menu.menu_parent') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<input class="form-control" type="text" value="{{ old('menuparent') }}" name="menuparent" placeholder="{{ __('Admin::menu.menu_parent') }}" />
					</div>					
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{ __('Admin::menu.menu_url') }}</label>
					<div class="col-md-7 col-sm-7 col-xs-12">
						<!--input class="form-control" type="text" value="{{ old('menuurl') }}" name="menuurl" placeholder="{{ __('Admin::menu.menu_url') }}" /-->
						{{ Form::select('menuurl', [''=>'---']+$permissions, old('menuurl'), ['class'=>'form-control'] ) }}
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

<script type="text/javascript">

	@if( Session::get('modal') )
		$('#modal-1').modal( {backdrop: 'static', keyboard: false} ); 
	@endif

	$(document).ready(function() {

		// $('#modal-1').on('hidden.bs.modal', function () {
		//     window.location.reload();
		// });

		$('.editbtn').on('click', function() {

			var menuid = $(this).data('id');
			var menuname = $(this).data('name');
			var menutrans = $(this).data('prefix');
			var menudesc = $(this).data('desc');
			var menusort = $(this).data('sequence');
			var menuicon = $(this).data('icon');
			var menuurl = $(this).data('url');
			var menuparent = $(this).data('parent');

			$('#modal-1').modal('show');

			$("[name='menuname']").val(menuname);
			$("[name='description']").val(menudesc);
			$("[name='menutrans']").val(menutrans);
			$("[name='menuicon']").val(menuicon);
			$("[name='menusort']").val(menusort);
			$("[name='menuparent']").val(menuparent);
			$("[name='menuurl']").val(menuurl);

			$('.menuform').attr('action', '{{ URL::to("admin/menus/update") }}/'+$(this).data('id') );

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

		$('.delbtn').click(function() {
      	
		  	swal({
			  // title: 'Are you sure you want to exit?',
			  width: 400,
			  text: "{{ __('Admin::menu.menu_delete_msg') }}",
			  type: 'warning',
			  allowOutsideClick: false,
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
        cancelButtonText: "{{ __('Admin::base.cancel') }}",
			}).then((result) => {
			  if (result) {
			    window.location = "{{ url::to('admin/menus/delete') }}/"+$(this).data('id');
			  }
			})
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