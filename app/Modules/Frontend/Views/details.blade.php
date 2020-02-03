@extends('layouts.frontend.electro')

@section('meta_description')
<meta name="description" content="{!! Globe::truncateString(strip_tags($product->product_content), 200) !!}" />
<!-- <meta name="keywords" content="{{-- $merchant_config->merchant_config_meta_keyword --}}"> -->
@stop

@section('breadcrumb')
@stop()

@section('content')
<div class="container">
	
	<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<!-- Product main img -->
					<div class="col-md-5 col-md-push-2">
						<div id="product-main-img">
							@foreach($product->activeStock(1)->multiimage as $image)
							<div class="product-preview">
								<img src="{{ asset($image->upload_path.$image->upload_filename) }}" alt=""> 
							</div>
							@endforeach
						</div>
					</div>
					<!-- /Product main img -->

					<!-- Product thumb imgs -->
					<div class="col-md-2  col-md-pull-5">
						<div id="product-imgs">
							@foreach($product->activeStock(1)->multiimage as $image)
							<div class="product-preview">
								<img src="{{ asset($image->upload_path.$image->upload_filename) }}" alt="">
							</div>
							@endforeach
						</div>
					</div>
					<!-- /Product thumb imgs -->

					<!-- Product details -->
					<div class="col-md-5">
						<div class="product-details">
							<h2 class="product-name">{{ $product->product_name }}</h2>
							<div>
								<div class="product-rating">
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star-o"></i>
								</div>
								<a href="javascript:;" data-toggle="modal" data-pid="{{ encrypt($product->product_id) }}" data-title="{{ $product->product_name }}" data-target="#review-modal" class="review-link">{{count($reviews)}} Review(s) | Add your review</a>
							</div>
							<div class="product-detail-price">
								<h3 class="product-price">
									{{ Globe::moneyFormat($productWithMinPrice['product_stock_market_price']) }}
									@if( $productWithMinPrice['product_stock_sale_price'] < $productWithMinPrice['product_stock_market_price'])
									<del class="product-old-price">{{ Globe::moneyFormat($productWithMinPrice['product_stock_market_price']) }}</del>
									@endif
								</h3>
								<span class="product-available">									
										{{ $productWithMinPrice->product_stock_quantity > 0 ? __('Frontend::frontend.in_stock') : __('Frontend::frontend.out_stock') }}
								</span>
							</div>
							<p class="product-desc">{!! Globe::truncateString($product->product_content, 120) !!}</p>

							<!-- <div class="product-options">
								<label>
									Size
									<select class="input-select">
										<option value="0">X</option>
									</select>
								</label>
								<label>
									Color
									<select class="input-select">
										<option value="0">Red</option>
									</select>
								</label>
							</div> -->

							@foreach($product->activeStock as $stock)								
								<div class="form-check getItem">
								    <input class="form-check-input stockid" name="stockid" type="radio" id="4511{{ $stock->product_stock_id }}003" data-id="{{ encrypt($stock->product_stock_id) }}">
								    <label class="form-check-label " for="4511{{ $stock->product_stock_id }}003">{{ $stock->product_stock_description }}</label>
								</div>
							@endforeach
							<hr>
							<div class="add-to-cart">
								<div class="qty-label">
									Qty
									<div class="input-number">
										<input type="number" class="item-qty" value="1">
										<span class="qty-up">+</span>
										<span class="qty-down">-</span>
									</div>
								</div>
								<button disabled="" type="button" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> {{ __('Frontend::frontend.add_to_cart') }}</button>
							</div>							

							<!-- <ul class="product-btns">
								<li><a href="#"><i class="fa fa-heart-o"></i> add to wishlist</a></li>
								<li><a href="#"><i class="fa fa-exchange"></i> add to compare</a></li>
							</ul> -->

							<ul class="product-links">
								<li>Category:</li>
								<li><a href="#">{{ $product->type->product_type_desc }}</a></li>
								<!--li><a href="#">Accessories</a></li-->
							</ul>

							<ul class="product-links">
								<li>Share:</li>
								<li><a href="#"><i class="fa fa-facebook"></i></a></li>
								<li><a href="#"><i class="fa fa-twitter"></i></a></li>
								<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
								<li><a href="#"><i class="fa fa-envelope"></i></a></li>
							</ul>

						</div>
					</div>
					<!-- /Product details -->

					<!-- Product tab -->
					<div class="col-md-12">
						<div id="product-tab">
							<!-- product tab nav -->
							<ul class="tab-nav">
								<li class="active"><a data-toggle="tab" href="#tab1">Description</a></li>
								<!-- <li><a data-toggle="tab" href="#tab2">Details</a></li> -->
								<li><a data-toggle="tab" href="#tab3">Reviews ({{count($reviews)}})</a></li>
							</ul>
							<!-- /product tab nav -->

							<!-- product tab content -->
							<div class="tab-content">
								<!-- tab1  -->
								<div id="tab1" class="tab-pane fade in active">
									<div class="row">
										<div class="col-md-12">
											<p>{!! $product->product_content !!}</p>
										</div>
									</div>
								</div>
								<!-- /tab1  -->

								<!-- tab3  -->
								<div id="tab3" class="tab-pane fade in">
									<div class="row">

										@if( count($reviews) > 0 )
										<!-- Rating -->
										<div class="col-md-3">
											<div id="rating">
												<div class="rating-avg">
													<span>{{ $reviews->avg('product_review_rating') }}</span> 
													<div class="rating-stars">
														
														<?php 
														$avgRatingLeft = 0;
														if($reviews->avg('product_review_rating') < 5 ) {
															$avgRatingLeft = 5 - $reviews->avg('product_review_rating');
														} 
														
														echo str_repeat('<i class="fa fa-star"></i>', $reviews->avg('product_review_rating'));
														echo str_repeat('<i class="fa fa-star-o"></i>', $avgRatingLeft);
														?>
													</div>
												</div>
												<ul class="rating">
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
														</div>
														<div class="rating-progress">
															<div style="width: 80%;"></div>
														</div>
														<span class="sum">{{ $product->reviews->where('product_review_rating', 5)->count('product_review_rating') }}</span>
													</li>
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="rating-progress">
															<div style="width: 60%;"></div>
														</div>
														<span class="sum">{{ $product->reviews->where('product_review_rating', 4)->count('product_review_rating') }}</span>
													</li>
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="rating-progress">
															<div></div>
														</div>
														<span class="sum">{{ $product->reviews->where('product_review_rating', 3)->count('product_review_rating') }}</span>
													</li>
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="rating-progress">
															<div></div>
														</div>
														<span class="sum">{{ $product->reviews->where('product_review_rating', 2)->count('product_review_rating') }}</span>
													</li>
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="rating-progress">
															<div></div>
														</div>
														<span class="sum">{{ $product->reviews->where('product_review_rating', 1)->count('product_review_rating') }}</span>
													</li>
												</ul>
											</div>
										</div>
										<!-- /Rating -->
										@else 
											<div class="col-md-3"><p>no reviews found</p></div>
										@endif

										<!-- Reviews -->
										<div class="col-md-9">
											<div id="reviews">
												<ul class="reviews">
													@foreach( $reviews as $review )
													<li>
														<div class="review-heading">
															
															<p class="date">{{ date('d M Y, h:i A',strtotime($review->created_at)) }}</p>
															<div class="review-rating">
																<?php

																	$total = 5;
																	if($review->product_review_rating < $total) {
																		$empty = $total - $review->product_review_rating;
																	} else {
																		$empty = 0;
																	}

																	echo str_repeat('<i class="fa fa-star"></i>', $review->product_review_rating);
																	echo str_repeat('<i class="fa fa-star-o empty"></i>', $empty);
																?>


															</div>
														</div>
														<div class="review-body">
															<h5 class="name"><small>{{ $review->guest->guest_fullname }}</small></h5>
															<p>{{ $review->product_review_remarks }}</p>
														</div>
													</li>
													@endforeach													
												</ul>
												<ul class="reviews-pagination">
													<!-- <li class="active">1</li>
													<li><a href="#">2</a></li>
													<li><a href="#">3</a></li>
													<li><a href="#">4</a></li>
													<li><a href="#"><i class="fa fa-angle-right"></i></a></li> -->
													{{ $reviews->links() }}
												</ul>
											</div>
										</div>
										<!-- /Reviews -->

										<!-- Review Form -->
										<!-- <div class="col-md-3">
											<div id="review-form">
												<form class="review-form">
													<input class="input" type="text" placeholder="Your Name">
													<input class="input" type="email" placeholder="Your Email">
													<textarea class="input" placeholder="Your Review"></textarea>
													<div class="input-rating">
														<span>Your Rating: </span>
														<div class="stars">
															<input id="star5" name="rating" value="5" type="radio"><label for="star5"></label>
															<input id="star4" name="rating" value="4" type="radio"><label for="star4"></label>
															<input id="star3" name="rating" value="3" type="radio"><label for="star3"></label>
															<input id="star2" name="rating" value="2" type="radio"><label for="star2"></label>
															<input id="star1" name="rating" value="1" type="radio"><label for="star1"></label>
														</div>
													</div>
													<button class="primary-btn">Submit</button>
												</form>
											</div>
										</div> -->
										<!-- /Review Form -->
									</div>
								</div>
								<!-- /tab3  -->
							</div>
							<!-- /product tab content  -->
						</div>
					</div>
					<!-- /product tab -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

		@if( count($related_products) > 0 )
		<!-- Section -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">

					<div class="col-md-12">
						<div class="section-title text-center">
							<h3 class="title">{{ __('Frontend::frontend.related_product') }}</h3>
						</div>
					</div>

					@foreach($related_products as $related_product)
						<?php $count = 1 ?>
						<!-- product -->
						<a href="{{ route('frontend') }}/{{$related_product->product_slug}}">
						<div class="col-md-3 col-xs-6">
							<div class="product">
								<div class="product-img">
									<img src="{{ asset($related_product->image->upload_path.$related_product->image->upload_filename) }}" alt="">
									<div class="product-label">
										<!-- <span class="sale">-30%</span> -->
									</div>
								</div>
								<div class="product-body">
									<p class="product-category">{{ $related_product->type->product_type_desc }}</p>
									<h3 class="product-name"><a href="{{ route('frontend') }}/{{$related_product->product_slug}}">{{ Globe::truncateString($related_product->product_name,20) }}</a></h3>
									<h4 class="product-price">
										{{ $related_product->activeStock(1)['product_stock_sale_price'] }}
										@if( $related_product->activeStock(1)['product_stock_sale_price'] < $related_product->activeStock(1)['product_stock_market_price'])
										<del class="product-old-price">{{ $related_product->activeStock(1)['product_stock_market_price'] }}</del>
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
								<!-- <div class="add-to-cart">
									<button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to cart</button>
								</div> -->
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
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /Section -->
		@endif

