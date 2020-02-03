<!-- jQuery -->
    <script src="{!! asset('gentella/vendors/jquery/dist/jquery.min.js') !!}"></script>
    <!-- Bootstrap -->
    <script src="{!! asset('gentella/vendors/bootstrap/dist/js/bootstrap.min.js') !!}"></script>
    <!-- FastClick -->
    <script src="{!! asset('gentella/vendors/fastclick/lib/fastclick.js') !!}"></script>
    <!-- NProgress -->
    <script src="{!! asset('gentella/vendors/nprogress/nprogress.js') !!}"></script>

    <!-- Chart.js -->
    <!--script src="{!! asset('gentella/vendors/Chart.js/dist/Chart.min.js') !!}"></script-->
    <!-- gauge.js -->
    <!--script src="{!! asset('gentella/vendors/gauge.js/dist/gauge.min.js') !!}"></script-->
    <!-- bootstrap-progressbar -->
    <!--script src="{!! asset('gentella/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') !!}"></script-->
    <!-- iCheck -->
    <!--script src="{!! asset('gentella/vendors/iCheck/icheck.js') !!}"></script-->
    <script src="{!! asset('gentella/vendors/iCheck/icheck.min.js') !!}"></script>

    <!-- Skycons -->
    <script src="{!! asset('gentella/vendors/skycons/skycons.js') !!}"></script>
    <!-- Flot -->
    <!--script src="{!! asset('gentella/vendors/Flot/jquery.flot.js') !!}"></script>
    <script src="{!! asset('gentella/vendors/Flot/jquery.flot.pie.js') !!}"></script>
    <script src="{!! asset('gentella/vendors/Flot/jquery.flot.time.js') !!}"></script>
    <script src="{!! asset('gentella/vendors/Flot/jquery.flot.stack.js') !!}"></script>
    <script src="{!! asset('gentella/vendors/Flot/jquery.flot.resize.js') !!}"></script-->
    <!-- Flot plugins -->
    <!--script src="{!! asset('gentella/vendors/flot.orderbars/js/jquery.flot.orderBars.js') !!}"></script>
    <script src="{!! asset('gentella/vendors/flot-spline/js/jquery.flot.spline.min.js') !!}"></script>
    <script src="{!! asset('gentella/vendors/flot.curvedlines/curvedLines.js') !!}"></script-->

    <!-- DateJS -->
    <!--script src="{!! asset('gentella/vendors/DateJS/build/date.js') !!}"></script-->

    <!-- JQVMap -->
    <!--script src="{!! asset('gentella/vendors/jqvmap/dist/jquery.vmap.js') !!}"></script>
    <script src="{!! asset('gentella/vendors/jqvmap/dist/maps/jquery.vmap.world.js') !!}"></script>
    <script src="{!! asset('gentella/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') !!}"></script-->

    <!-- bootstrap-daterangepicker -->
    <!--script src="{!! asset('gentella/vendors/moment/min/moment.min.js') !!}"></script>
    <script src="{!! asset('gentella/vendors/bootstrap-daterangepicker/daterangepicker.js') !!}"></script-->

    <!-- sweetalert -->
    <script src="{!! asset('gentella/vendors/sweetalert2/dist/sweetalert2.js') !!}"></script>

    <!-- Custom Theme Scripts -->
    <script src="{!! asset('gentella/build/js/custom.min.js') !!}"></script>

    <!-- Include a polyfill for ES6 Promises (optional) for IE11 and Android browser (FOR SWEETALERT2) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>


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


    @section('footer')
    @show
    @yield('script')

    <!-- scripts related to specific page -->
    @yield('page-script')
	
  </body>
</html>