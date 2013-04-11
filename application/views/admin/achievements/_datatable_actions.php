<div class = 'flr'>
	<?php if ($flagged) : ?>
	<i class="icon-flag"></i>
	<?php endif; ?>
	<i class="icon-certificate<?php if ($achievers == 0) echo ' opaque'; ?>" rel="tooltip" title="{achievers} Achievers"></i>
</div>

<a href='/admin/achievements/edit/<?php echo $achievement_id; ?>'>Edit Achievement</a>