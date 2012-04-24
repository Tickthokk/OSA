<!DOCTYPE html>
<html>
	<head>
		<meta charset = 'UTF-8'>
		<title><?php echo $page_title; ?></title>
		<meta name = 'author' content = '<?php echo $page_author; ?>'>
		<meta name = 'description' content = '<?php echo $page_description; ?>'>
		<meta name = 'keywords' content = '<?php echo $page_keywords; ?>'>
		<link href = '/favicon.ico' rel = 'shortcut icon' type = 'image/vnd.microsoft.icon'>
		<link href = 'http://fonts.googleapis.com/css?family=Quicksand:400,700|Mate+SC|Chivo' rel = 'stylesheet' type='text/css'>
		<link href = '/assets/styles/twitterbootstrap/bootstrap.css' rel = 'stylesheet'>
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
							<?php
								foreach ($page_navigation as $nav_item) :
							?>
							<li<?php if ($page_nav_choice == $nav_item) : ?> class = 'active'<?php endif; ?>>
								<a href = '/<?php echo $nav_item; ?>'>
									<?php echo ucfirst($nav_item); ?>
								</a>
							</li>
							<?php endforeach; ?>
						</ul>
						<form action = '/search' class = 'navbar-search pull-left' method = 'post'>
							<input type = 'text' name = 'search' class = 'search-query' placeholder = 'Search' value = '<?php echo $page_search; ?>' />
						</form>
					</div>
				</div>
			</div>
		</div>
			
		<div class = 'container'>