<!DOCTYPE html>
<html>
	<head>
		<meta charset = 'UTF-8'>
		<title><?php echo $title; ?></title>
		<meta name = 'author' content = '<?php echo $this->page->author; ?>'>
		<meta name = 'description' content = '<?php echo $this->page->description; ?>'>
		<meta name = 'keywords' content = '<?php echo $this->page->keywords; ?>'>
		<link href = '/favicon.ico' rel = 'shortcut icon' type = 'image/vnd.microsoft.icon'>
		<link href = 'http://fonts.googleapis.com/css?family=Quicksand:400,700|Mate+SC|Chivo' rel = 'stylesheet' type='text/css'>
		<link href = '/assets/styles/bootstrap.min.css' rel = 'stylesheet'>
		<link href = '/assets/styles/layout.css' rel = 'stylesheet' type = 'text/css'>
		<link href = '/assets/styles/common.css' rel = 'stylesheet' type = 'text/css'>
	</head>
	<body>
		<div class = 'navbar-wrapper' style = 'z-index: 5; min-height: 60px;'>
			<div class = 'navbar'>
				<div class = 'navbar-inner'>
					<div class = 'container'>
						<div id = 'user'>
							<span class = 'text'>Logged in as</span>
							<a href = '/user/Tickthokk'>Tickthokk</a>
						</div>
						<a class = 'brand' href = '/'>Old School Achievements</a>
						
						<ul class = 'nav'>
							<li class = 'active'>
								<a href = '/'>Home</a>
							</li>
							<li>
								<a href = '/games'>Games</a>
							</li>
							<li>
								<a href = '/about'>About</a>
							</li>
							<li>
								<a href = '/contact'>Contact</a>
							</li>
						</ul>
						<form action = '/search' class = 'navbar-search pull-left'>
							<input type = 'text' class = 'search-query' placeholder = 'Search' />
						</form>
					</div>
				</div>
			</div>
		</div>
			
		<div class = 'container'>