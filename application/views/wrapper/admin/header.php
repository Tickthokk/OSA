<!DOCTYPE html>
<html>
	<head>
		<meta charset = 'UTF-8'>
		<title>OSA Admin Panel</title>
		<meta name = 'robots' content = 'noindex, nofollow'>
		<?php if ($firewall_enabled) : ?>
		<link href = '/assets/firewall/jquery-ui.css' rel = 'stylesheet' type='text/css'>
		<?php else : ?>
		<link href = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css' rel = 'stylesheet' type='text/css'>
		<?php endif; ?>
		<link href = '/assets/css/thirdparty/jquery-ui-1.8.16.custom.css' rel = 'stylesheet' type='text/css'>
		<!-- <link href = '/assets/css/twitterbootstrap/bootstrap.min.css' rel = 'stylesheet' type='text/css'> -->
		<link href = '/assets/css/thirdparty/inspiritas.css' rel = 'stylesheet' type='text/css'>
		<link href = '/assets/css/thirdparty/jquery.dataTables.css' rel = 'stylesheet' type='text/css'>
		<!-- <link href = '/assets/css/thirdparty/jquery.dataTables_themeroller.css' rel = 'stylesheet' type='text/css'> -->
		<link href = '/assets/css/thirdparty/datatables_bootstrap.css' rel = 'stylesheet' type='text/css'>
		<link href = '/assets/css/layout.css' rel = 'stylesheet' type = 'text/css'>
		<link href = '/assets/css/common.css' rel = 'stylesheet' type = 'text/css'>
		<?php foreach ($css as $c) : ?>
		<link href = '/assets/css/<?php echo $c; ?>.css' rel = 'stylesheet' type = 'text/css'>
		<?php endforeach; ?>
	</head>
	<body>
		<div class = 'navbar navbar-static-top navbar-inverse'>
			<div class = 'navbar-inner'>
				<div class = 'container'>
					<a class = 'brand' href = '/'>Old School Achievements</a>
					
					<ul class = 'nav'>
						<li>
							<a href = '/'>Back to Site</a>
						</li>
					</ul>
					<div class = 'nav-collapse collapse' id = 'user_menu'>
						<div class = 'auth pull-right'>
							<img class = 'avatar' src = '<?php echo $gravatar_url; ?>'>
							<span class = 'name'><?php echo $this->user->username; ?></span>
							<br>
							<span class = 'links'>
								<a href = '/user/logout'>Logout</a>
							</span>
						</div>
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
			<div class = 'row-fluid'>
				<div class = 'span3'>
					<aside>
						<nav>
							<ul class = 'nav'>
								<?php 
									$nav_items = array(
										'dash' => array(
											'link' => '/admin',
											'icon' => 'play',
											'text' => 'Dashboard'
										),
										'um' => array(
											'link' => '/admin/manage/users',
											'icon' => 'user',
											'text' => 'User Management'
										),
										'ul' => array(
											'link' => '/admin/unapproved/link',
											'icon' => 'share',
											'text' => 'Unapproved Links'
										),
										'fl' => array(
											'link' => '/admin/flagged/links',
											'icon' => 'flag',
											'text' => 'Flagged Links'
										),
										'fg' => array(
											'link' => '/admin/flagged/games',
											'icon' => 'flag',
											'text' => 'Flagged Games'
										),
									);
									foreach ($nav_items as $nav_key => $nav) :
								?>
								<li class = '<?php echo $left_nav == $nav_key ? ' selected' : ''; ?>'>
									<a href = '<?php echo $nav['link']; ?>'>
										<i class = 'icon-<?php echo $nav['icon']; ?><?php echo $left_nav == $nav_key ? '' : ' icon-white'; ?>'></i>
										<?php echo $nav['text']; ?>
									</a>
								</li>
								<?php endforeach; ?>
							</ul>
							<h5 style = 'color: white; margin: -10px 0 10px 10px;'>Fix Achievement Tally's For</h5>
							<ul class = 'nav'>
								<li>
									<a href = '/admin/fix/games'>
										<i class = 'icon-map-marker icon-white'></i>
										Games
									</a>
								</li>
								<li>
									<a href = '/admin/fix/users'>
										<i class = 'icon-user icon-white'></i>
										Users
									</a>
								</li>
							</ul>
						</nav>
					</aside>
					<?php /*
								<!--
								<li>
									<strong>Warning: Use sparingly</strong>
								</li>
								<li>
									<a href = '/admin/dummy/users/100'>Create 100 dummy users</a>
									<strong>Currently:</strong> {user_tally}
								</li>
								<li>
									<a href = '/admin/dummy/games/20'>Create 20 dummy games</a>
									<strong>Currently:</strong> {games_tally}
								</li>
								<li>
									<a href = '/admin/dummy/achievements/15'>Create 15 dummy achievements (per game)</a>
									<strong>Currently:</strong> {achievements_tally}
								</li>
								-->
							*/
					?>
				</div>
				<div class = 'span9' id = 'content-wrapper'>
					<div id = 'content'>