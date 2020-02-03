<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>{!! !empty($pagetitle) || isset($pagetitle) ? $pagetitle : ''  !!}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="{!! asset('adminLTE/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <!--link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css
    " /-->
    <link rel="stylesheet" type="text/css" href="{!! asset('css/fontawesome/fontawesome-all.css') !!}">
    <!-- fullcalendar -->
    <link rel="stylesheet" type="text/css" href="{!! asset('fullcalendar/fullcalendar.css') !!}">
    <!-- fileinput -->
    <link rel="stylesheet" type="text/css" href="{!! asset('css/fileinput.css') !!}">
    <!-- multiselectdropdown -->
    <link rel="stylesheet" type="text/css" href="{!! asset('css/multiselectdropdown.css') !!}">
    <!-- switchcheckbox -->
    <link rel="stylesheet" type="text/css" href="{!! asset('css/switchcheckbox.css') !!}">
    <!-- ckeditor -->
    <!--link rel="stylesheet" type="text/css" href="{!! asset('adminLTE/plugins/ckeditor/contents.css') !!}"-->
    <!-- animatedcheckbox -->
    <!-- link rel="stylesheet" type="text/css" href="{!! asset('css/animatedcheckbox.css') !!}"-->
    <!-- Ionicons -->
    <!--link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" /-->
    <!-- Theme style -->
    <link href="{!! asset('adminLTE/dist/css/AdminLTE.min.css') !!}" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="{!! asset('adminLTE/dist/css/skins/_all-skins.min.css') !!}" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="{!! asset('css/mjmz.css') !!}" rel="stylesheet">
    <!-- sweetalert -->
    <link href="{!! asset('adminLTE/plugins/sweetalert2/dist/sweetalert2.css') !!}" rel="stylesheet">

    <!-- jQuery 2.2.3 -->
    <script src="{!! asset('adminLTE/plugins/jQuery/jquery-2.2.3.min.js') !!}"></script>
    <!-- fullcalendar -->
    <script src="{!! asset('fullcalendar/lib/moment.min.js') !!}"></script>
    <script src="{!! asset('fullcalendar/fullcalendar.js') !!}"></script>
    <!-- fileinput -->
    <script src="{!! asset('js/fileinput.js') !!}"></script>
    <!-- multiselectdropdown -->
    <script src="{!! asset('js/multiselectdropdown.js') !!}"></script>
    <!-- ckeditor -->
    <!--script src="{!! asset('adminLTE/plugins/ckeditor/ckeditor.js') !!}"></script-->
    

	@section('header')
	@show

  </head>
  <body class="skin-blue sidebar-mini">

  	<div id="idletimeout">
    You will be logged off in <span><!-- countdown place holder --></span>&nbsp;seconds due to inactivity.
    <a href="#" id="idletimeout-resume">Click here to continue using this web page</a>.</div>

    <!-- Site wrapper -->
    <div class="wrapper">
      
      <header class="main-header">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="logo">
          <span class="logo-lg" style="font-size: 0.8em;">
            <b><?php echo date("D",strtotime($t=date('d-m-Y')));?> <?php echo date("d/m/Y");?></b>
          </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

              <li role="" class="dropdown">
                  <a class="text-uppercase" dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="javascript:;"><i class="fa fa-language"></i> {{ Config::get('app.locale') }}</a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="{{ route('setlanguage','en') }}"><i class="fa fa-dot-circle"></i>  {{ __('Admin::base.en') }}</a></li>
                    <li><a href="{{ route('setlanguage','my') }}"><i class="fa fa-dot-circle"></i>  {{ __('Admin::base.my') }}</a></li>
                  </ul>
                </li>
              <!-- Control Sidebar Toggle Button -->
              <!--li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-cog"></i></a>
              </li-->
            </ul>
            <ul class="nav navbar-nav">

              <li role="" class="dropdown">
                  <a class="text-uppercase" dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="javascript:;"><i class="fa fa-sign-out-alt"></i></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="javascript:;" id="signout">{{ __('Admin::base.logout') }}<i class="fa fa-power-off pull-right" style="padding-top: 3px"></i></a></li>
                  </ul>
                </li>
              <!-- Control Sidebar Toggle Button -->
              <!--li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-cog"></i></a>
              </li-->
            </ul>
          </div>
        </nav>
      </header>

      <!-- =============================================== -->

      <!-- Left side column. contains the sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
          <br>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ Auth::user()->picture == null ? asset('adminLTE/dist/img/avatar.png') : '' }}" width="30px" height="30px" style="margin-left:10px;border-radius: 50%" class="user-image" alt="User Image"/>
              <span class="hidden-xs" style="margin-left: 10px;font-size: 1.2em">
                <b>
                  {{ strtoupper(Auth::user()->name) }}
                </b>
              </span>
            </a>
            <hr>
          
            {{ Form::main_menu() }}
            

            <!--li class="treeview"><a href="{{-- route('home') --}}"><i class="fa fa-home"></i> Home</a></li-->

            {{--@hasanyrole('admin','superadmin')--}}
            <!--li class="treeview">
              <a href="#">
                <i class="fa fa-cogs"></i> <span>Administration</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{-- URL::to('admin') --}}"><i class="fa fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="{{-- URL::to('user') --}}"><i class="fa fa-users"></i> Users</a></li>
                <li><a href="{{-- route('admin.roles') --}}"><i class="fa fa-user-secret"></i> Roles</a></li>
                <li><a href="{{-- route('admin.permission') --}}"><i class="fa fa-unlock"></i> Permission</a></li>
                <li><a href="{{-- route('admin.menus') --}}"><i class="fa fa-bars"></i> Menu</a></li>
                <li><a href="{{-- URL::to('admin/logs') --}}"><i class="fa fa-code"></i> Logs</a></li>
                <li><a href="{{-- URL::to('admin/configuration') --}}"><i class="fa fa-cog"></i> Configuration</a></li>
                <li><a href="{{-- route('admin.auditrail') --}}"><i class="fa fa-clipboard"></i> Audit Trails</a></li>
              </ul>
            </li-->
            {{--@endhasanyrole--}}

            {{--@hasanyrole('admin','superadmin')--}}
            <!--li class="treeview">
              <a href="#">
                <i class="fa fa-building"></i> <span>Property</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{-- URL::to('property/maindetail') --}}"><i class="fa fa-building"></i> Main Detail</a></li>
                <li><a href="{{-- URL::to('property/type') --}}"><i class="fa fa-list-alt"></i> Property Type</a></li>
                <li><a href="{{-- URL::to('property/block') --}}"><i class="fa fa-square"></i> Property Block</a></li>
                <li><a href="{{-- URL::to('property/level') --}}"><i class="fa fa-angle-double-up"></i> Property Level</a></li>
                <li><a href="{{-- URL::to('property') --}}"><i class="fa fa-home"></i> Properties</a></li>
                <li><a href="{{-- URL::to('property') --}}"><i class="fa fa-history"></i> History</a></li>
              </ul>
            </li-->
            {{--@endhasanyrole--}}

            <!--li class="treeview">
              <a href="#">
                <i class="fa fa-sitemap"></i> <span>Resident</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                {{--@hasanyrole('admin','superadmin')--}}
                <li><a href="{{ URL::to('resident') }}"><i class="fa fa-user"></i> Unit User</a></li-->
                {{--@endhasanyrole--}}
                {{-- @hasanyrole('masterowner','admin') --}} <!-- taktaukenapa xjadi -->
                <!--li><a href="{{-- URL::to('resident/unitmaster') --}}"><i class="fa fa-building"></i> Unit Registration</a></li-->
                {{-- @endhasanyrole --}}
                {{--@hasrole('masterowner')--}}
                <!--li><a href="{{ URL::to('resident/unitmaster') }}"><i class="fa fa-building"></i> Unit Registration (Ext User)</a></li>
                {{--@endhasrole--}}
                {{--@hasrole('masterowner')--}}
                <li><a href="{{ URL::to('resident/extusers') }}"><i class="fa fa-users"></i> Unit External User</a></li>
                {{--@endhasrole--}}
                {{--@hasanyrole('admin','superadmin')--}}
                <li><a href="{{ URL::to('resident/complainttype') }}"><i class="fa fa-list-alt"></i> Complaint Type</a></li>
                <li><a href="{{ URL::to('resident/quickresponse') }}"><i class="fa fa-file"></i> Quick Response Template</a></li>
                {{--@endhasanyrole--}}
                <li><a href="{{ URL::to('resident/complaint') }}"><i class="fa fa-comments"></i> Complaint</a></li>
                {{--@hasanyrole('admin','superadmin')--}}
                <li><a href="{{ URL::to('resident/emergencytype') }}"><i class="fa fa-list"></i> Emergency Type</a></li>
                {{--@endhasanyrole--}}
                <li><a href="{{ URL::to('resident/emergency/create') }}"><i class="fa fa-plus-square"></i> Emergency</a></li>
                {{--@hasanyrole('admin','superadmin')--}}
                <li><a href="{{ URL::to('resident/emergency') }}"><i class="fa fa-hospital-alt"></i> Emergency List</a></li>
                {{--@endhasanyrole--}}
              </ul>
            </li-->

            <!--li class="treeview">
              <a href="#">
                <i class="fa fa-calendar"></i> <span>Booking</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{-- URL::to('booking') --}}"><i class="fa fa-clock-o"></i> Booking List</a></li>
              </ul>
            </li-->
            
            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- =============================================== -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            {{ empty($pagetitle) || !isset($pagetitle) ? '' : $pagetitle }}
            <small>{{ empty($pagedesc) || !isset($pagedesc) ? '' : $pagedesc }}</small>
          </h1>
          <!--ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Examples</a></li>
            <li class="active">Blank page</li>
          </ol-->
        </section>

        <!-- Main content -->
        <section class="content supreme-container">

          <!-- Default box -->
          <!--div class="box" -->
            <!--div class="box-header with-border"-->
              <!--h3 class="box-title">Title</h3-->
              <!--div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div-->
            <!--/div-->
            <!--div class="box-body"-->
              @include('common.message')
          		@yield('content')
            <!--/div--><!-- /.box-body -->
          <!--/div--><!-- /.box -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.0
        </div>
        <strong>&copy; {{ date('Y') }} <a href="javascript:;">IT Partnership Solution</a>.</strong> All rights reserved.
      </footer>
      
      <!-- Control Sidebar -->      
      <aside class="control-sidebar control-sidebar-dark">                
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
          <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
          
          <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <!-- Home tab content -->
          <div class="tab-pane" id="control-sidebar-home-tab">
            <h3 class="control-sidebar-heading">Recent Activity</h3>
            <ul class='control-sidebar-menu'>
              <li>
                <a href='javascript::;'>
                  <i class="menu-icon fa fa-birthday-cake bg-red"></i>
                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                    <p>Will be 23 on April 24th</p>
                  </div>
                </a>
              </li>
              <li>
                <a href='javascript::;'>
                  <i class="menu-icon fa fa-user bg-yellow"></i>
                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>
                    <p>New phone +1(800)555-1234</p>
                  </div>
                </a>
              </li>
              <li>
                <a href='javascript::;'>
                  <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>
                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>
                    <p>nora@example.com</p>
                  </div>
                </a>
              </li>
              <li>
                <a href='javascript::;'>
                  <i class="menu-icon fa fa-file-code-o bg-green"></i>
                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>
                    <p>Execution time 5 seconds</p>
                  </div>
                </a>
              </li>
            </ul><!-- /.control-sidebar-menu -->

            <h3 class="control-sidebar-heading">Tasks Progress</h3> 
            <ul class='control-sidebar-menu'>
              <li>
                <a href='javascript::;'>               
                  <h4 class="control-sidebar-subheading">
                    Custom Template Design
                    <span class="label label-danger pull-right">70%</span>
                  </h4>
                  <div class="progress progress-xxs">
                    <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                  </div>                                    
                </a>
              </li> 
              <li>
                <a href='javascript::;'>               
                  <h4 class="control-sidebar-subheading">
                    Update Resume
                    <span class="label label-success pull-right">95%</span>
                  </h4>
                  <div class="progress progress-xxs">
                    <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                  </div>                                    
                </a>
              </li> 
              <li>
                <a href='javascript::;'>               
                  <h4 class="control-sidebar-subheading">
                    Laravel Integration
                    <span class="label label-waring pull-right">50%</span>
                  </h4>
                  <div class="progress progress-xxs">
                    <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                  </div>                                    
                </a>
              </li> 
              <li>
                <a href='javascript::;'>               
                  <h4 class="control-sidebar-subheading">
                    Back End Framework
                    <span class="label label-primary pull-right">68%</span>
                  </h4>
                  <div class="progress progress-xxs">
                    <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                  </div>                                    
                </a>
              </li>               
            </ul><!-- /.control-sidebar-menu -->         

          </div><!-- /.tab-pane -->
          <!-- Stats tab content -->
          <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->
          <!-- Settings tab content -->
          <div class="tab-pane" id="control-sidebar-settings-tab">            
            <form method="post">
              <h3 class="control-sidebar-heading">General Settings</h3>
              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Report panel usage
                  <input type="checkbox" class="pull-right" checked />
                </label>
                <p>
                  Some information about this general settings option
                </p>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Allow mail redirect
                  <input type="checkbox" class="pull-right" checked />
                </label>
                <p>
                  Other sets of options are available
                </p>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Expose author name in posts
                  <input type="checkbox" class="pull-right" checked />
                </label>
                <p>
                  Allow the user to show his name in blog posts
                </p>
              </div><!-- /.form-group -->

              <h3 class="control-sidebar-heading">Chat Settings</h3>

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Show me as online
                  <input type="checkbox" class="pull-right" checked />
                </label>                
              </div><!-- /.form-group -->

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Turn off notifications
                  <input type="checkbox" class="pull-right" />
                </label>                
              </div><!-- /.form-group -->

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Delete chat history
                  <a href="javascript::;" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                </label>                
              </div><!-- /.form-group -->
            </form>
          </div><!-- /.tab-pane -->
        </div>
      </aside><!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->

    <!-- Bootstrap 3.3.2 JS -->
    <script src="{!! asset('adminLTE/bootstrap/js/bootstrap.min.js') !!}" type="text/javascript"></script>
    <!-- SlimScroll -->
    <script src="{!! asset('adminLTE/plugins/slimScroll/jquery.slimscroll.min.js') !!}" type="text/javascript"></script>
    <!-- FastClick -->
    <script src="{!! asset('adminLTE/plugins/fastclick/fastclick.min.js') !!}"></script>
    <!-- AdminLTE App -->
    <script src="{!! asset('adminLTE/dist/js/app.min.js') !!}" type="text/javascript"></script>
    
    <!-- Demo -->
    <script src="{!! asset('adminLTE/dist/js/demo.js') !!}" type="text/javascript"></script>

    <!-- sweetalert -->
    <script src="{!! asset('adminLTE/plugins/sweetalert2/dist/sweetalert2.js') !!}"></script>

    <script src="{!! asset('js/jquery.idletimer.js') !!}"></script>
    <script src="{!! asset('js/jquery.idletimeout.js') !!}"></script>
    
    @if( Globe::baseconf("config_auto_logout") == 1 )
    <script>    
      $.idleTimeout('#idletimeout', '#idletimeout a', {
        idleAfter: {{ Globe::baseconf("config_idle_minutes") }},
        pollingInterval: 2,
        serverResponseEquals: 'OK',
        onTimeout: function(){
          $(this).fadeOut();
          window.location = "{{ route('logout') }}";
        },
        onIdle: function(){
          $(this).fadeIn(); // show the warning bar
        },
        onCountdown: function( counter ){
          $(this).find("span").html( counter ); // update the counter
        },
        onResume: function(){
          $(this).fadeOut(); // hide the warning bar
        }
      });
	</script>
    @endif

    <script type="text/javascript">

      	$('#signout').click(function() {
      	
		  	swal({
			  // title: 'Are you sure you want to exit?',
			  width: 400,
			  text: "{{ __('Admin::base.asklogout') }}",
			  // type: 'warning',
			  allowOutsideClick: false,
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
        cancelButtonText: "{{ __('Admin::base.cancel') }}",
			}).then((result) => {
			  if (result) {
			    window.location = "{{ route('logout') }}";
			  }
			})
      	});
    </script>


    @section('footer')
    @show
    @yield('script')

    <!-- scripts related to specific page -->
    @yield('page-script')

  </body>
</html>