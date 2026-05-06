@extends('layouts.newheader')
@section('title', 'Blog - RSM Multilink LLP')


@section('content')
 <section class=" py-12 single-blog-sec">
        <div class="container">
            <div class="row">
                <h2 class="collectuion-title">Blog</h2>
            </div>
            <div class="row">
			@foreach($blogs as $blog)
				<div class="col-lg-4"> 
					<div class="blog-box">
						<div class="product-image"><a href="{{ url('blog/'.$blog->slug) }}"><img class="w-100" src="{{ safe_storage_url($blog->image) }}" loading="lazy" alt="{{ $blog->title }}"></a></div>
						<p class="date">{{ date('F d, Y h:i A', strtotime($blog->created_at)) }}</p>
						<div class="product-tittle"><a href="{{ url('blog/'.$blog->slug) }}">{{ $blog->title}}</a></div>
						
						<a class="post-classic-media" href="{{ url('blog/'.$blog->slug) }}"><button class="blog-btn">Read More</button></a>
					</div>
				</div>
			@endforeach
           </div>
           
           <!-- Pagination -->
           <div class="row">
               <div class="col-12">
                   {{ $blogs->links('vendor.pagination.bootstrap-4') }}
               </div>
           </div>
        </div>
    </section>

    <link rel="icon" type="image/png" href="{{ asset('images/rsm-Logo.png') }}">
   
@endsection