<li>
	<i class = 'icon-star'></i>
	<a href = 'http://en.wikipedia.org/wiki/<?php echo $wikiSlug; ?>' class = 'external-game-link' data-id = 'wiki'>Wikipedia</a>
</li>
<?php foreach ($links as $link) : ?>
<li class = '<?php if ( ! $link['approved']) echo 'unapproved'; ?>'>
	<i class = 'star icon-star<?php if ( ! $link['approved']) echo '-empty'; ?>' rel = 'tooltip' title = 'Warning: Link has not been approved by a Moderator'></i>
	<a href = '<?php echo $link['url']; ?>' class = 'external-game-link' data-id = '<?php echo $link['id']; ?>'><?php echo $link['site']; ?></a>
	<?php if ($link['flagged']) : ?>
	<i class = 'icon-thumbs-down' rel = 'tooltip' title = 'Link has been reported as bad'></i>
	<?php endif; ?>
</li>
<?php endforeach; ?>