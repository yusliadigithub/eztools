@extends('layouts.adminLTE.master')

@section('content')

<div class="row">
	<div class="col-md-3">
		<div class="box box-primary">
			<div class="box-body box-profile">
				<img class="profile-user-img img-responsive img-circle" src="http://v4/adminLTE/dist/img/avatar.png" />
				<h5 class="profile-username text-center">{{ Auth::user()->name }}</h5>
			</div>
		</div>
	</div>

	<div class="col-md-9">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs"></ul>
		</div>
	</div>

</div>


@stop