<?php 
$nav_items = array(
	'dashboard' => array(
		'link' => '/admin',
		'icon' => 'play',
		'text' => 'Dashboard'
	),
	'users' => array(
		'link' => '/admin/users',
		'icon' => 'user',
		'text' => 'Users'
	),
	'games' => array(
		'link' => '/admin/games',
		'icon' => 'film',
		'text' => 'Games'
	),
	// 'gameflags' => array(
	// 	'link' => '/admin/games/flags',
	// 	'icon' => 'flag',
	// 	'text' => 'Game Flags'
	// ),
	'links' => array(
		'link' => '/admin/links',
		'icon' => 'link',
		'text' => 'Links'
	),
	// 'linkflags' => array(
	// 	'link' => '/admin/links/flags',
	// 	'icon' => 'flag',
	// 	'text' => 'Link Flags'
	// ),
	'achievements' => array(
		'link' => '/admin/achievements',
		'icon' => 'certificate',
		'text' => 'Achievements'
	),
	// 'achievementflags' => array(
	// 	'link' => '/admin/achievements/flags',
	// 	'icon' => 'flag',
	// 	'text' => 'Achievement Flags'
	// ),
	'icons' => array(
		'link' => '/admin/icons',
		'icon' => 'heart',
		'text' => 'Icons'
	),
	'tags' => array(
		'link' => '/admin/tags',
		'icon' => 'tags',
		'text' => 'Tags'
	),
	'flags' => array(
		'link' => '/admin/flags',
		'icon' => 'flag',
		'text' => 'Flags'
	),
	'log' => array(
		'link' => '/admin/log',
		'icon' => 'book',
		'text' => 'Log'
	),
	'advanced' => array(
		'link' => '/admin/advanced',
		'icon' => 'lock',
		'text' => 'Advanced'
	)
);
?>
<div class='well' style='padding: 8px 0;'>
	<ul class='nav nav-list'>
		<li class='nav-header'>Administrate</li>
		@foreach($nav_items as $nav_key => $nav)
		<li class='@if($left_nav == $nav_key)active@endif'>
			<a href='{{ $nav['link'] }}'>
				<i class='icon-{{ $nav['icon'] }}'></i>
				{{ $nav['text'] }}
			</a>
		</li>
		@endforeach
	</ul>
</div>