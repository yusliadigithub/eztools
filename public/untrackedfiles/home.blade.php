@extends('layouts.adminLTE.master')

@section('content')
<div class="box">
	<div class="box-body">
		Hello, this is home!
		{{ $users }}
		{{ $str }}
		<br>
		@hasrole('admin')
			i am admin<br>
		@endhasrole
		@hasrole('superadmin')
			i am superadminadmin<br>
		@endhasrole
		@hasrole('unitowner')
			i am unitowner<br>
		@endhasrole
		@hasrole('normaluser')
			i am normaluser<br>
		@endhasrole
		@hasanyrole('admin','unitowner')
			yes, got it
		@endhasanyrole
	</div>
</div>
@stop 