</div>

<!-- Modal -->
<div id="review-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
    	
    	<form method="post" class="review-form">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title review-modal-title">Modal Header</h4>
	      </div>
	      <div class="modal-body">
	      	@if(Auth::guard('users_guest')->check())
	        <textarea name="review_remark" required="" class="input" placeholder="Your Review"></textarea>
	        <div class="input-rating">
				<span>Your Rating: </span>
				<div class="stars">
					<input id="star5" name="review_rating" value="5" type="radio"><label for="star5"></label>
					<input id="star4" name="review_rating" value="4" type="radio"><label for="star4"></label>
					<input id="star3" name="review_rating" value="3" type="radio"><label for="star3"></label>
					<input id="star2" name="review_rating" value="2" type="radio"><label for="star2"></label>
					<input id="star1" name="review_rating" value="1" type="radio"><label for="star1"></label>
				</div>
			</div>
			@else
				<p class="text-warning"><i class="fa fa-warning"></i> Please <a href="{{ route('frontend.login') }}">login</a> to review this product.</p>
			@endif
	      </div>
	      <div class="modal-footer">
	      	@if(Auth::guard('users_guest')->check())
	      	<button type="submit" class="primary-btn">Submit</button>
	      	@endif
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </form>
    </div>

  </div>
</div>

