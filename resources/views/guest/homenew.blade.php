@extends('layouts.newheader')
@section('content')
 	<section id="slide-show">
		<div class="container-fluid m-0 p-0">

		<!-- Carousel -->
		<div id="demo" class="carousel slide" data-bs-ride="carousel">

			<!-- Indicators/dots -->
			<div class="carousel-indicators">
				<button type="button"  aria-label="First Slide" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
				<button type="button" aria-label="Second Slide" data-bs-target="#demo" data-bs-slide-to="1"></button>
				<button type="button" aria-label="Third Slide" data-bs-target="#demo" data-bs-slide-to="2"></button>
			</div>

			<!-- The slideshow/carousel -->
			<div class="carousel-inner">
				<div class="carousel-item active">
					<img src="{{ asset('imgs/slide-1.webp') }}" width="1512" height="480" alt="MAXGUN100" class="d-block" style="width:100%" loading="lazy" >
				</div>
					<div class="carousel-item ">
					<img src="{{ asset('imgs/slide-2.webp') }}" width="1512" height="480" alt="SILDIGRA" class="d-block" style="width:100%" loading="lazy" >
				</div>
			
					<div class="carousel-item ">
					<img src="{{ asset('imgs/slide-3.webp') }}" width="1512" height="480" alt="TADAGA" class="d-block" style="width:100%" loading="lazy" >
				</div>
			
			
			</div>

			<!-- Left and right controls/icons -->
			<button class="carousel-control-prev" type="button" aria-label="Prev Slide" data-bs-target="#demo" data-bs-slide="prev">
				<span class="carousel-control-prev-icon"></span>
			</button>
			<button class="carousel-control-next" type="button" aria-label="Next Slide" data-bs-target="#demo" data-bs-slide="next">
				<span class="carousel-control-next-icon"></span>
			</button>
		</div>

		</div>
	</section>



    <section class=" py-5 launches-sec">
        <div class="container">
            <div class="row">
                <h2 class="collectuion-title">NEW LAUNCHES</h2>
            </div>
            <div class="row">
            @foreach ($new_launches as $new) 
			   @if(!empty($new->image))
			   @php
				  $image_name = $new->image;
				  $name = explode(".",$new->image);
				  $new_image_name = $new->image;
				  $new_alt_img = $new->alt_img;
				@endphp
				@endif  
			    <div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="prodcut-box">
					@if($new->alt_img != NULL && $new->alt_img != '--' )
                        <div class="product-image bcd"><a href="{{ url('') }}/{!! !empty($new->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $new->slug}}"><img src="{{asset('storage/images/'.$new_alt_img.'')}}" alt="new-launches" width="266" height="266" loading="lazy"></a></div>
					@else
                        <div class="product-image abc"><a href="{{ url('') }}/{!! !empty($new->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $new->slug}}"><img src="{{asset('storage/'.$new_image_name.'')}}" alt="new-launches" width="266" height="266" loading="lazy"></a></div>
					@endif
                        <div class="product-tittle"><a href="{{ url('') }}/{!! !empty($new->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $new->slug}}">{{ $new->title }}</a></div>
                    </div>
                </div>
             @endforeach   
            </div>
        </div>
    </section>

    <section class=" py-5 selling-sec">
        <div class="container">
            <div class="row">
                <h2 class="collectuion-title">HOT SELLING</h2>
            </div>
            <div class="row">
                @foreach ($hot_offers as $hot)
				@if(!empty($hot->image))
				   @php
					  $image_name = $hot->image;
					  $name = explode(".",$image_name);
					  $hotnew_image_name = $image_name;
					  $hot_alt_img = $hot->alt_img;
					@endphp
				@endif
				<div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="prodcut-box">
					  @if($hot->alt_img != NULL && $hot->alt_img != '--' )
                        <div class="product-image"><a href="{{ url('') }}/{!! !empty($hot->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $hot->slug}}"><img src="{{asset('storage/images/'.$hot_alt_img.'')}}" alt="hot-selling" width="266" height="266" loading="lazy"></a></div>
					@else
                        <div class="product-image"><a href="{{ url('') }}/{!! !empty($hot->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $hot->slug}}"><img src="{{asset('storage/'.$hotnew_image_name.'')}}" alt="hot-selling" width="266" height="266" loading="lazy"></a></div>
					@endif
                            <div class="product-tittle"><a href="{{ url('') }}/{!! !empty($hot->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $hot->slug}}">{{ $hot->title }}</a></div>
                    
                    </div>
                </div>
           @endforeach    
            </div>
        </div>
    </section>
    <section class=" py-5 health-sec">
        <div class="container">
            <div class="row">
                <h2 class="collectuion-title">MEN HEALTH</h2>
            </div>
            <div class="row  d-flex align-items-center">
                <div class="col-lg-6 col-sm-12 my-4">
                    
                 <img class="w-100" src="{{ asset('imgs/men.png') }}" alt="Men-health"  width="636" height="531" loading="lazy" >
                    
                </div>
					@php $cnt=1; @endphp
					@foreach ($mens_health as $mens)
						@if($cnt > 2)
							@break;
						@endif
						@if(!empty($mens->image))
						   @php
							  $image_name = $mens->image;
							  $name = explode(".",$image_name);
							  $hotnew_image_name = $name[0].".webp";
							  $health_alt_img = $mens->alt_img;
							  $cnt++;
							@endphp
						@endif
						<div class="col-lg-3 col-sm-6 col-xs-12">  
						<div class="prodcut-box">
						@if($mens->alt_img != NULL && $mens->alt_img != '--' )
							<div class="product-image"><a href="{{ url('') }}/{!! !empty($hot->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $mens->slug}}"><img src="{{asset('storage/images/'.$health_alt_img.'')}}" alt="mens-health" width="266" height="266" loading="lazy"></a></div>
						@else
							<div class="product-image"><a href="{{ url('') }}/{!! !empty($hot->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $mens->slug}}"><img src="{{asset('storage/'.$hotnew_image_name.'')}}" alt="mens-health" width="266" height="266" loading="lazy"></a></div>
						@endif
                            <div class="product-tittle"><a href="{{ url('') }}/{!! !empty($mens->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $mens->slug}}">{{ $mens->title }}</a></div>
						</div>
						</div>
					@endforeach
            </div>
        </div>
    </section>
    <section class=" py-5 heaths-sec">
        <div class="container">
            <div class="row">
                <h2 class="collectuion-title">WOMEN HEALTH</h2>
            </div>
            <div class="row d-flex align-items-center ">
                @php $cnt=1; @endphp
					@foreach ($womens_health as $womens)
						@if($cnt > 2)
							@break;
						@endif
						@if(!empty($womens->image))
						   @php
							  $image_name = $womens->image;
							  $name = explode(".",$image_name);
							  $hotnew_image_name = $name[0].".webp";
							  $health_alt_img = $womens->alt_img;
							  $cnt++;
							@endphp
						@endif
						<div class="col-lg-3 col-sm-6 col-xs-12">  
						<div class="prodcut-box">
						@if($womens->alt_img != NULL && $womens->alt_img != '--' )
							<div class="product-image"><a href="{{ url('') }}/{!! !empty($womens->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $womens->slug}}"><img src="{{asset('storage/images/'.$health_alt_img.'')}}" alt="womens-health" width="266" height="266" loading="lazy"></a></div>
						@else
							<div class="product-image"><a href="{{ url('') }}/{!! !empty($womens->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $womens->slug}}"><img src="{{asset('storage/'.$hotnew_image_name.'')}}" alt="womens-health" width="266" height="266" loading="lazy"></a></div>
						@endif
                            <div class="product-tittle"><a href="{{ url('') }}/{!! !empty($womens->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $womens->slug}}">{{ $womens->title }}</a></div>
						</div>
						</div>
					@endforeach
               

                
                    <div class="col-lg-6 col-sm-12 my-4">
                        
                     <img class="w-100" src="{{ asset('imgs/wu.png') }}" alt="womens-health" width="636" height="531" loading="lazy" >
                        
                    </div>
            </div>
        </div>
    </section>

    <section class="bg-blue-outer ">
		<div class="container">
			<div class="row">
				<h1 class="text-center"><strong>FEATURED PRODUCTS</strong></h1>

				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item" role="presentation">
						<a class="nav-link active" id="simple-tab-0" data-bs-toggle="tab" href="#simple-tabpanel-0" role="tab" aria-controls="simple-tabpanel-0" aria-selected="true">HOT OFFERS</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link" id="simple-tab-1" data-bs-toggle="tab" href="#simple-tabpanel-1" role="tab" aria-controls="simple-tabpanel-1" aria-selected="false">NEW LAUNCHES</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link" id="simple-tab-2" data-bs-toggle="tab" href="#simple-tabpanel-2" role="tab" aria-controls="simple-tabpanel-2" aria-selected="false">WOMEN HEALTH</a>
					</li>
					<li class="nav-item" role="presentation">
						<a class="nav-link" id="simple-tab-3" data-bs-toggle="tab" href="#simple-tabpanel-3" role="tab" aria-controls="simple-tabpanel-3" aria-selected="false">MEN HEALTH</a>
					</li>
				</ul>
				<div class="tab-content pt-5" id="tab-content">
					<div class="tab-pane active" id="simple-tabpanel-0" role="tabpanel" aria-labelledby="simple-tab-0">
						<div class="row">
							@foreach ($hot_offers as $hot)
								@if(!empty($hot->image))
								   @php
									  $image_name = $hot->image;
									  $name = explode(".",$image_name);
									  $hotnew_image_name = $hot->image;
									  $hot_alt_img = $hot->alt_img;
									@endphp
								@endif
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="prodcut-box">
									 @if($hot->alt_img != NULL && $hot->alt_img != '--' )
										<div class="product-image"><a href="{{ url('') }}/{!! !empty($hot->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $hot->slug}}"><img src="{{asset('storage/images/'.$hot_alt_img.'')}}" alt="Feature Products" width="266" height="266" loading="lazy"></a></div>
									@else
										<div class="product-image"><a href="{{ url('') }}/{!! !empty($hot->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $hot->slug}}"><img src="{{asset('storage/'.$hotnew_image_name.'')}}" alt="Feature Products" width="266" height="266" loading="lazy"></a></div>
									@endif
											<div class="product-tittle"><a href="{{ url('') }}/{!! !empty($hot->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $hot->slug}}">{{ $hot->title }}</a></div>
									
									</div>
								</div>
						   @endforeach
							
						</div>
					</div>
					<div class="tab-pane" id="simple-tabpanel-1" role="tabpanel" aria-labelledby="simple-tab-1">
						<div class="row">
							@foreach ($new_launches as $new) 
							   @if(!empty($new->image))
							   @php
								  $image_name = $new->image;
								  $name = explode(".",$new->image);
								  $new_image_name = $new->image;
								  $new_alt_img = $new->alt_img;
								@endphp
								@endif  
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="prodcut-box">
									@if($new->alt_img != NULL && $new->alt_img != '--' )
										<div class="product-image"><a href="{{ url('') }}/{!! !empty($new->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $new->slug}}"><img src="{{asset('storage/images/'.$new_alt_img.'')}}" alt="new-launches" width="266" height="266" loading="lazy"></a></div>
									@else
										<div class="product-image"><a href="{{ url('') }}/{!! !empty($new->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $new->slug}}"><img src="{{asset('storage/'.$new_image_name.'')}}" alt="new-launhes" width="266" height="266" loading="lazy"></a></div>
									@endif
										<div class="product-tittle"><a href="{{ url('') }}/{!! !empty($new->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $new->slug}}">{{ $new->title }}</a></div>
									</div>
								</div>
							 @endforeach   
						</div>
					</div>
					<div class="tab-pane" id="simple-tabpanel-2" role="tabpanel" aria-labelledby="simple-tab-2">
						<div class="row">
							@foreach ($womens_health as $womens)
								
								@if(!empty($womens->image))
								   @php
									  $image_name = $womens->image;
									  $name = explode(".",$image_name);
									  $hotnew_image_name = $name[0].".webp";
									  $health_alt_img = $womens->alt_img;
									 
									@endphp
								@endif
								<div class="col-lg-3 col-sm-6 col-xs-12">  
								<div class="prodcut-box">
									@if($womens->alt_img != NULL && $womens->alt_img != '--' )
										<div class="product-image"><a href="{{ url('') }}/{!! !empty($womens->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $womens->slug}}"><img src="{{asset('storage/images/'.$health_alt_img.'')}}" alt="womens health" width="266" height="266" loading="lazy"></a></div>
									@else
										<div class="product-image"><a href="{{ url('') }}/{!! !empty($womens->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $womens->slug}}"><img src="{{asset('storage/'.$hotnew_image_name.'')}}" alt="womens health" width="266" height="266" loading="lazy"></a></div>
									@endif
									<div class="product-tittle"><a href="{{ url('') }}/{!! !empty($womens->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $womens->slug}}">{{ $womens->title }}</a></div>
								</div>
								</div>
							@endforeach
						</div>
					</div>
					<div class="tab-pane" id="simple-tabpanel-3" role="tabpanel" aria-labelledby="simple-tab-2">
						<div class="row">
						@foreach ($mens_health as $mens)
							
							@if(!empty($mens->image))
							   @php
								  $image_name = $mens->image;
								  $name = explode(".",$image_name);
								  $hotnew_image_name = $name[0].".webp";
								  $health_alt_img = $mens->alt_img;
								  
								@endphp
							@endif
							<div class="col-lg-3 col-sm-6 col-xs-12">  
							<div class="prodcut-box">
								@if($mens->alt_img != NULL && $mens->alt_img != '--' )
									<div class="product-image"><a href="{{ url('') }}/{!! !empty($mens->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $mens->slug}}"><img src="{{asset('storage/images/'.$health_alt_img.'')}}" alt="Mens Health" width="266" height="266" loading="lazy"></a></div>
								@else
									<div class="product-image"><a href="{{ url('') }}/{!! !empty($mens->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $mens->slug}}"><img src="{{asset('storage/'.$hotnew_image_name.'')}}" alt="Mens Health" width="266" height="266" loading="lazy"></a></div>
								@endif
								<div class="product-tittle"><a href="{{ url('') }}/{!! !empty($mens->name) ? 'product/catproducts' : 'product/viewdetail' !!}/{{ $mens->slug}}">{{ $mens->title }}</a></div>
							</div>
							</div>
						@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

    <section class=" py-5 medicine-sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                <div class="box-one">
                    <div class="img-content d-flex align-items-start justify-content-between">
                        <span><img src="{{ asset('imgs/med.png') }}" alt="Sildalist Strong 100" width="165" height="221" loading="lazy"></span><div class="right-content">
                            <h2>Sildalist Strong </h2>
                            <h6>(Sildenafil 100mg + Tadalafil 40mg)</h6>
                            <p>Sildalist strong 140mg is a potent Medicine that helps in treating ED in men. It contains the active substance of Sildenafil Citrate 100mg and Tadalafil 40mg, used to treat sexual disorders in men. This Medicine is manufactured for RSM Enterprises</p>
                        </div>
                    </div>
                    <a href="product/viewdetail/sildalist-strong"><button class="w-100 large-btn">Learn More</button></a>
                </div>    
                </div>
                <div class="col-lg-6">
                    <div class="box-one">
                        <div class="img-content d-flex align-items-start justify-content-between">
                            <span><img src="{{ asset('imgs/med2.png') }}"alt="Sildenafil Citrate 100"  width="165" height="221" loading="lazy"></span><div class="right-content">
                                <h2>Sildalist </h2>
                                <h6>(Sildenafil Citrate 100mg + Tadalafil 20mg)</h6>
                                <p>Sildenafil Citrate & Tadalafil (Sildalist) manufactured by RSM Enterprises is packed with Tadalafil and Sildenafil citrate which can handle the most severe problem of erectile dysfunction. It increases blood flow in penis causes erection. By using Sildenafil Citrate.</p>
                            </div>
                        </div>
                        <a href="product/viewdetail/sildenafil-citrate-tadalafil-23"><button class="w-100 large-btn">Learn More</button></a>
                    </div>    
				</div>
			</div>
		</div>
	</section>


    <section class=" py-5 blog-sec">
        <div class="container">
            <div class="row">
                <h2 class="collectuion-title">Blog</h2>
            </div>
            <div class="row">
			@foreach ($blogs as $blog) 
                <div class="col-lg-4">  <div class="blog-box">
                    <div class="product-image"><a href="{{url('blog/'.$blog->slug) }}"><img class="w-100" src="{{ safe_storage_url($blog->image) }}" alt="{{ $blog->title }}" width="376" height="145" loading="lazy" ></a></div>
                    <p class="date">{{ date('F d, Y h:i A', strtotime($blog->created_at)) }}</p>
                    <div class="product-tittle"><a href="{{ url('blog/'.$blog->slug) }}">{{ $blog->title}}</a></div>
                        <a href="{{ url('blog/'.$blog->slug) }}"><button class="blog-btn">Read More</button></a>
                </div></div>
            @endforeach    
            </div>
        </div>
    </section>
@stop