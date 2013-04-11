<section>
	<header>
		<h1>Manage <?php echo $achievement_name; ?></h1>
	</header>
	<form class = 'form-horizontal' action = '/admin/achievements/edit/<?php echo $achievement_id; ?>' method = 'POST'>
		<div class = 'row-fluid'>
			<div class = 'span12'>
				<div class = 'help-block'>
					<p>First Letter will be automatically updated based on the Name</p>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Game</label>
					<div class = 'controls'>
						<?php echo $game_name_input; ?>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Achievement Name</label>
					<div class = 'controls'>
						<?php echo $name_input; ?>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Description</label>
					<div class = 'controls'>
						<?php echo $description_input; ?>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>System Exclusive</label>
					<div class = 'controls'>
						<?php echo $system_exclusive_input; ?>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Icon</label>
					<div class = 'controls'>
						<button id='icon_chooser' class='btn'>Icon Chooser</button>
						<div>
							<?php /*echo $icon_input; ?>
							<?php echo $icon_color_input; ?>
							<?php echo $icon_bg_input;*/ ?>
						</div>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Tags</label>
					<div class = 'controls'>
						TODO
					</div>
				</div>
			</div>
		</div>
		<div class = 'form-actions'>
			<button type = 'submit' name = 'submit' class = 'btn btn-primary'>Update Achievement</button>
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
			var only_for = parseInt(<?php echo $achievement_id; ?>);
		</script>
		<div class = 'span12 clearfix'>
			<?php include APPPATH . 'views/admin/achievement_flags/_datatable.php'; ?>
		</div>
	</div>
</section>