@stop()

@section('footer')
<script type="text/javascript">

	$('#product-tab a[href="' + window.location.hash + '"]').tab('show');

	$('.getItem').on('click',function(){

		var checkedid = $( "input[name=stockid]:checked", this ).data('id');

		// console.log(checkedid);

		$.ajax({
			type: "GET",
			url : '{{ URL::to("frontend/stock/detail") }}',
			// data: {stock_id: $( "input[name=stockid]:checked", this ).data('id')},
			data: {stock_id: checkedid},
			dataType: "json",
			cache:false,
			success:
			function(data) {
				$('.product-detail-price').html('');
				$('.add-to-cart-btn').remove();
				$('.add-to-cart-btn').attr('disabled', false);
				$('.product-detail-price').html(data.price);
				$(data.add_to_cart_btn).insertAfter("div.qty-label");
			}

		});
	});

	$(document).on('click', '.add-to-cart-btn', function(){
		
		$.ajax({

			type: "GET",
			url : '{{ URL::to("frontend/cart/add") }}',
			data: {spid: $(this).data('id'), qty: $('.item-qty').val() },
			dataType: "json",
			cache:false,
			success:
			function(data) {
				swal({
					title: '{{ __("Frontend::frontend.success") }}',
					text: '{{ __("Frontend::frontend.add_cart_success") }}',
					type: "success",
					timer: 1000
				});
			}

		});

	});

	$('.review-link').on('click', function(){
		$('.review-modal-title').text( $(this).data('title') );
		$('.review-form').attr('action', '{{ URL::to("frontend/product/review") }}/'+ $(this).data('pid')  );
	});

</script>
@stop
