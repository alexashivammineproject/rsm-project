<!doctype html>
<html lang="en">
	<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="user-scalable=no,initial-scale=1,maximum-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ url()->full() }}" />
    <!-- SEO Title -->

    @if (request()->route() && request()->route()->getName() == 'homepage')

        <title> Premium ED Products by RSM Multilink: Sildenafil,Tadalafil & Dapoxetine </title>
    @else
        @if (trim($__env->yieldContent('title')))

            <title>@yield('title') </title>
        @else
            <title>
                @if (trim($__env->yieldContent('pageTitle')) == '123')
                    Manufacturers & Exporter of ed products(Erectile dysfunction)
                @elseif (trim($__env->yieldContent('pageTitle')) == 'Men&#039;s Health products | Sildenafil citrate 100mg- RSM enterprises')
                    Men's Health products | Sildenafil citrate 100mg- RSM enterprises
                @else
                    @yield('pageTitle', app('global_settings')[0]['setting_value'])
                @endif
            </title>

        @endif


    @endif
<link rel="icon" type="image/x-icon" href="/imgs/favicon.ico">
    <!-- SEO Meta Descrition -->

    @if (request()->route() && request()->route()->getName() == 'homepage')
        <meta name="description"
            content="Discover top-quality ED Products produced by RSM Multilink LLP.Explore our range of Sildenafil,Tadalafil,Vardenafil & Dapoxetine products & experience enhanced performance. Visit us now at rsmmultilink.com.">
    @else
        <meta name="description" content="@yield('pageDescription', app('global_settings')[1]['setting_value'])">
    @endif




    <meta name="keywords"
        content="@if (trim($__env->yieldContent('pageKeyword')) == '') Manufacturers & Exporter of ed products @else @yield('pageKeyword') @endif">

    @if (request()->route() && request()->route()->getName() == 'homepage')
        <meta itemprop="name" content="RSM Multilink LLP Produced Sildenafil, Tadalafil & Dapoxetine Tablets">
        <meta itemprop="description"
            content=" RSM Multilink LLP Produced the ED products like sildenafil, Tadalafil, Vardenafil, Dapoxetine products like many more. Visit Us @rsmmultilink.com">
    @else
        <meta itemprop="name" content="@yield('pageTitle', app('global_settings')[0]['setting_value'] . ' - ' . app('global_settings')[1]['setting_value'])">
        <meta itemprop="description" content="@yield('pageDescription', app('global_settings')[1]['setting_value'])">
    @endif


    <meta itemprop="image" content="http://www.example.com/image.jpg">


    <meta name="twitter:card" content="summary_large_image">
    @if (request()->route() && request()->route()->getName() == 'homepage')
        <meta name="twitter:title" content="RSM Multilink LLP Produced Sildenafil, Tadalafil & Dapoxetine Tablets">
        <meta name="twitter:description"
            content="RSM Multilink LLP Produced the ED products like sildenafil, Tadalafil, Vardenafil, Dapoxetine products like many more. Visit Us @rsmmultilink.com">
    @else
        <meta name="twitter:title" content="@yield('pageTitle', app('global_settings')[0]['setting_value'] . ' - ' . app('global_settings')[1]['setting_value'])">
        <meta name="twitter:description" content="@yield('pageDescription', app('global_settings')[1]['setting_value'])">
    @endif
    <meta name="twitter:image:src" content="http://www.example.com/image.jpg">

    @if (request()->route() && request()->route()->getName() == 'homepage')
        <meta property="og:title" content="RSM Multilink LLP Produced Sildenafil, Tadalafil & Dapoxetine Tablets" />
        <meta property="og:description"
            content="RSM Multilink LLP Produced the ED products like sildenafil, Tadalafil, Vardenafil, Dapoxetine products like many more. Visit Us @rsmmultilink.com" />
    @else
        <meta property="og:title" content="@yield('pageTitle', app('global_settings')[0]['setting_value'] . ' - ' . app('global_settings')[1]['setting_value'])" />
        <meta property="og:description" content="@yield('pageDescription', app('global_settings')[1]['setting_value'])" />
    @endif

    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ url()->full() }}" />
    <meta property="og:image" content="@yield('pageImage', 'http://placehold.it/700x700')" />
    <meta property="og:site_name" content="{{ app('global_settings')[0]['setting_value'] }}" />

    <meta name="google-site-verification" content="7SppIxuOHPzQLwEgzymK_a8otHxqN5HktlkwuQB6oK0" />
    <!-- Google tag (gtag.js) - Deferred to prevent blocking -->
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-4YHLRKGPXZ');
        
        // Load GA asynchronously after page load
        window.addEventListener('load', function() {
            var script = document.createElement('script');
            script.async = true;
            script.src = 'https://www.googletagmanager.com/gtag/js?id=G-4YHLRKGPXZ';
            document.head.appendChild(script);
        });
    </script>
    <!--@if(request()->url() != 'https://rsmmultilink.com')
     {{-- recaptcha loaded per-page --}} 
    @endif -->
        <link rel="stylesheet" href="{{ asset('css/fontawesome-all.min.css') }}">
        <!-- FontAwesome CDN Backup with timeout fallback -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" media="print" onload="this.media='all'" />
        <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" /></noscript>
		<link rel="stylesheet" href="{{ asset('css2/style.css') }}">
		<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" >
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	</head>
	<style>
	    
         .mobile-header .whatsap-chat img { 
             object-fit: contain; 
             max-width: 35px; 
             width: 100%; 
         } 
          .mobile-header-fixed { 
            position: fixed;
            top: 0;
            z-index: 9;
            background: #fff; 
         } 
         .mobile-header { 
             display: none; 
         } 
         button.navbar-toggler { 
         border: 0; 
         } 
         .mobile-header .nav-outer { 
         background-color: transparent; 
         } 
         .navbar-toggler:focus { 
         box-shadow: none; 
         } 
         .mobile-header .search { 
         box-shadow: none; 
         margin-top: 20px; 
         } 
         .navbar-collapse.justify-content-center.collapse.show { 
         background-color: #002c49; 
         position: relative; 
         z-index: 2; 
         } 
         .mobile-header .brand-bar { 
         position: relative;
         } 
         .toogle-mob { 
         position: absolute; 
         width: 100%; 
         right: 0; 
         top: 30px; 
         } 
         .mobile-header a.nav-link { 
         text-align: left; 
         } 
         .mobile-header .search input { 
         width: 100%; 
         box-shadow: 0 0 7px -4px #00000078; 
         border-top-right-radius: 0px; 
         border-bottom-right-radius: 0px; 
         } 
         .phone-option i { 
         font-size: 24px; 
         } 
         @media (max-width: 991px) { 
         .mobile-header { 
         display: block; 
         } 
         .desktop-version { 
         display: none; 
         }
         /* Hide phone and WhatsApp icons in mobile header */
         .mobile-header .header-contact-info {
         display: none !important;
         }
         }
         
         @media (max-width: 575px) {
             
        .toogle-mob .container {
                   padding: 0;
           }
         }
         @media (max-width: 420px) {
          .toogle-mob {
                  top: 15px;
          }
         }
    </style>
    
