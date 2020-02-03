<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ Globe::baseconf('config_system_longname') }}</title>

    <!-- Bootstrap -->
    <link href="{!! asset('gentella/vendors/bootstrap/dist/css/bootstrap.min.css') !!}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{!! asset('gentella/vendors/font-awesome/css/font-awesome.min.css') !!}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{!! asset('gentella/vendors/nprogress/nprogress.css') !!}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{!! asset('gentella/vendors/animate.css/animate.min.css') !!}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{!! asset('gentella/build/css/custom.min.css') !!}" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div>
      @if( Globe::baseconf('config_maintenance') != 1 )
      <div class="login_wrapper">
        
        <div id="regiter" class="animate form egistration_form">
          <section class="ogin_content text-center">
            <div class="x_panel">
            <form class="form-horizontal" method="POST" action="{{ route('register') }}">
              {{ csrf_field() }}
              <h4><i class="fa fa-cog"></i> Create Account</h4><hr />
              <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <input placeholder="Name" id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
              </div>

              <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" placeholder="E-Mail Address" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <input placeholder="Password" id="password" type="password" class="form-control" name="password" required>

              @if ($errors->has('password'))
                  <span class="help-block">
                      <strong>{{ $errors->first('password') }}</strong>
                  </span>
              @endif
            </div>

            <div class="form-group">
              <input id="password-confirm" placeholder="Confirm password" type="password" class="form-control" name="password_confirmation" required>
            </div>

              <div>
                <button class="btn btn-sm btn-primary">Submit</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="{{ route('login') }}" class=""> Log in </a>
                </p>

                
              </div>
            </form>
            </div>
            <div>©{{ date('Y') }} All Rights Reserved. {{ Globe::baseconf('config_client_name') }}</div>
          </section>
        </div>

      </div>
      @endif

      @if( Globe::baseconf('config_maintenance') == 1 && Session()->get('referer') != '/login/admin')
        <!-- page content -->
        <div class="col-md-12">
          <div class="col-middle">
            <div class="text-center text-center">
              <h1 class="error-number"><i class="fa fa-warning"></i></h1>
              <h3 class="error-numbr">UNDER MAINTENANCE</h3>
              <h2>We are sorry, please try again later ... </h2>
              <p></p>
            </div>
          </div>
        </div>
        <!-- /page content -->
      @endif

      
    </div>
  </body>
</html>