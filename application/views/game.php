<div class = 'page-header'>
	<h1><?php echo $this->game->name; ?></h1>
</div>

<div class = 'row'>
	<div class = 'span3'>
		<img src = '/images/game/<?php echo $this->game->slug; ?>' />
	</div>
	<div class = 'span9'>
		<h1>Achievements</h1>
		<br />
		{achievements}
		<div class = 'well'>
			{name}
		</div>
		{/achievements}
		<div class = 'well'>
			<a href = '/create/achievement/{game_id}' class = 'btn btn-primary'>
				Create Achievement
			</a>
		</div>
	</div>
</div>