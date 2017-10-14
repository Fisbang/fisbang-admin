<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Fisbang Admin">
	<meta name="author" content="Fisbang Team">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Fisbang Admin') }}</title>

    <!-- Styles -->
    <!--link href="/css/app.css" rel="stylesheet"-->
	
	<!-- Bootstrap core CSS -->
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/bootstrap-switch.css" rel="stylesheet">

	<!-- Morris for graph -->
	<link href="/css/morris.css" rel="stylesheet"/>	
	
	<!-- Font Awesome -->
	<link href="/css/font-awesome.min.css" rel="stylesheet">

	<!-- Fisbang Design css -->
	<link href="/css/app.min.css" rel="stylesheet">
	<link href="/css/app-skin.css" rel="stylesheet">
	<link href="/css/app-fisbang.css" rel="stylesheet">


    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body class="overflow-hidden">
	<!-- Overlay Div -->
	<div id="overlay" class="transparent"></div>
	
	@if (Auth::guest())
		<div id="wrapper" class="sidebar-hide">
	@else
		<div id="wrapper" class="preload">
	@endif

		<!--top nav / header-->
		<div id="top-nav" class="fixed">
			@if (!Auth::guest())
			<button type="button" class="navbar-toggle pull-left" id="sidebarToggle">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<button type="button" class="navbar-toggle pull-left hide-menu" id="menuToggle">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			@endif
			<div class="brand">
				<img src="/img/logo_fisbang.png" href="{{ url('/') }}">
				<span> Fisbang </span>
			</div><!-- /brand -->	
			<ul class="nav-notification clearfix">
				@if (Auth::guest())
					<li><a href="{{ route('login') }}">Login</a></li>
					<li><a href="{{ route('register') }}">Register</a></li>
				@else
					<li class="profile dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<span>{{ Auth::user()->name }}</span>
							<span><i class="fa fa-chevron-down"></i></span>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a class="clearfix" href="#">
									<img src="{{ Auth::user()->getGravatarUrl() }}" alt="User Avatar">
									<div class="detail">
										<strong>{{ Auth::user()->name }}</strong>
										<p class="grey">{{ Auth::user()->email }}</p> 
									</div>
								</a>
							</li>
							<li><a tabindex="-1" href="{{ url('/') }}" class="main-link"><i class="fa fa-desktop fa-lg"></i> Dashboard</a></li>
							<li><a tabindex="-1" href="//www.fisbang.com" class="main-link"><i class="fa fa-globe fa-lg"></i> Fisbang.com</a></li>
							<li><a tabindex="-1" href="#" class="theme-setting"><i class="fa fa-cog fa-lg"></i> Setting</a></li>
							<li class="divider"></li>
							<li>
								<a tabindex="-1" class="main-link logoutConfirm_open" href="#logoutConfirm"><i class="fa fa-lock fa-lg"></i> Log out</a>

								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									{{ csrf_field() }}
								</form>
							</li>
						</ul>
					</li>
				@endif
			</ul>
		</div><!-- /top-nav-->
		
		@if (!Auth::guest())
		<!-- aside -->
		<aside class="fixed">
			<div class="sidebar-inner scrollable-sidebar">
				<div class="user-block clearfix">
					<img src="{{ Auth::user()->getGravatarUrl() }}" alt="User Avatar">
					<div class="detail">
						<span class="user">{{ Auth::user()->name }}</span>
						<!--ul class="list-inline">
							<li><a href="#">Profile</a></li>
							<li><a href="#" class="no-margin">Inbox</a></li>
						</ul-->
					</div>
				</div><!-- /user-block -->
				<div class="main-menu">
					<ul>
						@if(Auth::user()->isAdmin())
							<li class="{{ Request::is('/') || Request::is('home/*') ? 'active' : '' }}">
								<a href="{{ url('/') }}">
									<span class="menu-icon">
										<i class="fa fa-desktop fa-lg"></i> 
									</span>
									<span class="text">
										Dashboard
									</span>
									<span class="menu-hover"></span>
								</a>
							</li>
							<li class="{{ Request::is('users/*') ? 'active' : '' }}">
								<a href="{{ url('users/') }}">
									<span class="menu-icon">
										<i class="fa fa-desktop fa-lg"></i> 
									</span>
									<span class="text">
										Users
									</span>
									<span class="menu-hover"></span>
								</a>
							</li>
							<li class="{{ Request::is('questions/*') ? 'active' : '' }}">
								<a href="{{ url('questions/') }}">
									<span class="menu-icon">
										<i class="fa fa-bar-chart-o fa-lg"></i> 
									</span>
									<span class="text">
										Ask Fisbang
									</span>
									<span class="menu-hover"></span>
								</a>
							</li>
							<li class="{{ Request::is('articles/*') ? 'active' : '' }}">
								<a href="{{ url('articles/') }}">
									<span class="menu-icon">
										<i class="fa fa-bar-chart-o fa-lg"></i> 
									</span>
									<span class="text">
										Articles
									</span>
									<span class="menu-hover"></span>
								</a>
							</li>
							<li class="{{ Request::is('settings/*') ? 'active' : '' }}">
								<a href="#">
									<span class="menu-icon">
										<i class="fa fa-cog fa-lg"></i> 
									</span>
									<span class="text">
										Setting
									</span>
									<span class="menu-hover"></span>
								</a>
							</li>
						@else
							<li class="{{ Request::is('/') || Request::is('home/*') ? 'active' : '' }}">
								<a href="{{ url('/') }}">
									<span class="menu-icon">
										<i class="fa fa-desktop fa-lg"></i> 
									</span>
									<span class="text">
										Dashboard
									</span>
									<span class="menu-hover"></span>
								</a>
							</li>
							<li class="{{ Request::is('saving/*') ? 'active' : '' }}">
								<a href="#">
									<span class="menu-icon">
										<i class="fa fa-bar-chart-o fa-lg"></i> 
									</span>
									<span class="text">
										Savings
									</span>
									<span class="menu-hover"></span>
								</a>
							</li>
							<li class="{{ Request::is('building/*') ? 'active' : '' }}">
								<a href="#">
									<span class="menu-icon">
										<i class="fa fa-building-o fa-lg"></i> 
									</span>
									<span class="text">
										Building
									</span>
									<span class="menu-hover"></span>
								</a>
							</li>
							<li class="{{ Request::is('market/*') ? 'active' : '' }}">
								<a href="#">
									<span class="menu-icon">
										<i class="fa fa-shopping-cart fa-lg"></i> 
									</span>
									<span class="text">
										Market
									</span>
									<span class="menu-hover"></span>
								</a>
							</li>
							<li class="{{ Request::is('setting/*') ? 'active' : '' }}">
								<a href="#">
									<span class="menu-icon">
										<i class="fa fa-cog fa-lg"></i> 
									</span>
									<span class="text">
										Setting
									</span>
									<span class="menu-hover"></span>
								</a>
							</li>
						@endif
					</ul>
					
					<div class="alert alert-info">
						Welcome fisbang admin. Optimize your building day by day 
					</div>
				</div><!-- /main-menu -->
			</div><!-- /sidebar-inner -->
		</aside><!-- /aside -->
		@endif
		
		<div id="main-container">
		@yield('content')
		</div>
		
		<!-- footer -->
		<footer>
			<div class="row">
				<div class="col-sm-6">
					<p class="no-margin">
						&copy; 2017 <strong>Fisbang</strong> Mail : contact@fisbang.com
					</p>
				</div><!-- /.col -->
			</div><!-- /.row-->
		</footer><!-- /footer -->
		
		@yield('modal')
	</div><!-- /wrapper -->

	<a href="" id="scroll-to-top" class="hidden-print"><i class="fa fa-chevron-up"></i></a>
	
	@if (!Auth::guest())
	<!-- Logout confirmation -->
	<div class="custom-popup width-100" id="logoutConfirm">
		<div class="padding-md">
			<h4 class="m-top-none"> Do you want to logout?</h4>
		</div>

		<div class="text-center">
			<a class="btn btn-success m-right-sm" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
			<a class="btn btn-danger logoutConfirm_close">Cancel</a>
		</div>
	</div>
	@endif

    <!-- Scripts -->
    <!--script src="/js/app.js"></script-->
	
	<!-- Jquery -->
	<script src="/js/jquery-1.10.2.min.js"></script>
	<!--script src="/js/jquery.min.js"></script-->

	<!-- Bootstrap -->
    <script src="/js/bootstrap.js"></script>
    <script src="/js/bootstrap-switch.js"></script>
   
	<!-- Flot -->
	<script src='/js/jquery.flot.min.js'></script>
   
	<!-- Morris -->
	<script src='/js/rapheal.min.js'></script>	
	<script src='/js/morris.min.js'></script>	
	
	<!-- Colorbox -->
	<script src='/js/jquery.colorbox.min.js'></script>	

	<!-- Sparkline -->
	<script src='/js/jquery.sparkline.min.js'></script>
	
	<!-- Pace -->
	<script src='/js/uncompressed/pace.js'></script>
	
	<!-- Popup Overlay -->
	<script src='/js/jquery.popupoverlay.min.js'></script>
	
	<!-- Slimscroll -->
	<script src='/js/jquery.slimscroll.min.js'></script>
	
	<!-- Modernizr -->
	<script src='/js/modernizr.min.js'></script>
	
	<!-- Cookie -->
	<script src='/js/jquery.cookie.min.js'></script>
	
	<!-- Perfect -->
	<!--script src="/js/app/app_dashboard-2.js"></script-->
	<script src="/js/app/app.js"></script>
</body>
</html>
