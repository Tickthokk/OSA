<!DOCTYPE html>
<html>
	<head>
		<meta charset = 'UTF-8'>
		<title><?php echo $page_title; ?></title>
		<meta name = 'author' content = '<?php echo $page_author; ?>'>
		<meta name = 'description' content = '<?php echo $page_description; ?>'>
		<meta name = 'keywords' content = '<?php echo $page_keywords; ?>'>
		<!--<link href = '/favicon.ico' rel = 'shortcut icon' type = 'image/vnd.microsoft.icon'>-->
		<?php if ($firewall_enabled) : ?>
		<link href = '/assets/firewall/jquery-ui.css' rel = 'stylesheet' type='text/css'>
		<?php else : ?>
		<link href = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css' rel = 'stylesheet' type='text/css'>
		<?php endif; ?>
		<link href = '/assets/css/thirdparty/jquery-ui-1.8.16.custom.css' rel = 'stylesheet' type='text/css'>
		<link href = '/assets/css/twitterbootstrap/bootstrap.min.css' rel = 'stylesheet' type='text/css'>
		<link href = '/assets/css/layout.css' rel = 'stylesheet' type = 'text/css'>
		<link href = '/assets/css/common.css' rel = 'stylesheet' type = 'text/css'>
		<?php foreach ($css as $c) : ?>
		<link href = '/assets/css/<?php echo $c; ?>.css' rel = 'stylesheet' type = 'text/css'>
		<?php endforeach; ?>
	</head>
	<body>
		<div class = 'navbar-wrapper' style = 'z-index: 5; min-height: 60px;'>
			<div class = 'navbar'>
				<div class = 'navbar-inner'>
					<div class = 'container'>
						<div id = 'user'>
							<?php if ($this->user->is_logged) : ?>
							<span class = 'text'>Logged in as</span>
							<a href = '/user/<?php echo $this->user->username; ?>' title = 'My Account'><?php echo $this->user->username; ?></a>
							<i class = 'icon-certificate icon-white' title = '<?php echo number_format($this->user->achievement_tally, 0, '.', ','); ?> Achievements'></i>
							<?php else : ?>
							<a href = '/user/login'>Log In</a> | 
							<a href = '/user/register'>Register</a>
							<?php endif; ?>
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
							<?php if ($is_admin) : ?>
							<li>
								<a href = '/admin'>Admin Panel</a>
							</li>
							<?php endif; ?>
							<?php if ($is_moderator) : ?>
							<li>
								<a href = '#' class = 'admin_mode'>Admin Mode</a>
							</li>
							<?php endif; ?>
						</ul>
						<form action = '/search' class = 'navbar-search pull-left' method = 'post'>
							<input type = 'text' name = 'search' class = 'search-query' placeholder = 'Search' value = '<?php echo $page_search; ?>' />
						</form>
					</div>
				</div>
			</div>
		</div>
			
		<div class = 'container'>
			<?php if (@$success) : ?>
			<div class = 'alert alert-success'>
				<span class = 'close' data-dismiss = 'alert'>&times;</span>
				{success}
			</div>
			<?php elseif (@$warning) : ?>
			<div class = 'alert alert-warning'>
				<span class = 'close' data-dismiss = 'alert'>&times;</span>
				{warning}
			</div>
			<?php elseif (@$error) : ?>
			<div class = 'alert alert-error'>
				<span class = 'close' data-dismiss = 'alert'>&times;</span>
				{error}
			</div>
			<?php endif; ?>