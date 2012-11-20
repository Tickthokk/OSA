<div class = 'hero-unit'>
	<h1>Achieve Anything</h1>
</div>

<h2>Leaderboard</h2>
<table id = 'leaderboard' class = 'table table-bordered table-striped table-hover table-condensed'>
	<thead>
		<tr>
			<th>User</th>
			<th>Tally</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($leaderboard as $l) : ?>
		<tr>
			<td><a href = '/user/<?php echo $l['username']; ?>'><?php echo $l['username']; ?></a></td>
			<td><?php echo $l['achievement_tally']; ?> Achievements</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<h2>Achievement Activity</h2>
<table id = 'achievement_activity' class = 'table table-bordered table-striped table-hover table-condensed'>
	<thead>
		<tr>
			<th>Game</th>
			<th>Achievement</th>
			<th>User</th>
			<th>When</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($achievement_activity as $aa) : ?>
		<tr>
			<td><?php echo $aa['game_name']; ?></td>
			<td><?php echo $aa['achievement_name']; ?></td>
			<td><a href = '/user/<?php echo $aa['username']; ?>'><?php echo $aa['username']; ?></a></td>
			<td><?php echo $aa['achieved']; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<h2>Comment Activity</h2>
<table id = 'comment_activity' class = 'table table-bordered table-striped table-hover table-condensed'>
	<thead>
		<tr>
			<th>Game</th>
			<th>Achievement</th>
			<th>User</th>
			<th>Comment</th>
			<th>When</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($achievement_comment_activity as $ca) : ?>
		<tr>
			<td><?php echo $ca['game_name']; ?></td>
			<td><?php echo $ca['achievement_name']; ?></td>
			<td><a href = '/user/<?php echo $ca['username']; ?>'><?php echo $ca['username']; ?></a></td>
			<td><?php echo Markdown($ca['comment']); ?></td>
			<td><?php echo $ca['added']; ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>