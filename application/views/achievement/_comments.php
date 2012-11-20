<?php foreach ($comments as $c) : ?>
<div class = 'well user_comment' data-id = '<?php echo $c['id']; ?>' data-comment = '<?php echo html_quotes($c['comment']); ?>'>
	<div class = 'buttons'>
		<?php if (($c['added_by'] == $this->user->id && ($c['modified_by'] && $c['modified_by'] == $c['added_by'])) || $this->user->is_moderator()) : ?>
		<span class = 'btn btn-warning btn-mini edit_comment' data-toggle = 'modal' data-target = 'comment_editing'>
			<i class = 'icon-pencil icon-white'></i>
			Edit
		</span>
		<span class = 'btn btn-danger btn-mini delete_comment'>
			<i class = 'icon-trash icon-white'></i>
			Delete
		</span>
		<?php elseif ($c['added_by'] == $this->user->id && ($c['modified_by'] && $c['modified_by'] != $c['added_by'])) : ?>
		<i class = 'icon-ban-circle' title = 'A moderator has edited your comment. You no longer own this comment.'></i>
		<?php endif; ?>
	</div>
	<p>
		<a href = '/user/profile/<?php echo $c['added_by']; ?>#<?php echo $c['username']; ?>' title = 'View Profile'><?php echo $c['username']; ?></a>
		<span class = 'created-modified'<?php if ($c['added_by'] != $c['modified_by']) : ?> title = 'Modified: <?php echo parse_sql_timestamp_full($c['modified']); ?> by <?php echo $c['mod_username']; ?>'<?php endif; ?>>
			on <?php echo parse_sql_timestamp_full($c['added']); ?>
		</span>
	</p>
	<blockquote>
		<?php echo markdown($c['comment']); ?>
	</blockquote>
</div>
<?php endforeach; ?>
<?php if ($comments_already_shown + count($comments) < $total_comments) : ?>
	<div class = 'btn btn-info load-more-comments'>Load More Comments...</div>
<?php endif; ?>