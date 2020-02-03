@extends('layouts.adminLTE.master')

@section('content')
<div class="box">
	
	<div class="box-body">
		<a class="btn btn-app" href="{{ URL::to('user') }}">
			<i class="fa fa-users"></i> 
			{{ __('Admin::user.users') }}
		</a>
		<a class="btn btn-app" href="{{ route('admin.roles') }}">
			<i class="fa fa-check-square"></i>
			{{ __('Admin::role.roles') }}
		</a>
		<a class="btn btn-app" href="{{ route('admin.permission') }}">
			<i class="fa fa-sitemap"></i>
			{{ __('Admin::permission.permission') }}
		</a>
		<a class="btn btn-app">
			<i class="fa fa-bars"></i>
			{{ __('Admin::base.menus') }}
		</a>
		<a class="btn btn-app" href="{{ route('admin.logs') }}">
			<i class="fa fa-code"></i>
			{{ __('Admin::base.logs') }}
		</a>
		<a class="btn btn-app" href="{{ route('admin.config') }}">
			<i class="fa fa-cogs"></i>
			{{ __('Admin::base.config') }}
		</a>
		<a class="btn btn-app" href="{{ route('admin.auditrail') }}">
			<i class="fa fa-clipboard"></i>
			{{ __('Admin::base.audit_trail') }}
		</a>
	</div>
</div>
@stop 