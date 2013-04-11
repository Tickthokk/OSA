<div class = 'flr'>
	<?php if ($flagged) : ?>
	<i class = 'icon-flag' rel = 'tooltip' title = 'Unresolved Flags'></i>
	<?php endif; ?>
	<i class="icon-certificate" rel="tooltip" title="{achievement_tally} Achievements"></i>
</div>

<div class = 'actions'>
	<a href = '/admin/games/edit/<?php echo $game_id; ?>' rel = 'tooltip' title = 'Edit Game'>Edit</a>
</div>