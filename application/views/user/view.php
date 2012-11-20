<script type = 'text/javascript'>
	var user_id = parseInt(<?php echo $user_id; ?>);
</script>
<?php if ($this->user->id == $user_id) : ?>
<a href = '/user/logout' class = 'flr btn btn-primary'>Logout</a>
<?php endif; ?>
<h1>{username}</h1>

<hr>

<h2>Achievements</h2>
<table id = 'user_achievements' class = 'table table-bordered table-striped table-hover table-condensed' data-total = '<?php echo $achievement_count; ?>'>
	<thead>
		<tr>
			<th>Game</th>
			<th>Achievement</th>
			<th>When</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan = '3'>
				<ul class = 'pager'>
					<li class = 'previous disabled'>
						<a href = '#'>&larr; Previous</a>
					</li>
					<li>
						<?php echo $achievement_count; ?> Achievement<?php if ($achievement_count != 1) echo 's'; ?><?php if ($achievement_count > 0) echo '!'; ?>
					</li>
					<li class = 'next<?php if ($achievement_count <= 10) echo ' disabled'; ?>'>
						<a href = '#'>Next &rarr;</a>
					</li>
				</ul>
			</th>
		</tr>
	</tfoot>
	<tbody>
		<?php include 'view/achievements.php'; ?>
	</tbody>
</table>

<h2>Comments</h2>
<table id = 'user_comments' class = 'table table-bordered table-striped table-hover table-condensed' data-total = '<?php echo $comments_count; ?>'>
	<thead>
		<tr>
			<th>Game</th>
			<th>Achievement</th>
			<th>Comment</th>
			<th>When</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan = '4'>
				<ul class = 'pager'>
					<li class = 'previous disabled'>
						<a href = '#'>&larr; Previous</a>
					</li>
					<li>
						<?php echo $comments_count; ?> Comment<?php if ($comments_count != 1) echo 's'; ?><?php if ($comments_count > 0) echo '!'; ?>
					</li>
					<li class = 'next<?php if ($comments_count <= 10) echo ' disabled'; ?>'>
						<a href = '#'>Next &rarr;</a>
					</li>
				</ul>
			</th>
		</tr>
	</tfoot>
	<tbody>
		<?php include 'view/achievement_comments.php'; ?>
	</tbody>
</table>

<?php if ($created_count) : ?>
<h2>Created Achievements</h2>
<table id = 'user_created_achievements' class = 'table table-bordered table-striped table-hover table-condensed' data-total = '<?php echo $created_count; ?>'>
	<thead>
		<tr>
			<th>Game</th>
			<th>Achievement</th>
			<th>When</th>
			<th>Comments</th>
			<th>Achievers</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan = '5'>
				<ul class = 'pager'>
					<li class = 'previous disabled'>
						<a href = '#'>&larr; Previous</a>
					</li>
					<li>
						<?php echo $created_count; ?> Comment<?php if ($created_count != 1) echo 's'; ?><?php if ($created_count > 0) echo '!'; ?>
					</li>
					<li class = 'next<?php if ($created_count <= 10) echo ' disabled'; ?>'>
						<a href = '#'>Next &rarr;</a>
					</li>
				</ul>
			</th>
		</tr>
	</tfoot>
	<tbody>
		<?php include 'view/created_achievements.php'; ?>
	</tbody>
</table>
<?php endif; ?>