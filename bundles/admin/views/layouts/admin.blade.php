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
		<link href='/css/font-awesome.css' rel='stylesheet' type='text/css'>
		<link href='/css/layout.css' rel='stylesheet' type='text/css'>
		<link href='/css/common.css' rel='stylesheet' type='text/css'>
		<link href='/css/admin.css' rel='stylesheet' type='text/css'>

		@yield('css')

	</head>
	<body>
		<div class='navbar-wrapper' style='z-index: 5; min-height: 50px;'>
			<div class='navbar navbar-inverse navbar-static-top'>
				<div class='navbar-inner'>
					<div class='container'>
						<a class='brand' href='/'>Old School Achievements</a>
						
						<ul class='nav'>
							<li>
								<a href ='/admin'>Dashboard</a>
							</li>
							<li>
								<a href ='/'>Site Home</a>
							</li>
							<li>
								<a href ='/auth/logout'>Log Out</a>
							</li>
						</ul>
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
			<div class='row-fluid'>
				<div class='span3'>
					@include('admin::layouts.nav')
				</div>
				<div class='span9'>
					@yield('content')
				</div>
			</div>
		</div>

		<footer>
			&copy; OSA {{ date('Y') }}
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