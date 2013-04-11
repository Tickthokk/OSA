<section>
	<header>
		<h1>Manage <?php echo $name; ?>'s Status</h1>
	</header>
	<form class = 'form-horizontal' action = '/admin/tags/edit/<?php echo $tag_id; ?>' method = 'POST'>
		<div class = 'row-fluid'>
			<div class = 'span12'>
				<div class = 'help-block'>
					<p>By marking it default, this tag will display on the achievement creation screen.</p>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Default Status</label>
					<label class = 'checkbox'>
						<?php echo $default_checkbox; ?> Default
					</label>
				</div>
				<div class = 'help-block'>
					<p>By marking this approved, this tag will never be able to be marked as inappropriate.</p>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Approved Status</label>
					<label class = 'checkbox'>
						<?php echo $approved_checkbox; ?> Approved
					</label>
				</div>
			</div>
		</div>
		<div class = 'form-actions'>
			<a href='/admin/tags/delete/<?php echo $tag_id; ?>' class = 'btn btn-danger fll'>Delete Tag</a>
			<button type = 'submit' name = 'submit' class = 'btn btn-primary'>Update Tag</button>
			<button type = 'submit' name = 'cancel' class = 'btn'>Cancel</button>
		</div>
	</form>
</section>
<section>
	<header>
		<h1>Last 10 Users who find this tag Innappropriate</h1>
	</header>
	<?php if ( ! count($inappropriate_list)) : ?>

	<?php else : ?>
	<table class = 'table table-striped table-bordered'>
		<thead>
			<tr>
				<th>ID</th>
				<th>Game</th>
				<th>Achievement</th>
				<th>Who</th>
				<th>When</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($inappropriate_list as $il) : ?>
			<tr>
				<td>
					<?php echo $il['id']; ?>
				</td>
				<td>
					<a href = '/game/<?php echo $il['game_id']; ?>#<?php echo $il['game_slug']; ?>' rel = 'tooltip' title = 'Visit the Game&#39;s page'><?php echo $il['game_name']; ?></a>
				</td>
				<td>
					<a href = '/achievement/<?php echo $il['achievement_id']; ?>' rel = 'tooltip' title = 'Visit the Achievement&#39;s page'><?php echo $il['achievement_name']; ?></a>
				</td>
				<td>
					<a href = '/user/<?php echo $il['username']; ?>' rel = 'tooltip' title = 'Visit the User&#39;s page'><?php echo $il['username']; ?></a>
				</td>
				<td>
					<?php echo $il['when']; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</section>