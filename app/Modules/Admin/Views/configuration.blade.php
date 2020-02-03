@extends('layouts.adminLTE.master')

@section('content')
<div class="box">
	<div class="box-body">
		<div class="row">

			
			<!-- system settings -->
			<div class="col-sm-6">

				<div class="panel">
					
						<div class="x_content">
							{{ Form::open(['action'=>'\App\Modules\Admin\Controllers\AdminController@updateConfiguration', 'method'=>'post', 'class'=>'form-horizontal']) }}

							<div class="form-group">
								<label class="col-sm-4 control-label">{{ __('Admin::base.config_sysname') }}</label>
								<div class="col-sm-8"><input type="text" class="form-control" name="confsystemname" value="{{ Globe::baseconf('config_system_longname') }}"></div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ __('Admin::base.config_shortsysname') }}</label>
								<div class="col-sm-8"><input type="text" class="form-control" name="confshortname" value="{{ Globe::baseconf('config_system_shortname') }}"></div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ __('Admin::base.config_language') }}</label>
								<div class="col-sm-8"><input type="text" class="form-control" name="conflanguage" value="{{ Globe::baseconf('config_language') }}"></div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ __('Admin::base.config_autologout') }}</label>
								<div class="col-sm-8"><input value="1" name="confenablelogout" class="form-contro" type="checkbox" {{ empty(Globe::baseconf('config_auto_logout')) ? '' : 'checked' }} /></div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ __('Admin::base.config_idletime') }}</label>
								<div class="col-sm-8"><input name="confidletime" class="form-control" type="text" value="{{ Globe::baseconf('config_idle_minutes') / 60 }}" /></div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ __('Admin::base.config_maintenance') }}</label>
								<div class="col-sm-8"><input value="1" name="confmaintenance" class="form-contro" type="checkbox" {{ empty(Globe::baseconf('config_maintenance')) ? '' : 'checked=""' }} /></div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{ __('Admin::base.config_clientname') }}</label>
								<div class="col-sm-8"><input name="confclientname" class="form-control" type="text" value="{{ Globe::baseconf('config_client_name') }}" /></div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"></label>
								<div class="col-sm-8"><button type="submit" class="btn btn-sm btn-success pull-right"><i class="fa fa-check-circle"></i> {{ __('Admin::base.save') }}</button></div>
							</div>							
							{{ Form::close() }}
						</div>
				</div>

			</div>
			

			<div class="col-sm-6">

			{{ Form::main_menu() }}

			{{--@foreach (Globe::navigation() as $menu)--}}
			  {{--@if( Auth::user()->can( $menu->menu_url ) )--}}
			  <!--li class="treeview">{{-- __($menu->menu_trans) --}} ({{-- $menu->menu_url --}})
              	{{--@if(count($menu['children']) > 0)--}}
              	<ul class="treeview-menu">
	              	{{--@foreach ($menu['children'] as $child)--}}
	              		{{--@if( Auth::user()->can( $child->menu_url ) )--}}
	              		<li><a href="{{-- route($child->menu_url) --}}">{{-- __($child->menu_trans) --}} ({{-- $child->menu_url --}})</a></li>
	              		{{--@endif--}}
	              	{{--@endforeach--}}
              	</ul>
          		{{--@endif--}}
              </li-->
              {{--@endif--}}
            {{--@endforeach--}}

			</div>
		</div>
	</div>
</div>
@stop 