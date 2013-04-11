<!DOCTYPE html>
<html>
	<head>
		<meta charset='UTF-8'>
		<title>@yield('title') - Old School Achievements</title>
		<meta name='author' content='@yield('author')'>
		<meta name='description' content='@yield('description')'>
		<meta name='keywords' content='@yield('keywords')'>
		
		
		<link href='http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css' rel='stylesheet' type='text/css'>
		
		<link href='/css/thirdparty/jquery-ui-1.8.16.custom.css' rel='stylesheet' type='text/css'>
		<link href='/css/bootstrap.min.css' rel='stylesheet' type='text/css'>
		<!-- <link href='/css/font-awesome.css' rel='stylesheet' type='text/css'> -->
		<link href='/css/layout.css' rel='stylesheet' type='text/css'>
		<link href='/css/common.css' rel='stylesheet' type='text/css'>
		
		@yield('css')

	</head>
	<body>
		<div class='navbar-wrapper' style='z-index: 5; min-height: 50px;'>
			<div class='navbar navbar-inverse navbar-static-top'>
				<div class='navbar-inner'>
					<div class='container'>
						<div id='user'>
							@if (Auth::check())
							<span class='text'>Logged in as</span>
							<a href='/user/{{ Auth::user()->username }}' title='My Account'>{{ Auth::user()->username }}</a>
							<i class='icon-certificate icon-white' title='{{ number_format(Auth::user()->achievement_tally, 0, '.', ',') }} Achievements'></i>
							@else
							<a href='/auth/login'>Log In</a> | 
							<a href='/auth/register'>Register</a>
							@endif
						</div>
						<a class='brand' href='/'>Old School Achievements</a>
						
						<ul class='nav'>
							<li{{ URI::is('/') ? ' class="active"' : '' }}>
								<a href ='/'>Home</a>
							</li>
							<li{{ URI::is('games*') ? ' class="active"' : '' }}>
								<a href ='/games'>Games</a>
							</li>
							<li{{ URI::is('about') ? ' class="active"' : '' }}>
								<a href ='/about'>About</a>
							</li>
						</ul>
						<form action='/games/search' class='navbar-search pull-left' method='post'>
							<input type='text' name='search' class='search-query' placeholder='Search' value='' />
						</form>
					</div>
				</div>
			</div>
		</div>
			
		<div class='container'>
			@if (Session::has('success'))
			<div class='alert alert-success'>
				<span class='close' data-dismiss='alert'>&times;</span>
				{{ Session::get('success') }}
			</div>
			@endif
			@if (Session::has('warning')) 
			<div class='alert alert-warning'>
				<span class='close' data-dismiss='alert'>&times;</span>
				{{ Session::get('warning') }}
			</div>
			@endif
			@if (Session::has('error'))
			<div class='alert alert-error'>
				<span class='close' data-dismiss='alert'>&times;</span>
				{{ Session::get('error') }}
			</div>
			@endif

			@yield('content')
		</div>

		<footer>
			&copy; Nick Wright {{ date('Y') }}
		</footer>

		<section id='alerts'>
			<div class='row'>
				<div class='span4 message_box'>
					<div id='message_template' class='hidden alert alert-block fade in'>
						<button type='button' class='close' data-dismiss='alert'>&times;</button>
						<h4 class='alert-heading'></h4>
						<p></p>
					</div>
				</div>
			</div>
		</section>
		
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js' type='text/javascript'></script>
		<script src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js' type='text/javascript'></script>
		<script src='/js/bootstrap.min.js' type='text/javascript'></script>
		<script src='/js/_osa.js' type='text/javascript'></script>
		@yield('javascript')
	</body>
</html>