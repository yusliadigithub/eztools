@extends('layouts.adminLTE.master')

@section('content')
<div class="box">

	<div class="box-header with-border">
		<div class="row">
			<div class="pull-left col-sm-12 col-md-6 col-lg-6">
				@hasrole('superadmin')
				<a href="{{ route('admin.clear.auditrail') }}" class="btn btn-sm btn-primary"><i class="fas fa-trash"></i> {{ __('Admin::base.audit_truncate') }}</a>
				@endhasrole			
			</div>
			<div class="box-tools col-sm-12 col-md-6 col-lg-6">
	            <button data-toggle="collapse" data-target="#searchbox" class="searchbox pull-right btn btn-box-tool" href="javascript:;" role="button">SEARCH&nbsp;&nbsp;&nbsp;<i class="fa fa-search"></i></button>
			</div>
		</div>
	</div>
	
	<div class="box-body">
		<div id="searchbox" class="collapse {{ Input::has('search') ? 'in' : '' }} well">
			{{ Form::open(['action'=>'\App\Modules\Admin\Controllers\AdminController@auditTrail', 'method'=>'get', 'class'=>'form-horizontal']) }}
				<div class="form-group">
					<label class="form-label col-sm-12 col-md-1">{{ __('Admin::user.searchby') }}</label>
					<div class="col-sm-12 col-md-2">
					  	<select class="form-control input-sm" name="search">
					  		<option value="name">{{ __('Admin::user.users') }}</option>
					  		<option value="event">{{ __('Admin::base.audit_events') }}</option>
					  		<option value="ip_address">{{ __('Admin::base.audit_ip') }}</option>
					  		<option value="auditable_type">{{ __('Admin::base.audit_model') }}</option>
					  		<option value="url">{{ __('Admin::base.audit_url') }}</option>
					  		<option value="url">{{ __('Admin::base.created_at') }}</option>
					  	</select>
				  	</div>
				  	<div class="col-sm-12 col-md-6"><input type="text" class="input-sm form-control" value="{{ Input::get('keyword') }}" placeholder="{{ __('Admin::base.keyword') }}" name="keyword"></div>
				  	<div class="col-sm-12 col-md-3 text-center">
				  		<button class="btn btn-sm btn-success"><i class="fa fa-search"></i> {{ __('Admin::base.search') }}</button>
				  		<a href="{{ route('admin.auditrail') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> {{ __('Admin::base.reset') }}</a>
				  	</div>
				</div>
			{{ Form::close() }}
		</div>
		<div class="table-responsive">
			<table class="table table-striped jambo_table bulk_action table-bordered table-condensed">
				<thead>
					<tr class="headings">
						<!--th><input type="checkbox" onclick="toggleCheck(this)" name="checkall" class="checkall"></th-->
						<th class="column-title">{{ __('Admin::user.users') }}</th>
						<th class="column-title">{{ __('Admin::base.audit_events') }}</th>
						<th class="column-title">{{ __('Admin::base.audit_id') }}</th>
						<th class="column-title">{{ __('Admin::base.audit_model') }}</th>
						<th class="column-title">{{ __('Admin::base.audit_url') }}</th>
						<th class="column-title">{{ __('Admin::base.audit_ip') }}</th>
						<!--th class="column-title">{{-- __('Admin::base.audit_browser') --}}</th-->
						<th class="column-title">{{ __('Admin::base.created_at') }}</th>
						<th class="column-title">{{ __('Admin::base.action') }}</th>
					</tr>
				</thead>
				<tbody>
					@if(count($audits) > 0)
					@foreach($audits as $audit)
					<tr>
						<td>{{ ($audit->user) ? $audit->user->name : 'PUBLIC USER' }}</td>
						<td>{{ $audit->event }}</td>
						<td class="text-center">{{ $audit->auditable_id }}</td>
						<td>{{ $audit->auditable_type }}</td>
						<td>{{ $audit->url }}</td>
						<td>{{ $audit->ip_address }}</td>
						<!--td>{{-- $audit->user_agent --}}</td-->
						<td>{{ $audit->created_at }}</td>
						<td>
							<a href="javascript:;" data-old='{!! json_encode($audit->old_values) !!}'' data-new='{!! json_encode($audit->new_values) !!}' class="btn btn-xs btn-info moreinfo"><i class="fa fa-info-circle"></i></a>
						</td>
					</tr>
					@endforeach
					@else
					<tr><td colspan="7">No result(s)</td></tr>
					@endif
				</tbody>
			</table>
			{{ $audits->appends(Request::only('search'))->appends(Request::only('keyword'))->links() }}
		</div>
	</div>
</div>
@stop 


@section('footer')

<script type="text/javascript">
	
	$('.moreinfo').click( function(){

		// swal({
		// 	title: 'User Activity Logs',
		// 	text: 'old values '+ $(this).attr('data-old') +"<br />"+$(this).attr('data-new'),
		// 	type: 'info',
		// });
		swal('{{ __("Admin::base.audit_trail_desc") }}', '{{ __("Admin::base.audit_oldval") }}: '+$(this).attr('data-old') +'<br /><br /> {{ __("Admin::base.audit_newval") }}: '+$(this).attr('data-new'));

	});

</script>

@stop