<div class = 'flr'>
	<?php if ($banned) : ?>
	<i class = 'icon-flag' rel = 'tooltip' title = 'Banned: <?php echo html_quotes($ban_reason); ?>'></i>
	<?php endif; ?>
	<?php if ( ! $activated) : ?>
	<i class = 'icon-user' rel = 'tooltip' title = 'Not Activated'></i>
	<?php endif; ?>
	<?php if ($level) : ?>
	<i class = 'icon-fire' rel = 'tooltip' title = '<?php echo $level == 1 ? 'Administrator' : 'Moderator'; ?>'></i>
	<?php endif; ?>
	<i class="icon-certificate" rel="tooltip" title="{achievement_tally} Achievements"></i>
</div>

<div>
	<a href = '/admin/manage/acl/<?php echo $user_id; ?>' rel = 'tooltip' title = 'Change their Access Control Level'>
		ACL
	</a>,
	<a href = '/admin/manage/ban/<?php echo $user_id; ?>' rel = 'tooltip' title = 'Change their Ban status and reason'>
		Ban
	</a>,
	<a href = '/admin/manage/active/<?php echo $user_id; ?>' rel = 'tooltip' title = 'Change their Active status'>
		Active
	</a>
</div>