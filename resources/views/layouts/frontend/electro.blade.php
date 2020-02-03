<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		@yield('google_analytic') 
		@yield('meta_description') 
		@yield('meta_keyword')
		@yield('meta_others')

		<title>{!! !empty($pagetitle) || isset($pagetitle) ? $pagetitle : ''  !!}</title>

 		<!-- Google font -->
 		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

 		<!-- Bootstrap -->
 		<link type="text/css" rel="stylesheet" href="{!! asset('front/electro/css/bootstrap.min.css') !!}"/>

 		<!-- font awesome -->
 		<link rel="stylesheet" type="text/css" href="{!! asset('css/fontawesome/fontawesome-all.css') !!}">

 		<!-- switchcheckbox -->
    	<link rel="stylesheet" type="text/css" href="{!! asset('css/switchcheckbox.css') !!}">

 		<!-- Slick -->
 		<link type="text/css" rel="stylesheet" href="{!! asset('front/electro/css/slick.css') !!}"/>
 		<link type="text/css" rel="stylesheet" href="{!! asset('front/electro/css/slick-theme.css') !!}"/>

 		<!-- nouislider -->
 		<link type="text/css" rel="stylesheet" href="{!! asset('front/electro/css/nouislider.min.css') !!}"/>

 		<!-- Font Awesome Icon -->
 		<link rel="stylesheet" href="{!! asset('front/electro/css/font-awesome.min.css') !!}">

 		<!-- Custom stlylesheet -->
 		<link type="text/css" rel="stylesheet" href="{!! asset('front/electro/css/style.css') !!}"/>
 		<link type="text/css" rel="stylesheet" href="{!! asset('front/electro/css/mjmz.css') !!}"/>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- sweetalert -->
    	<link href="{!! asset('adminLTE/plugins/sweetalert2/dist/sweetalert2.css') !!}" rel="stylesheet">

		@section('header')
		@show

    </head>
	<body>
		<!-- HEADER -->
		<header>
			<!-- TOP HEADER -->
			<div id="top-header">
				<div class="container">
					<ul class="header-links pull-left">
						@if(!empty($merchant_config->merchant_config_officeno))
						<li><a href="#"><i class="fa fa-phone"></i> {{ $merchant_config->merchant_config_officeno }}</a></li>
						@endif
						@if(!empty($merchant_config->merchant_config_email))
						<li><a href="#"><i class="fa fa-envelope-o"></i> {{ $merchant_config->merchant_config_email }}</a></li>
						@endif
						<!-- <li><a href="#"><i class="fa fa-map-marker"></i> 1734 Stonecoal Road</a></li> -->
					</ul>
					<ul class="header-links pull-right">
						@if( Auth::guard('users_guest')->check() )
						<li class="text-white"><small>{{ __('Frontend::frontend.greeting_word').', '. Auth::guard('users_guest')->user()->guest_fullname }}</small></li>
						@endif

						{{ Form::language_select('',[], Session('frontend_locale'), [], 'list', 'frontend.update.language') }}

						@if( !Auth::guard('users_guest')->check() )
						<li><a href="{{ URL::to('frontend/login') }}"><i class="fas fa-key"></i> {{ __('Frontend::frontend.signin') }}</a></li>
						@endif

						@if( Auth::guard('users_guest')->check() )
						<li><a href="{{ route('frontend.account') }}"><i class="fa fa-user-o"></i> {{ __('Frontend::frontend.my_account') }}</a></li>
						<li><a id="signout" href="javascript:;"><i class="fas fa-sign-out-alt"></i> {{ __('Frontend::frontend.signout') }}</a></li>
						@endif
					</ul>
				</div>
			</div>
			<!-- /TOP HEADER -->

			<!-- MAIN HEADER -->
			<div id="header">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<!-- LOGO -->
						<div class="col-md-3">
							<div class="header-logo">
								<a href="http://{{ substr (Request::root(), 7) }}" class="logo">
									<!--img src="./img/logo.png" alt=""-->
									<br /><h3 class="text-warning">{{ $merchant_detail->merchant_name }}</h3>
								</a>
							</div>
						</div>
						<!-- /LOGO -->

						<!-- SEARCH BAR -->
						<div class="col-md-6">
							{{ Form::open(['action'=>'\App\Modules\Frontend\Controllers\FrontendController@index', 'method'=>'post', 'class'=>'form-horizontal' ]) }}
							<div class="input-group search-text">								
      							<input type="text" class="form-control" placeholder="{{ __('Admin::base.keyword') }}">
      							<span class="input-group-btn">
        							<button class="btn btn-danger search-btn" type="button"><i class="fas fa-search"></i></button>
      							</span>      							
    						</div>
    						{{ Form::close() }}
							<!-- <input type="search" name="keyword" class="input-sm input-block" placeholder="search"> -->
							<!-- <div class="header-search text-center">
								<form>
									<small>
										<select style="width:160px" name="category" class="input-select">
										<option value="">{{ __('Frontend::frontend.category') }}</option>
										{{ Globe::product_category() }}
										</select> 
										<input class="input" placeholder="Search here">
										<button class="search-btn"><i class="fas fa-search"></i></button>
									</small>
								</form>
							</div> -->
						</div>
						<!-- /SEARCH BAR -->

						<!-- ACCOUNT -->
						<div class="col-md-3 clearfix">
							<div class="header-ctn">

								{{--@if(Auth::guard('users_guest')->check())--}}
								<!-- Wishlist -->
								<!-- <div>
									<a href="#">
										<i class="fa fa-heart-o"></i>
										<span>{{-- __('Frontend::frontend.wishlist') --}}&nbsp;</span> -->
										<!-- <div class="qty">2</div> -->
									<!-- </a>
								</div> -->
								<!-- /Wishlist -->
								{{--@endif--}}

								<!-- Cart -->
								<div class="dropdown">
									<!-- <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"> -->
										<a href="{{ route('frontend.cart') }}">
										<i class="fa fa-shopping-cart"></i>
										<span>{{ __('Frontend::frontend.your_cart') }}&nbsp;</span>
										@if(Cart::session('cart')->getTotalQuantity() > 0)
										<div class="qty cart-qty">{{Cart::session('cart')->getTotalQuantity()}}</div>
										@endif
									</a>
									<!-- <div class="cart-dropdown">
										<div class="cart-list">
											<div class="product-widget">
												<div class="product-img">
													<img src="./img/product01.png" alt="">
												</div>
												<div class="product-body">
													<h3 class="product-name"><a href="#">product name goes here</a></h3>
													<h4 class="product-price"><span class="qty">1x</span>$980.00</h4>
												</div>
												<button class="delete"><i class="fa fa-close"></i></button>
											</div>

											<div class="product-widget">
												<div class="product-img">
													<img src="./img/product02.png" alt="">
												</div>
												<div class="product-body">
													<h3 class="product-name"><a href="#">product name goes here</a></h3>
													<h4 class="product-price"><span class="qty">3x</span>$980.00</h4>
												</div>
												<button class="delete"><i class="fa fa-close"></i></button>
											</div>
										</div>
										<div class="cart-summary">
											<small>3 Item(s) selected</small>
											<h5>SUBTOTAL: $2940.00</h5>
										</div>
										<div class="cart-btns">
											<a href="#">View Cart</a>
											<a href="#">Checkout  <i class="fa fa-arrow-circle-right"></i></a>
										</div>
									</div> -->
								</div>
								<!-- /Cart -->

								<!-- Menu Toogle -->
								<div class="menu-toggle">
									<a href="#">
										<i class="fa fa-bars"></i>
										<span>Menu</span>
									</a>
								</div>
								<!-- /Menu Toogle -->
							</div>
						</div>
						<!-- /ACCOUNT -->
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<!-- /MAIN HEADER -->
		</header>
		<!-- /HEADER -->

		<!-- NAVIGATION -->
		<nav id="navigation">
			<!-- container -->
			<div class="container">
				<!-- responsive-nav -->
				<div id="responsive-nav">
					<!-- NAV -->
					<ul id="mjmz_menu" class="main-nav nav navbar-nav">
						<li><a href="http://{{ substr (Request::root(), 7) }}">{{ __('Frontend::frontend.home_page') }}</a></li>
						<!--li><a href="#">Hot Deals</a></li-->
						<li><a href="#">Categories</a></li>
						<!-- <li><a href="#">Laptops</a></li>
						<li><a href="#">Smartphones</a></li>
						<li><a href="#">Cameras</a></li>
						<li><a href="#">Accessories</a></li> -->
						@foreach($merchant_pages as $page)
						<li><a href="{{ URL::to('frontend/page', $page->merchant_page_slug) }}">{{ $page->merchant_page_title }}</a></li>
						@endforeach
						{{-- @if(Auth::guard('users_guest')->check()) --}}
						<!-- <li><a href="{{-- URL::to('frontend/order') --}}">{{-- __('Frontend::frontend.order_list') --}}</a></li> -->
						{{-- @endif --}}
						{{-- @if(!Auth::guard('users_guest')->check()) --}}
						<!-- <li><a href="{{-- URL::to('frontend/login') --}}"><i class="fas fa-key"></i> {{-- __('Frontend::frontend.signin') --}}</a></li> -->
						{{-- @endif --}}
					</ul>
					<!-- /NAV -->
				</div>
				<!-- /responsive-nav -->
			</div>
			<!-- /container -->
		</nav>
		<!-- /NAVIGATION -->

		<!-- BREADCRUMB -->
		<div id="breadcrumb" class="section">
			<!-- container -->
			<div class="container">
				@yield('breadcrumb')
				<!-- row -->
				<!--div class="row">
					<div class="col-md-12">
						<ul class="breadcrumb-tree">
							<li><a href="#">Home</a></li>
							<li><a href="#">All Categories</a></li>
							<li><a href="#">Accessories</a></li>
							<li class="active">Headphones (227,490 Results)</li>
						</ul>
					</div>
				</div-->
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /BREADCRUMB -->

		<div class="container">
		@include('common.message')
		</div>
		@yield('content')

		<!-- NEWSLETTER -->
		<div id="newsletter" class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<div class="newsletter">
							<p>{!! __('Frontend::frontend.sign_up_for_newsletter') !!}</p>
							<form>
								<input class="input email_subscribe" type="email" placeholder="{{ __('Frontend::frontend.enter_your_email') }}">
								<button class="newsletter-btn"><i class="fa fa-envelope"></i> {{ __('Frontend::frontend.subscribe') }}</button>
							</form>
							<ul class="newsletter-follow">
								<li>
									<a href="#"><i class="fa fa-facebook"></i></a>
								</li>
								<li>
									<a href="#"><i class="fa fa-twitter"></i></a>
								</li>
								<li>
									<a href="#"><i class="fa fa-instagram"></i></a>
								</li>
								<li>
									<a href="#"><i class="fa fa-pinterest"></i></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /NEWSLETTER -->

		<!-- FOOTER -->
		<footer id="footer">
			<!-- top footer -->
			<div class="section">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">{{ __('Frontend::frontend.contact_us') }}</h3>
								<!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut.</p> -->
								<ul class="footer-links">
									@if(!empty($merchant_config->merchant_config_address1))
									<li><a href="javascript:;"><i class="fa fa-map-marker"></i> {{ $merchant_config->merchant_config_address1 }} {{ $merchant_config->merchant_config_address2 }} {{ $merchant_config->merchant_config_address3 }} {{ $merchant_config->merchant_config_postcode }}</a></li>
									@endif
									@if(!empty($merchant_config->merchant_config_officeno))
									<li><a href="javascript:;"><i class="fa fa-phone"></i> {{ $merchant_config->merchant_config_officeno }}</a></li>
									@endif
									@if(!empty($merchant_config->merchant_config_email))
									<li><a href="mailto:{{ $merchant_config->merchant_config_email }}"><i class="fa fa-envelope-o"></i> {{ $merchant_config->merchant_config_email }}</a></li>
									@endif
								</ul>
							</div>
						</div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Categories</h3>
								<ul class="footer-links">
									<li><a href="#">Hot deals</a></li>
									<li><a href="#">Laptops</a></li>
									<li><a href="#">Smartphones</a></li>
									<li><a href="#">Cameras</a></li>
									<li><a href="#">Accessories</a></li>
								</ul>
							</div>
						</div>

						<div class="clearfix visible-xs"></div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Information</h3>
								<ul class="footer-links">
									<li><a href="#">About Us</a></li>
									<li><a href="#">Contact Us</a></li>
									<li><a href="#">Privacy Policy</a></li>
									<li><a href="#">Orders and Returns</a></li>
									<li><a href="#">Terms & Conditions</a></li>
								</ul>
							</div>
						</div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Service</h3>
								<ul class="footer-links">
									<li><a href="#">{{ __('Frontend::frontend.my_account') }}</a></li>
									<li><a href="#">{{ __('Frontend::frontend.your_cart') }}</a></li>
									<li><a href="#">Wishlist</a></li>
									<li><a href="#">Track My Order</a></li>
									<li><a href="#">Help</a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /row -->
				</div>
				<!-- /container -->
			</div>
			<!-- /top footer -->

			<!-- bottom footer -->
			<div id="bottom-footer" class="section">
				<div class="container">
					<!-- row -->
					<div class="row">
						<div class="col-md-12 text-center">
							<ul class="footer-payments">
								<li><a href="#"><i class="fa fa-cc-visa"></i></a></li>
								<li><a href="#"><i class="fa fa-credit-card"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-paypal"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-mastercard"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-discover"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-amex"></i></a></li>
							</ul>
							<span class="copyright">
								<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
								Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
							<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
							</span>
						</div>
					</div>
						<!-- /row -->
				</div>
				<!-- /container -->
			</div>
			<!-- /bottom footer -->
		</footer>
		<!-- /FOOTER -->

		<!-- jQuery Plugins -->
		<script src="{!! asset('front/electro/js/jquery.min.js') !!}"></script>
		<script src="{!! asset('front/electro/js/bootstrap.min.js') !!}"></script>
		<script src="{!! asset('front/electro/js/slick.min.js') !!}"></script>
		<script src="{!! asset('front/electro/js/nouislider.min.js') !!}"></script>
		<script src="{!! asset('front/electro/js/jquery.zoom.min.js') !!}"></script>
		<script src="{{ asset('front/electro/js/main.js') }}"></script>

		<!-- sweetalert -->
    	<script src="{!! asset('adminLTE/plugins/sweetalert2/dist/sweetalert2.js') !!}"></script>

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
			    window.location = "{{ route('frontend.logout') }}";
			  }
			})
      	});


    	// newsletter subsciption
    	$('.newsletter-btn').click(function(e) {

    		e.preventDefault();
    		$.ajax({

				type: "POST",
				url : '{{ URL::to("frontend/subscribe/newsletter") }}',
				data: {email: $('.email_subscribe').val()},
				dataType: "json",
				cache:false,
				success:
				function(data) {

					
					if(data.status == 'OK') {

						$('.email_subscribe').val('');
						
						swal({
							// title: '{{ __("Frontend::frontend.success") }}',
							text: data.message,
							type: "success",
							// timer: 2000
						});
					}

					if(data.status == 'INVALID') {
						swal({
							// title: '{{ __("Frontend::frontend.success") }}',
							text: data.errors,
							type: "error",
						});
					}

				}

			});
    	});

    	</script>

		@section('footer')
	    @show
	    @yield('script')

	    <!-- scripts related to specific page -->
	    @yield('page-script')

	</body>
</html>
