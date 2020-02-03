<!DOCTYPE html>
<html lang="en">
  <head>
  
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ Globe::baseconf('config_system_longname') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <!-- Bootstrap 3.3.4 -->
  <link href="{!! asset('adminLTE/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css" />
  <!-- Font Awesome Icons -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <!-- Theme style -->
  <link href="{!! asset('adminLTE/dist/css/AdminLTE.min.css') !!}" rel="stylesheet" type="text/css" />
  <!-- iCheck -->
  <link href="{!! asset('adminLTE/plugins/iCheck/square/blue.css') !!}" rel="stylesheet" type="text/css" />

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style type="text/css">
    body {
        background-image:    url('{!! asset("/img/black.gif") !!}') !important;
        background-size:     cover;
        background-repeat:   no-repeat;
        background-position: center center;
    }
    
    #masking {
        background: transparent url('{!! asset("/img/line-dotted.png") !!}') repeat;
        height: 100%;
        margin: 0;
        padding: 0;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: -99;
    }
  </style>

  <script type="text/javascript">

    /* check browser cookie enable */
    function checkCookie(){
        var cookieEnabled=(navigator.cookieEnabled)? true : false;
        if (typeof navigator.cookieEnabled=="undefined" && !cookieEnabled){ 
            document.cookie="testcookie";
            cookieEnabled=(document.cookie.indexOf("testcookie")!=-1)? true : false;
        }
        return (cookieEnabled)?true:showCookieFail();
    }

    function showCookieFail(){
      $('#sitemessage').show();
      $('#sitemessage').html('<i class="fa fa-warning"></i> Your browser is currently set to block cookies. Please set your browser allow cookies before using this website.');
    }

    $(function() {
      $('#sitemessage').hide();
      checkCookie();
    });

  </script>


  </head>

  <body class="login-page">

    <div id="masking"></div>

    @if( Globe::baseconf('config_maintenance') != 1 )
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>{{-- Globe::baseconf('config_system_longname') --}}</b></a>
      </div><!-- /.login-logo -->
      
      <div class="login-box-body">
        <p class="login-box-msg"><b>{{ Globe::baseconf('config_system_longname') }}</b></p>
        <form class="" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
          <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
            @if ($errors->has('email'))
                <i class="fa fa-times-circle-o">
                    {{ $errors->first('email') }}
                </i>
            @endif
            <input placeholder="email" id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
            @if ($errors->has('password'))
                <i class="fa fa-times-circle-o">
                    {{ $errors->first('password') }}
                </i>
            @endif
            <input placeholder="password" id="password" type="password" class="form-control" name="password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">    
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Remember Me
                </label>
              </div>                        
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>

        <div class="row">
            <div class="col-xs-6">    
                <div class="checkbox icheck">
                    <label>
                      <a href="#">Forgot password?</a>
                    </label>
                </div>                        
            </div>
            <div class="col-xs-6">    
                <div class="checkbox icheck text-right">
                    <label>
                      <a href="agent/create">Sign Up</a>
                    </label>
                </div>                        
            </div>
        </div>
        

      </div><!-- /.login-box-body -->

      <br /><div class="text-center">©{{date('Y')}} All Rights Reserved. {{ Globe::baseconf('config_client_name') }} {{ Session()->get('referer') }}</div>
    </div><!-- /.login-box -->    
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


    @if( Globe::baseconf('config_maintenance') == 1 && Session()->get('referer') == '/login/admin')
    <div class="login-box">
      <div class="login-logo">
        <a href="../../index2.html"><b>{{ Globe::baseconf('config_system_longname') }}</b></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Administrator's Login Page</p>
        <form class="" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}
          <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
            @if ($errors->has('email'))
                <i class="fa fa-times-circle-o">
                    {{ $errors->first('email') }}
                </i>
            @endif
            <input placeholder="email" id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
            @if ($errors->has('password'))
                <i class="fa fa-times-circle-o">
                    {{ $errors->first('password') }}
                </i>
            @endif
            <input placeholder="password" id="password" type="password" class="form-control" name="password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">    
              <!--div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Remember Me
                </label>
              </div-->                        
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>

      
      </div><!-- /.login-box-body --> 
      <br /><div class="text-center">©{{date('Y')}} All Rights Reserved. {{ Globe::baseconf('config_client_name') }} {{-- Session()->get('referer') --}}</div>     
    </div><!-- /.login-box -->
    @endif
    

  <!-- jQuery 2.1.4 -->
  <script src="{!! asset('adminLTE/plugins/jQuery/jQuery-2.1.4.min.js') !!}"></script>
  <!-- Bootstrap 3.3.2 JS -->
  <script src="{!! asset('adminLTE/bootstrap/js/bootstrap.min.js') !!}" type="text/javascript"></script>
  <!-- iCheck -->
  <script src="{!! asset('adminLTE/plugins/iCheck/icheck.min.js') !!}" type="text/javascript"></script>
  <script>
    $(function () {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
      });
    });
  </script>

  </body>
</html>