<!-- Google Tag Manager - Deferred to prevent blocking -->
<script>
// Defer GTM loading until after page load
window.addEventListener('load', function() {
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-M5VVXQD4');
});
</script>
<!-- End Google Tag Manager -->
	<body>
	    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M5VVXQD4"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <header >
        <div class="topbar">
            <div class="container">
                <div class="row">
                    <div class="col-lg-11 col-sm-12">
                        <p>Visit our USFDA APPROVED Spices Division chilli</p>
                    </div>
                    <div class="col-lg-1 col-sm-12">
                        <div class="translator" id="google_translate_element"> 
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="desktop-version">    
        <div class="brand-bar">
            <div class="container">
                <div class="row">
                    <div class="col-4">
                        <div class="brand-logo">
                            <a href="/"><img src="/imgs/logo.png" alt="brand-logo"  width ="101" height="90" loading="lazy" /></a>
                        </div>
                    </div>
                    <div class="col-8 d-flex right-head align-items-center  justify-content-between">
                        <div class="search">
                            <form class="form-inline my-lg-0" id="headerSearchForm" action="{{ url('/searchpage') }}" method="GET">
                                <input class="form-control mr-sm-2" type="search" id="headerSearchInput" value="{!! !empty($_GET['s']) ? $_GET['s'] : '' !!}" name="s" placeholder="Search" aria-label="Search" />
                                <button class="btn" type="submit" aria-label="Search Button">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.11115 16.2223C13.0385 16.2223 16.2223 13.0385 16.2223 9.11115C16.2223 5.18377 13.0385 2 9.11115 2C5.18377 2 2 5.18377 2 9.11115C2 13.0385 5.18377 16.2223 9.11115 16.2223Z" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                        />
                                        <path d="M18.0003 18L14.1336 14.1333" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </form>
                            <div id="searchResults" class="search-results" style="display:none; position:absolute; top:100%; left:0; right:0; background:white; border:1px solid #ddd; max-height:300px; overflow-y:auto; z-index:1000; border-radius:4px;"></div>
                        </div>
                        <div class="header-contact-info">
                            <div class="phone-option">
                                <small>CALL US</small>
                                <p><a href="tel:919023962158">+91-9023962158</a></p>
                            </div>
                            <div class="whatsap-chat">
                                <a href="https://wa.me/919023962158" target="_blank"><img src="/imgs/whatsap.png" alt="whatsapp-logo" width="150" height="47" loading="lazy"></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="nav-outer">
            <div class="container">
                <nav class="navbar navbar-expand-lg justify-content-center">
                    <!-- <button 
 class="navbar-toggler" 
 type="button" 
 data-toggle="collapse" 
 data-target="#navbarNavDropdown" 
 aria-controls="navbarNavDropdown" 
 aria-expanded="false" 
 aria-label="Toggle navigation" 
 > 
 <span class="navbar-toggler-icon"></span>  </button> -->
                    <div class="justify-content-center" id="">
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link" href="/">Home </a> </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  Shop  </a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
									<div class="menu-inner">
										<ul>
											<h4> MEN HEALTH </h4>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/armodafinil-136') }}">Armodafinil</a></li>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/avanafil-tablets-135') }}">Avanafil Tablets</a></li>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/modafinil-134') }}">Modafinil</a></li>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/oral-jelly-84') }}">Oral Jelly</a></li>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/dapoxetine-tablets-83') }}">Dapoxetine Tablets</a></li>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/vardenafil-tablets-82') }}">Vardenafil Tablets</a></li>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/tadalafil-tablets-81') }}">Tadalafil Tablets</a></li>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/sildenafil-citrate-tablets-80') }}">Sildenafil Citrate Tablets</a></li>
										</ul>
										<ul>  <h4>WOMEN'S HEALTH</h4>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/ladygra-100mg') }}">Ladygra 100mg</a></li>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/fili-100mg-229') }}">Fili 100mg (Flibanserin 100mg)</a></li>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/tadalafil-10mg-52') }}">Femalefil 10mg (Tadalafil 10mg)</a></li>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/pink-lady-100-sildenafil-citrate-100mg') }}">Pink Lady 100 (Sildenafil Citrate 100mg)</a></li>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/female-up-tadalafil-20mg') }}">Female UP (Tadalafil 20mg)</a></li>                                                                                         </li>
										</ul>
										<ul>  <h4>ANTI FUNGAL</h4>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/fluconazole-150mg-55') }}">Fluconazole 150mg </a></li>
										</ul>
										<ul>  <h4>ANTI ACNE</h4>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/isotretinoin-20mg-58') }}"> Isotretinoin 20mg</a></li>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/isotretinoin-10mg-57') }}"> Isotretinoin 10mg</a></li>
										</ul>
										<ul>  <h4>ANTI CANCER</h4>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/tamoxifen-citrate-20mg-60') }}"> Tamoxifen Citrate 20mg
											</a></li>
											<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/tamoxifen-citrate-10mg-59') }}">     Tamoxifen Citrate 10mgg</a></li>
										</ul>
									</div>
								</div>
							 </li> 
							<li class="nav-item">
							   <a class="nav-link" href="{{ url('/blog') }}">Blog</a>
							</li>
							<li class="nav-item">
							   <a class="nav-link" href="{{ url('/contactus') }}">Contact Us</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{ url('/content/view/about-us') }}">About Us</a>
							</li>
                                        </ul>
                                    </div>
                </nav>
                </div>
                </div>
    </div>
    <div class="mobile-header">
        <div class="brand-bar">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-3">
                        <div class="brand-logo">
                            <a href="/"><img src="/imgs/logo.png" alt="brand-logo" width ="75" height="66" loading="lazy" /></a></div>
                    </div>
                    <div class="col-6">
                        <div class="header-contact-info justify-content-center">
                            <div class="phone-option d-flex">
                               <a href="tel:919023962158"> <i class="fa-solid fa-square-phone" style="color: #45c655; margin-top:7px;   font-size: 34px;"></i></a>
                            </div>
                            <div class="whatsap-chat">
                                <a href="https://wa.me/919023962158" target="_blank"><img src="/imgs/whatsapp.png" alt="whatsapp-logo" width="150" height="47" loading="lazy" /></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 toogle-mob">
                        <div class="nav-outer">
                            <div class="container">
                                <nav class="navbar navbar-expand-lg justify-content-end">
                                    <button class="navbar-toggler" type="button"  aria-label="Navbar Toggle" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span> </button>
                                    <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
                                        <ul class="navbar-nav">
                                            <li class="nav-item active">
                                                <a class="nav-link" href="/">Home </a> </li>
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  Shop  </a>
												<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
													<div class="menu-inner">
														<ul>
															<h4> MEN HEALTH </h4>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/armodafinil-136') }}">Armodafinil</a></li>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/avanafil-tablets-135') }}">Avanafil Tablets</a></li>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/modafinil-134') }}">Modafinil</a></li>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/oral-jelly-84') }}">Oral Jelly</a></li>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/dapoxetine-tablets-83') }}">Dapoxetine Tablets</a></li>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/vardenafil-tablets-82') }}">Vardenafil Tablets</a></li>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/tadalafil-tablets-81') }}">Tadalafil Tablets</a></li>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/catproducts/sildenafil-citrate-tablets-80') }}">Sildenafil Citrate Tablets</a></li>
														</ul>
														<ul>  <h4>WOMEN'S HEALTH</h4>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/ladygra-100mg') }}">Ladygra 100mg</a></li>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/fili-100mg-229') }}">Fili 100mg (Flibanserin 100mg)</a></li>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/tadalafil-10mg-52') }}">Femalefil 10mg (Tadalafil 10mg)</a></li>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/pink-lady-100-sildenafil-citrate-100mg') }}">Pink Lady 100 (Sildenafil Citrate 100mg)</a></li>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/female-up-tadalafil-20mg') }}">Female UP (Tadalafil 20mg)</a></li>                                                                                         </li>
														</ul>
														<ul>  <h4>ANTI FUNGAL</h4>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/fluconazole-150mg-55') }}">Fluconazole 150mg </a></li>
														</ul>
														<ul>  <h4>ANTI ACNE</h4>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/isotretinoin-20mg-58') }}"> Isotretinoin 20mg</a></li>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/isotretinoin-10mg-57') }}"> Isotretinoin 10mg</a></li>
														</ul>
														<ul>  <h4>ANTI CANCER</h4>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/tamoxifen-citrate-20mg-60') }}"> Tamoxifen Citrate 20mg
															</a></li>
															<li class="nav-item"><a class="nav-link" href="{{ url('/product/viewdetail/tamoxifen-citrate-10mg-59') }}">     Tamoxifen Citrate 10mgg</a></li>
														</ul>
													</div>
												</div>
											 </li> 
							<li class="nav-item">
							   <a class="nav-link" href="{{ url('/blog') }}">Blog</a>
							</li>
							<li class="nav-item">
							   <a class="nav-link" href="{{ url('/contactus') }}">Contact Us</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="{{ url('/content/view/about-us') }}">About Us</a>
							</li>
                                                        </ul>
                                                    </div>
                                </nav>
                                </div>
                                </div>
                            </div>
                            <div class="search">
                                <form class="form-inline my-lg-0" id="mobileSearchForm" action="{{ url('/searchpage') }}" method="GET">
                                     <input class="form-control mr-sm-2" id="mobileSearchInput" name="s" type="search" placeholder="Search" aria-label="Search" value="{!! !empty($_GET['s']) ? $_GET['s'] : '' !!}" />
                                    <button class="btn" type="submit"  aria-label="Search Button">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.11115 16.2223C13.0385 16.2223 16.2223 13.0385 16.2223  9.11115C16.2223 5.18377 13.0385 2 9.11115 2C5.18377 2 2 5.18377 2 9.11115C2 13.0385 5.18377 16.2223 9.11115 16.2223Z" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                            />
                                            <path d="M18.0003 18L14.1336 14.1333" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </form>
                                <div id="mobileSearchResults" class="search-results" style="display:none; position:absolute; top:100%; left:0; right:0; background:white; border:1px solid #ddd; max-height:300px; overflow-y:auto; z-index:1000; border-radius:4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
    </header>
    
 		@yield('content')
 		
		<footer class="circle-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-sm-6 col-xs-12"> 
						<div class="footer-logo"><img src="/imgs/logo.png"  alt="footer-logo" width="101" height="90" loading="lazy"></div>
						<div class="google-rew"><img src="/imgs/google-review.png"  alt="google-logo" width="196" height="92" loading="lazy"></div>
					
					</div>
					<div class="col-lg-3 col-sm-6 col-xs-12"> 
						<div class="footer-contact">
						<div class="addres">   <h4>Contact US</h4>
							<p>Industrial Area, Phase-2,<br>Chandigarh, India</p></div> 
							<p><strong><a href="tel:919216325377">+(91)-92163-25377</a></strong></p>
							<p><strong><a href="tel:919216925377">+(91)-92169-25377</a></strong></p>      

							<p><a href="mailto:info@rsmmultilink.com">info@rsmmultilink.com</a></p>
							<div class="twiter">
    							<span><svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
    								<path d="M19.6924 1.894C18.9517 2.21708 18.1682 2.4318 17.3663 2.53155C18.2114 2.03016 18.8433 1.23623 19.1423 0.300146C18.3509 0.769487 17.4851 1.1 16.5823 1.27738C16.0287 0.686339 15.3102 0.275431 14.5201 0.0980458C13.73 -0.0793392 12.9048 -0.015002 12.1517 0.282699C11.3986 0.5804 10.7525 1.0977 10.2972 1.7674C9.84195 2.4371 9.59862 3.22823 9.59883 4.03802C9.59883 4.35802 9.62591 4.66571 9.69237 4.95864C8.08631 4.8799 6.51494 4.46306 5.08102 3.73539C3.64709 3.00772 2.3829 1.98559 1.37109 0.735842C0.852706 1.62429 0.692446 2.67691 0.922939 3.67937C1.15343 4.68183 1.75735 5.55875 2.61171 6.13157C1.97264 6.11443 1.34705 5.94389 0.787697 5.63433V5.67864C0.788768 6.61087 1.11098 7.51427 1.70009 8.23676C2.28921 8.95926 3.10925 9.45673 4.02218 9.64544C3.67681 9.73646 3.32087 9.78116 2.96371 9.77836C2.7072 9.78292 2.45095 9.75981 2.1994 9.70944C2.46025 10.5107 2.96308 11.2114 3.63866 11.7151C4.31424 12.2187 5.12934 12.5005 5.97173 12.5218C4.54272 13.6394 2.78029 14.2456 0.96616 14.2436C0.635081 14.2436 0.317541 14.2289 0 14.1882C1.84602 15.3778 3.99717 16.0071 6.19327 15.9999C13.6222 15.9999 17.6838 9.84605 17.6838 4.51187C17.6838 4.3334 17.6777 4.16109 17.669 3.99002C18.4653 3.42015 19.151 2.70986 19.6924 1.894Z" fill="#03A9F4"/>
    								</svg>
    							</span><p><a href="https://x.com/RSMMultilinkLLP" >RSM Multilink LLP</a></p>
    						</div>
						</div>
					</div>
					<div class="col-lg-3 col-sm-6 col-xs-12"> 
						<h4>Hot Products</h4>
						<div class="footer-nav">
						<ul>
							<li><a href="/product/catproducts/men-health-73">Men Health</a></li>
							<li><a href="/product/catproducts/women-s-health-85">Women's Health   </a></li>
							<li><a href="/product/catproducts/anti-fungal-86">Anti Fungal </a></li>
							<li><a href="/product/catproducts/anti-acne-87">Anti Acne  </a></li>
							<li><a href="/product/catproducts/anti-cancer-88">Anti Cancer</a></li>

						</ul></div>
					</div>

					<div class="col-lg-3 col-sm-6 col-xs-12"> 
						<h4>Recent Blogs</h4>
						<div class="footer-nav">
						<ul>
							<li><a href="/blog/enhancing-male-sensual-health-by-treating-ed-with-maxgun-100">Enhancing Male Sensual Health by Treating ED with Maxgun 100</a></li>
							<li><a href="/blog/an-efficacious-solution-for-sensual-dysfunction-in-women-with-female-up-20mg">An Efficacious Solution for Sensual Dysfunction in Women With Female Up 20mg </a></li>
							<li><a href="/blog/a-cost-effective-solution-for-ed-pe-issues-with-sildigra-super-power">A Cost-effective Solution for ED & PE Issues With Sildigra Super Power  </a></li>
