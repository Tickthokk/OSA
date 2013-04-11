<div class = 'flr'>
	<?php if ($inappropriate_tally) : ?>
	<i class = 'icon-flag' rel = 'tooltip' title = '{inappropriate_tally} Users find this Inappropriate'></i>
	<?php endif; ?>
	<i class="icon-certificate" rel="tooltip" title="Used on {achievement_tally} Achievements"></i>
</div>

<div class = 'actions'>
	<a href = '/admin/tags/edit/<?php echo $tag_id; ?>' rel = 'tooltip' title = 'Edit Tag Status'>Edit Status</a>
</div>