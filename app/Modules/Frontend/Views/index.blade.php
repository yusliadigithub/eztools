@extends('layouts.frontend.electro')

@section('meta_description')
<meta name="description" content="{{ $merchant_config->merchant_config_meta_description }}" />
<meta name="keywords" content="{{ $merchant_config->merchant_config_meta_keyword }}">
@stop

@section('content')
<!-- SECTION -->
<div class="section">
	<!-- container -->
	<div class="container">
		<!-- row -->
		<div class="row">
			<!-- ASIDE -->
			<div id="aside" class="col-md-3">
				<!-- aside Widget -->
				<div class="aside">
					<h3 class="aside-title">Categories</h3>
					<div class="checkbox-filter">

						<div class="input-checkbox">
							<input type="checkbox" id="category-1">
							<label for="category-1">
								<span></span>
								Laptops
								<small>(120)</small>
							</label>
						</div>

						<div class="input-checkbox">
							<input type="checkbox" id="category-2">
							<label for="category-2">
								<span></span>
								Smartphones
								<small>(740)</small>
							</label>
						</div>

						<div class="input-checkbox">
							<input type="checkbox" id="category-3">
							<label for="category-3">
								<span></span>
								Cameras
								<small>(1450)</small>
							</label>
						</div>

						<div class="input-checkbox">
							<input type="checkbox" id="category-4">
							<label for="category-4">
								<span></span>
								Accessories
								<small>(578)</small>
							</label>
						</div>

						<div class="input-checkbox">
							<input type="checkbox" id="category-5">
							<label for="category-5">
								<span></span>
								Laptops
								<small>(120)</small>
							</label>
						</div>

						<div class="input-checkbox">
							<input type="checkbox" id="category-6">
							<label for="category-6">
								<span></span>
								Smartphones
								<small>(740)</small>
							</label>
						</div>
					</div>
				</div>
				<!-- /aside Widget -->

				<!-- aside Widget -->
				<div class="aside">
					<h3 class="aside-title">Price</h3>
					<div class="price-filter">
						<div id="price-slider"></div>
						<div class="input-number price-min">
							<input id="price-min" type="number">
							<span class="qty-up">+</span>
							<span class="qty-down">-</span>
						</div>
						<span>-</span>
						<div class="input-number price-max">
							<input id="price-max" type="number">
							<span class="qty-up">+</span>
							<span class="qty-down">-</span>
						</div>
					</div>
				</div>
				<!-- /aside Widget -->

				<!-- aside Widget -->
				<div class="aside">
					<h3 class="aside-title">Brand</h3>
					<div class="checkbox-filter">
						<div class="input-checkbox">
							<input type="checkbox" id="brand-1">
							<label for="brand-1">
								<span></span>
								SAMSUNG
								<small>(578)</small>
							</label>
						</div>
						<div class="input-checkbox">
							<input type="checkbox" id="brand-2">
							<label for="brand-2">
								<span></span>
								LG
								<small>(125)</small>
							</label>
						</div>
						<div class="input-checkbox">
							<input type="checkbox" id="brand-3">
							<label for="brand-3">
								<span></span>
								SONY
								<small>(755)</small>
							</label>
						</div>
						<div class="input-checkbox">
							<input type="checkbox" id="brand-4">
							<label for="brand-4">
								<span></span>
								SAMSUNG
								<small>(578)</small>
							</label>
						</div>
						<div class="input-checkbox">
							<input type="checkbox" id="brand-5">
							<label for="brand-5">
								<span></span>
								LG
								<small>(125)</small>
							</label>
						</div>
						<div class="input-checkbox">
							<input type="checkbox" id="brand-6">
							<label for="brand-6">
								<span></span>
								SONY
								<small>(755)</small>
							</label>
						</div>
					</div>
				</div>
				<!-- /aside Widget -->

				<!-- aside Widget -->
				<!--div class="aside">
					<h3 class="aside-title">Top selling</h3>
					<div class="product-widget">
						<div class="product-img">
							<img src="./img/product01.png" alt="">
						</div>
						<div class="product-body">
							<p class="product-category">Category</p>
							<h3 class="product-name"><a href="#">product name goes here</a></h3>
							<h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
						</div>
					</div>

					<div class="product-widget">
						<div class="product-img">
							<img src="./img/product02.png" alt="">
						</div>
						<div class="product-body">
							<p class="product-category">Category</p>
							<h3 class="product-name"><a href="#">product name goes here</a></h3>
							<h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
						</div>
					</div>

					<div class="product-widget">
						<div class="product-img">
							<img src="./img/product03.png" alt="">
						</div>
						<div class="product-body">
							<p class="product-category">Category</p>
							<h3 class="product-name"><a href="#">product name goes here</a></h3>
							<h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
						</div>
					</div>
				</div-->
				<!-- /aside Widget -->
			</div>
			<!-- /ASIDE -->

			<!-- STORE -->
			<div id="store" class="col-md-9">
				<!-- store top filter -->
				<div class="store-filter clearfix">
					<div class="store-sort">
						<label>
							Sort By:
							<select class="input-select">
								<option value="0">Popular</option>
								<option value="1">Position</option>
							</select>
						</label>

						<label>
							Show:
							<select class="input-select">
								<option value="0">20</option>
								<option value="1">50</option>
							</select>
						</label>
					</div>
					<!--ul class="store-grid">
						<li class="active"><i class="fa fa-th"></i></li>
						<li><a href="#"><i class="fa fa-th-list"></i></a></li>
					</ul-->
				</div>
				<!-- /store top filter -->

				<!-- store products -->
				<div class="row">
					
					@if(count($products) > 0)
						@foreach($products as $product)

							<?php $count = 1 ?>

							<!-- product -->
							<a href="{{ route('frontend') }}/{{$product->product_slug}}">
							<div class="col-md-3 col-xs-6">
								<div class="product">
									<div class="product-img">
										<img src="{{ asset($product->image->upload_path.$product->image->upload_filename) }}" alt="">
									</div>
									<div class="product-body">
										<p class="product-category">{{ $product->type->product_type_desc }}</p>
										<h5 class="product-name"><a href="{{ route('frontend') }}/{{$product->product_slug}}">{{ $product->product_name }}</a></h5>
										<h4 class="product-price">
											@if($product->product_isstockcontrol == 1)
											{{ Globe::moneyFormat( $product->stock->where('product_stock_quantity', '>', 0)->min('product_stock_sale_price') ) }}
											<!--del class="product-old-price">$990.00</del-->
											@endif
										</h4>
										<!-- <div class="product-rating">
										</div>
										<div class="product-btns">
											<button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
											<button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to compare</span></button>
											<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
										</div> -->
									</div>
									<div class="add-to-cart">
										<!-- <button data-qty="1" data-id="{{-- encrypt( Globe::getStockWithMinPrice($product->product_id, 'product_stock_id') ) --}}" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> <small>{{ __('Frontend::frontend.add_to_cart') }}</small></button> -->
										<a class="btn btn-warning" href="{{ route('frontend') }}/{{$product->product_slug}}"><strong><i class="fas fa-info-circle"></i> {{ __('Frontend::frontend.product_detail') }}</strong></a>
									</div>
								</div>
							</div>
							</a>
							<!-- /product -->
							<?php $count++ ?>

							@if($count == 1)
								<div class="clearfix visible-sm visible-xs"></div>
								<?php $count = 0 ?>
							@endif

						@endforeach

						@else
							<p>No result(s) found</p>
						@endif
						
				</div>
				<!-- /store products -->

				<!-- store bottom filter -->
				<div class="store-filter clearfix">
					<!-- <span class="store-qty">Showing 20-100 products</span>
					<ul class="store-pagination">
						<li class="active">1</li>
						<li><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li><a href="#">4</a></li>
						<li><a href="#"><i class="fa fa-angle-right"></i></a></li>
					</ul> -->
					@if(count($products) > 0)
						{{ $products->links() }}
					@endif
				</div>
				<!-- /store bottom filter -->
			</div>
			<!-- /STORE -->
		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
</div>
<!-- /SECTION --> {{-- session($merchant_detail->merchant_domain)->merchant_id --}} {{-- Session::get($_SERVER['SERVER_NAME']) --}} {{-- Session::get('frontend_locale') --}} {{-- print_r(config('mail')) --}}

{{-- Cart::session('cart')->getContent() --}}

@endsection

@section('footer')
<script type="text/javascript">
	$('.add-to-cart-btn').click(function() {

		$.ajax({

			type: "GET",
			url : '{{ URL::to("frontend/cart/add") }}',
			data: {spid: $(this).data('id'), qty:$(this).data('qty')  },
			dataType: "json",
			cache:false,
			success:
			function(data) {

					$('.cart-qty').text('').text(data.cartitems);
				swal({
					title: '{{ __("Frontend::frontend.success") }}',
					text: '{{ __("Frontend::frontend.add_cart_success") }}',
					type: "success",
					timer: 1000
				});
			}

		});
	});
</script>
@stop