</ul></div>
					</div>

				</div>
			</div>
			
			<div class="footer-bottom">
			    	<p>© 2024 All rights reserved.</p>
			</div>
		</footer>
		  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
        
    </script>

    <script type="text/javascript">
        // Load Google Translate after page load to avoid blocking
        window.addEventListener('load', function() {
            var script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
            script.defer = true;
            document.head.appendChild(script);
        });
    </script>
   <script>
    window.addEventListener('load', function () {
        setTimeout(function () {
            const scripts = [
                "{{ asset('js/jquery-3.2.1.slim.min.js') }}",
                "{{ asset('js/popper.min.js') }}",
                "{{ asset('js/bootstrap.min.js') }}",
                "{{ asset('js/bootstrap.bundle.min.js') }}"
            ];
	
            function loadScriptsInOrder(scripts, index = 0, callback = () => {}) {
                if (index >= scripts.length) return callback();
                let script = document.createElement('script');
                script.src = scripts[index];
                script.onload = () => loadScriptsInOrder(scripts, index + 1, callback);
                document.body.appendChild(script);
            }

            loadScriptsInOrder(scripts, 0, function () {
                console.log("All scripts loaded, initializing functionality...");

                // ✅ Re-initialize things here (examples below)

                // Example: Re-enable Bootstrap tooltips (if using them)
                if (typeof bootstrap !== 'undefined') {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                }

                // Example: If using jQuery code
                if (typeof $ !== 'undefined') {
                    // Call your jQuery DOM ready code again
                    $(function () {
                        console.log('jQuery ready');
                        // Your jQuery-dependent logic here
                    });
                }
            });
        }, 1000);
    });
