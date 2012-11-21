<h1>Dashboard</h1>

<h2>Moderator Management</h2>
<ul>
	<li>
		<a href = '/admin/unapproved/links'>View Unapproved Links</a> TODO
	</li>
	<li>
		<a href = '/admin/flagged/links'>View Flagged Links</a> TODO
	</li>
	<li>
		<a href = '/admin/flagged/games'>View Flagged Games</a> TODO
	</li>
	<li>
		<a href = '#'>Manage Users</a> TODO
	</li>
</ul>

<?php if ($is_admin) : ?>
<h2>System Management</h2>
<ul>
	<li>
		<a href = '#'>Manage Moderators</a> TODO
	</li>
	<li>
		<a href = '/admin/fix/games'>Fix Games achievement tally</a>
	</li>
	<li>
		<a href = '/admin/fix/users'>Fix Users achievement tally</a>
	</li>
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
</ul>
<?php endif; ?>