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
		<link href = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/ui-lightness/jquery-ui.css' rel='stylesheet' type='text/css' />
		<!-- <link href = '/assets/css/twitterbootstrap/bootstrap.min.css' rel = 'stylesheet' type='text/css'> -->
		<link href = '/assets/css/thirdparty/inspiritas.css' rel = 'stylesheet' type='text/css'>
		<link href = '/assets/css/thirdparty/jquery.dataTables.css' rel = 'stylesheet' type='text/css'>
		<!-- <link href = '/assets/css/thirdparty/jquery.dataTables_themeroller.css' rel = 'stylesheet' type='text/css'> -->
		<link href = '/assets/css/thirdparty/datatables_bootstrap.css' rel = 'stylesheet' type='text/css'>
		<link href = '/assets/css/layout.css' rel = 'stylesheet' type = 'text/css'>
		<link href = '/assets/css/common.css' rel = 'stylesheet' type = 'text/css'>
		<link href = '/assets/css/admin/common.css' rel = 'stylesheet' type='text/css'>
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
											'link' => '/admin/dashboard',
											'icon' => 'play',
											'text' => 'Dashboard'
										),
										'us' => array(
											'link' => '/admin/users',
											'icon' => 'user',
											'text' => 'Users'
										),
										'ga' => array(
											'link' => '/admin/games',
											'icon' => 'film',
											'text' => 'Games'
										),
										'gf' => array(
											'link' => '/admin/game_flags',
											'icon' => 'flag',
											'text' => 'Game Flags'
										),
										'li' => array(
											'link' => '/admin/links',
											'icon' => 'bell',
											'text' => 'Links'
										),
										'lf' => array(
											'link' => '/admin/link_flags',
											'icon' => 'flag',
											'text' => 'Link Flags'
										),
										'ac' => array(
											'link' => '/admin/achievements',
											'icon' => 'certificate',
											'text' => 'Achievements'
										),
										'af' => array(
											'link' => '/admin/achievement_flags',
											'icon' => 'flag',
											'text' => 'Achievement Flags'
										),
										'ic' => array(
											'link' => '/admin/icons',
											'icon' => 'heart',
											'text' => 'Icons'
										),
										'ta' => array(
											'link' => '/admin/tags',
											'icon' => 'tags',
											'text' => 'Tags'
										),
										'fl' => array(
											'link' => '/admin/flags/all',
											'icon' => 'flag',
											'text' => 'All Flags'
										),
										'lo' => array(
											'link' => '/admin/log',
											'icon' => 'book',
											'text' => 'Log'
										),
										'ad' => array(
											'link' => '/admin/advanced',
											'icon' => 'lock',
											'text' => 'Advanced'
										)
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
						</nav>
					</aside>
				</div>
				<div class = 'span9' id = 'content-wrapper'>
					<div id = 'content'>