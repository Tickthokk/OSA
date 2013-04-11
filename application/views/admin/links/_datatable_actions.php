<?php if ($flagged) : ?>
<div class = 'flr'>
	<i class = 'icon-flag' rel = 'tooltip' title = '{flagged} Users have flagged this site'></i>
</div>
<?php endif; ?>

<div class = 'actions'>
	<a href = '/admin/links/edit/<?php echo $link_id; ?>' rel = 'tooltip' title = 'Edit Link'>View Status</a>
</div>