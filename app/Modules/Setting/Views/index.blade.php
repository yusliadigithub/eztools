@extends('layouts.adminLTE.master')

@section('header')
<!-- iCheck -->
<link href="{!! asset('gentella/vendors/iCheck/skins/flat/green.css') !!}" rel="stylesheet">
@stop

@section('content')

	<div class="box box-primary">
	    <!-- /.box-header -->
	    <div class="box-body">
	      <ul class="todo-list">
	        <li class="">
			  <a href="{{ URL::to('setting/settingpoint') }}"><p style="margin: 0px 0px 0px 0px;"><span class="text">Points</span></p></a>
	        </li>
	        <li class="">
			  <a href="{{ URL::to('setting/settingstar') }}"><p style="margin: 0px 0px 0px 0px;"><span class="text">Star</span></p></a>
	        </li>
	        <li class="">
			  <a href="{{ URL::to('setting/settingchief') }}"><p style="margin: 0px 0px 0px 0px;"><span class="text">Chief</span></p></a>
	        </li>
	        <li class="">
			  <a href="{{ URL::to('setting/settinglevel') }}"><p style="margin: 0px 0px 0px 0px;"><span class="text">Level</span></p></a>
	        </li>
	        <li class="">
			  <a href="{{ URL::to('setting/settingrequest') }}"><p style="margin: 0px 0px 0px 0px;"><span class="text">Request</span></p></a>
	        </li>
	        <li class="">
			  <a href="{{ URL::to('setting/settingjobposition') }}"><p style="margin: 0px 0px 0px 0px;"><span class="text">Job Position</span></p></a>
	        </li>
	        <li class="">
			  <a href="{{ URL::to('setting/settingdepartment') }}"><p style="margin: 0px 0px 0px 0px;"><span class="text">Department</span></p></a>
	        </li>
	        <li class="">
			  <a href="{{ URL::to('setting/settingempstatus') }}"><p style="margin: 0px 0px 0px 0px;"><span class="text">Employment Status</span></p></a>
	        </li>

	      </ul>
	    </div>
	  </div>


@stop

@section('footer')

  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
		<div class="modal-body">
			<div class="form-group">
				<label class="control-label col-md-2 col-sm-2 col-xs-12">Subject</label>
				<div class="col-md-8 col-sm-8 col-xs-12">
					<input class="form-control modaldata" type="text" name="announcement_subject" id="announcement_subject" placeholder="Subject" />
				</div>					
			</div>
			<div class="form-group">
				<div class="col-md-8 col-sm-8 col-xs-12">
					<input class="form-control modaldata" type="hidden" name="announcement_status" id="announcement_status" value="active" />
				</div>					
			</div>
		</div>
        <div class="modal-footer">
			<button type="submit" class="btn btn-sm btn-success pull-right">Submit</button>
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
@stop