<div class = 'page-header'>
	<h1><?php echo $this->game->name; ?></h1>
</div>

<script>
	var game_id = <?php echo $this->game->id; ?>;
</script>

<div class = 'row'>
	<div class = 'span4'>
		<div class = 'center'>
			<img src = '/images/game/<?php echo $this->game->id; ?>/300' />
		</div>

		<div class = 'well' style = 'margin-top: 10px;'>
			<p><strong>Systems:</strong></p>
			<ul class = 'clearfix' id = 'game-systems'>
				<?php foreach ($systems as $s) : ?>
				<li>
					<a href = '/games/<?php echo $s['developer']; ?>/<?php echo $s['slug']; ?>/all' title = '<?php echo html_quotes($s['name']); ?>' rel = 'tooltip'><?php echo strtoupper($s['slug']); ?></a>
				</li>
				<?php endforeach; ?>
			</ul>

			<hr>

			<p><strong>Links:</strong></p>

			<ul class = 'clearfix' id = 'links'>
				<?php include '_links.php'; ?>
			</ul>

			<hr>

			<div class = 'btn-group'>
				<a class = 'btn dropdown-toggle' data-toggle='dropdown' href='#'>
					<i class = 'icon-cog'></i>
					Help keep OSA Tidy!
					<span class = 'caret'></span>
				</a>
				<ul class = 'dropdown-menu'>
					<li><a href = '#' class = 'suggest-link'><i class = 'icon-share-alt'></i> Suggest a new link</a></li>
					<li><a href = '#' class = 'bad-link'><i class = 'icon-fire'></i> Bad link, Kill it with fire!</a></li>
					<!-- <li><a href='#'><i class = 'icon-pencil'></i> Suggest Edits</a></li> -->
					<li><a href='#' class = 'flag-as-inappropriate'><i class = 'icon-warning-sign'></i> Flag as Inappropriate</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class = 'span8'>
		<a href = '/achievement/create/{game_id}' class = 'btn btn-primary flr'>
			Create Achievement
		</a>
		<div class="btn-group flr achieved-toggle" data-toggle="buttons-radio" style = 'margin-right: 10px;'>
			<button class="btn btn-info active" rel = 'all'>All</button>
			<button class="btn btn-info" rel = 'achieved'>Achieved (<span></span>)</button>
			<button class="btn btn-info" rel = 'unachieved'>Unachieved (<span></span>)</button>
		</div>

		<h1>Achievements</h1>
		<br />
		<div id = 'achievements' class = 'row'>
			<div class = 'span8'>
			<?php include '_achievements.php'; ?>
			</div>
		</div>
	</div>
</div>

<?php include '_view_modals.php'; ?>