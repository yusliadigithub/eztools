<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- new 30042018 -->
    <link href="{!! asset('adminLTE/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css" />
    <link href="{!! asset('adminLTE/dist/css/AdminLTE.min.css') !!}" rel="stylesheet" type="text/css" />
    <link href="{!! asset('adminLTE/plugins/sweetalert2/dist/sweetalert2.css') !!}" rel="stylesheet">
    <!-- close new 30042018 -->
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<style type="text/css">
    body {
        background-image:    url('{!! asset("/img/agent.jpg") !!}') !important;
        background-repeat:   no-repeat;
        background-position: center center;
        width: 100%;
        height: 200px;
        background-size: 100% 100%;
    }
</style>
<!-- jQuery 2.1.4 -->
<script src="{!! asset('adminLTE/plugins/jQuery/jQuery-2.1.4.min.js') !!}"></script>
<script type="text/javascript">

    $(document).ready(function() {

        if($('#users_detail_postcode').val()!=''){
            getstatedistrict('{{ old("users_detail_postcode") }}');
        }

        $('#users_detail_postcode').on('keyup', function(e){
            
            var code = $(this).val();
            getstatedistrict(code);

        });

        function getstatedistrict(code){

            $('#district_id').empty();
            $('#state_id').empty();
            
            if(code.length>4){

                $.ajax({
                    url: '{{ URL::to("admin/getStateDistrict") }}/'+code,
                    type: 'get',
                    dataType: 'json',
                    success:function(data) {

                        if(data.states!=null){
                            console.log(data);
                            var district = data.districts;
                            var state = data.states;

                            $('#district_id').append('<option value="'+district.district_id+'">'+district.district_desc+'</option>');
                            $('#state_id').append('<option value="'+state.state_id+'">'+state.state_desc+'</option>');
                        }else{
                            swal('{{ __("Admin::base.norecordfound") }}','{{ __("Admin::base.chooseother") }}','error');
                            $('#district_id').append('<option value="">-- No Record Found --</option>');
                            $('#state_id').append('<option value="">-- No Record Found --</option>');
                        }

                    }
                });

            }else{
                $('#district_id').append('<option value="">{{ __("Admin::base.please_type").' '.__("Admin::base.postcode") }}</option>');
                $('#state_id').append('<option value="">{{ __("Admin::base.please_type").' '.__("Admin::base.postcode") }}</option>');
            }

        }

        /*if($('#state_id').val()!=''){
            getdistrict($('#state_id').val(),'{{ old("district_id") }}');
            //$('#district_id').val('{{ old("district_id") }}');
        }

        $('#state_id').on('change', function(e){
            
            var id = $(this).val();
            getdistrict(id,'');

        });

        function getdistrict(id,did){

            //var id = $(this).val();
            $('#district_id').empty();
            
            if(id!=''){

                $.ajax({
                    url: '{{ URL::to("admin/getDistrict") }}/'+id,
                    type: 'get',
                    dataType: 'json',
                    success:function(data) {

                        if(data!=''){
                            $('#district_id').append('<option value="">{{ __('Admin::base.please_select') }}</option>');
                            $.each(data, function(key, value) {
                                if(did!='' && did==key){
                                    $('#district_id').append('<option value="'+ key +'" selected>'+ value +'</option>');
                                }else{
                                    $('#district_id').append('<option value="'+ key +'">'+ value +'</option>');
                                }
                            });
                        }else{
                            $('#district_id').append('<option value="">-- No Record Found --</option>');
                        }

                    }
                });

            }else{
                $('#district_id').append('<option value="">-- Please Select State --</option>');
            }

        }*/

    });

</script>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <!--li><a href="{{ route('register') }}">Register</a></li-->
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{!! asset('adminLTE/plugins/sweetalert2/dist/sweetalert2.js') !!}"></script>
</body>
</html>
