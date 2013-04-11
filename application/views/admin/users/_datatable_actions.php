<div class = 'flr'>
	<?php if ($banned) : ?>
	<i class = 'icon-flag' rel = 'tooltip' title = 'Banned: <?php echo html_quotes(truncate_text($ban_reason, 20)); ?>'></i>
	<?php endif; ?>
	<?php if ( ! $activated) : ?>
	<i class = 'icon-user' rel = 'tooltip' title = 'Not Activated'></i>
	<?php endif; ?>
	<?php if ($level) : ?>
	<i class = 'icon-fire' rel = 'tooltip' title = '<?php echo $level == 1 ? 'Administrator' : 'Moderator'; ?>'></i>
	<?php endif; ?>
	<i class="icon-certificate" rel="tooltip" title="{achievement_tally} Achievements"></i>
</div>

<div class = 'actions'>
	<a href = '/admin/users/acl/<?php echo $user_id; ?>' rel = 'tooltip' title = 'Change their Access Control Level'>ACL</a>
	<a href = '/admin/users/ban/<?php echo $user_id; ?>' rel = 'tooltip' title = 'Change their Ban status and reason'>Ban</a>
	<a href = '/admin/users/active/<?php echo $user_id; ?>' rel = 'tooltip' title = 'Change their Active status'>Active</a>
</div>