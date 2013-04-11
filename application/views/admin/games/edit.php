<section>
	<header>
		<h1>Manage <?php echo $game_name; ?></h1>
	</header>
	<form class = 'form-horizontal' action = '/admin/games/edit/<?php echo $game_id; ?>' method = 'POST'>
		<div class = 'row-fluid'>
			<div class = 'span12'>
				<div class = 'help-block'>
					<p>First Letter will be automatically updated based on the Name</p>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Name</label>
					<div class = 'controls'>
						<?php echo $name_input; ?>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Slug</label>
					<div class = 'controls'>
						<?php echo $slug_input; ?>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Wiki Slug</label>
					<div class = 'controls'>
						<?php echo $wiki_input; ?>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Systems</label>
					<div class = 'controls'>
						<?php foreach ($systems as $s) : ?>
						<label class = 'checkbox'>
							<?php echo $s['checkbox']; ?>
							<?php echo $s['name']; ?>
						</label>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<div class = 'form-actions'>
			<button type = 'submit' name = 'submit' class = 'btn btn-primary'>Update Game</button>
			<button type = 'submit' name = 'cancel' class = 'btn'>Cancel</button>
		</div>
	</form>
</section>
<section>
	<header>
		<h1>Flags</h1>
	</header>
	<div class = 'row-fluid'>
		<div class = 'flr'>
			<ul class = 'special_search'>
				<li>
					<strong>Special Searches:</strong>
				</li>
				<li>
					<a href = '#' data-search = 'unsolved'>Unsolved</a>
				</li>
				<li>
					<a href = '#' data-search = 'solved'>Solved</a>
				</li>
			</ul>
		</div>
		<script type = 'text/javascript'>
			var default_oSearch = '';
			var only_for = parseInt(<?php echo $game_id; ?>);
		</script>
		<div class = 'span12 clearfix'>
			<?php include APPPATH . 'views/admin/game_flags/_datatable.php'; ?>
		</div>
	</div>
</section>