</script>
<script>
    window.addEventListener('load', function () {
        setTimeout(function () {
            // Load Zopim after 5 seconds
            window.$zopim || (function (d, s) {
                var z = $zopim = function (c) { z._.push(c) }, $ = z.s =
                    d.createElement(s), e = d.getElementsByTagName(s)[0];
                z.set = function (o) { z.set._.push(o) };
                z._ = []; z.set._ = [];
                $.async = true;
                $.setAttribute("charset", "utf-8");
                $.src = "https://v2.zopim.com/?1eslpvTfBb36qVZ5jw1VKJh9L5CjKeAF";
                z.t = +new Date();
                $.type = "text/javascript";
                e.parentNode.insertBefore($, e);
            })(document, "script");
        }, 1000);
    });
</script>

	<script>
    	$(document).ready(function(){
        	$(window).scroll(function() {
                var scroll = $(window).scrollTop();
                console.log('scroll - '+scroll);
                if (scroll >= 194) {
                    $('.mobile-header').addClass('mobile-header-fixed');
                } else {
                    $('.mobile-header').removeClass('mobile-header-fixed');
                }
            });
        });
        </script>
        
    <!-- Floating Buttons for Mobile -->
    <div class="floating-buttons">
        <!-- Scroll to Top Button -->
        <button id="scrollToTop" class="scroll-top-btn" aria-label="Scroll to top">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 19V5M12 5L5 12M12 5L19 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        
        <!-- WhatsApp Button -->
        <a href="https://wa.me/919023962158" target="_blank" class="whatsapp-float" aria-label="Chat on WhatsApp">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M27.281 4.65C24.318 1.686 20.404 0.062 16.238 0C7.406 0 0.194 7.21 0.19 16.044C0.188 18.862 0.914 21.614 2.296 24.046L0.062 32L8.204 29.804C10.55 31.074 13.174 31.744 15.844 31.746H15.852C24.682 31.746 31.896 24.534 31.9 15.7C31.902 11.538 30.28 7.626 27.281 4.65ZM16.238 29.084H16.232C13.852 29.082 11.518 28.444 9.482 27.238L8.986 26.944L3.886 28.268L5.234 23.294L4.912 22.778C3.588 20.664 2.888 18.234 2.89 15.744C2.894 8.702 8.596 3 15.638 3C19.052 3.002 22.254 4.332 24.678 6.758C27.102 9.184 28.43 12.388 28.428 15.802C28.424 22.844 22.722 29.084 16.238 29.084ZM22.69 19.002C22.332 18.822 20.562 17.952 20.228 17.832C19.894 17.712 19.652 17.652 19.41 18.012C19.168 18.372 18.478 19.182 18.266 19.424C18.054 19.666 17.842 19.696 17.484 19.516C17.126 19.336 15.954 18.948 14.566 17.718C13.486 16.76 12.754 15.578 12.542 15.22C12.33 14.862 12.52 14.664 12.7 14.484C12.862 14.322 13.058 14.064 13.238 13.852C13.418 13.64 13.478 13.488 13.598 13.246C13.718 13.004 13.658 12.792 13.568 12.612C13.478 12.432 12.748 10.66 12.444 9.944C12.148 9.248 11.848 9.342 11.626 9.332C11.414 9.322 11.172 9.32 10.93 9.32C10.688 9.32 10.3 9.41 9.966 9.768C9.632 10.126 8.702 10.996 8.702 12.768C8.702 14.54 10.026 16.252 10.206 16.494C10.386 16.736 12.752 20.388 16.384 21.956C17.228 22.332 17.886 22.564 18.398 22.738C19.244 23.012 20.016 22.972 20.626 22.88C21.308 22.776 22.69 22.016 22.994 21.186C23.298 20.356 23.298 19.646 23.208 19.496C23.118 19.346 22.876 19.256 22.518 19.076L22.69 19.002Z" fill="currentColor"/>
            </svg>
        </a>
    </div>

    <style>
        /* Floating Buttons Container */
        .floating-buttons {
            position: fixed;
            bottom: 80px;
            right: 20px;
            z-index: 99999 !important;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* WhatsApp Button */
        .whatsapp-float {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: white !important;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            text-decoration: none;
            transition: all 0.3s ease;
            animation: pulse 2s infinite;
        }
        
        .whatsapp-float svg {
            width: 32px;
            height: 32px;
            fill: white;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.6);
            color: white !important;
            text-decoration: none;
        }

        /* Scroll to Top Button */
        .scroll-top-btn {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
        }
        
        .scroll-top-btn svg {
            width: 24px;
            height: 24px;
            stroke: white;
        }

        .scroll-top-btn.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .scroll-top-btn:hover {
            transform: scale(1.1) translateY(0);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        /* Pulse Animation for WhatsApp */
        @keyframes pulse {
            0% {
                box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            }
            50% {
                box-shadow: 0 4px 25px rgba(37, 211, 102, 0.7);
            }
            100% {
                box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            }
        }

        /* Hide on Desktop (optional - show only on mobile) */
        @media (min-width: 992px) {
            .floating-buttons {
                display: none;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .whatsapp-float {
                width: 55px;
                height: 55px;
            }
            
            .whatsapp-float svg {
                width: 28px;
                height: 28px;
            }
            
            .scroll-top-btn {
                width: 45px;
                height: 45px;
            }
            
            .scroll-top-btn svg {
                width: 20px;
                height: 20px;
            }
            
            .floating-buttons {
                bottom: 75px;
                right: 15px;
                gap: 12px;
            }
        }
    </style>

    <script>
        // Scroll to Top functionality
        $(document).ready(function() {
            var scrollTopBtn = $('#scrollToTop');
            
            // Show/hide button based on scroll position
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    scrollTopBtn.addClass('show');
                } else {
                    scrollTopBtn.removeClass('show');
                }
            });
            
            // Smooth scroll to top
            scrollTopBtn.click(function() {
                $('html, body').animate({
                    scrollTop: 0
                }, 600);
                return false;
            });
        });
    </script>
    <script src="{{ asset('js/ajax-search.js') }}" defer></script>
        
	</body>
</html>



