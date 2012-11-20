<?php foreach ($achievements as $a) : ?>
<div class = 'well achievement <?php echo $a['iDidIt'] ? 'achieved' : 'unachieved'; ?>' data-id = '<?php echo $a['id']; ?>'>
	<a href = '/achievement/<?php echo $a['id']; ?>'><img src = '/assets/images/icons/<?php echo $a['icon']; ?>' class = 'icon'></a>
	<div class = 'info'>
		<div class = 'flr'>
			<div class = 'stats'>
				<div class = 'flr'>
					<i class = 'icon-certificate' title = 'Achievers' rel = 'tooltip'></i> <?php echo $a['achievers']; ?>
				</div>
				<div class = 'flr clear_right'>
					<i class = 'icon-bullhorn' title = 'Comments' rel = 'tooltip'></i> <?php echo $a['comments']; ?>
				</div>
			</div>
			<?php if ($a['systemSlug']) : ?>
			<div class = 'label label-inverse system-exclusive clear_right' title = '<?php echo html_quotes(strtoupper($a['systemSlug'])); ?> Exclusive' rel = 'tooltip'>
				<div>
					<?php echo strtoupper($a['systemSlug']); ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<h2 class = 'title'><a href = '/achievement/<?php echo $a['id']; ?>'><?php echo truncate_text($a['name'], 30); ?></a></h2>
		
		<p class = 'description'><?php echo truncate_text(strip_tags(markdown($a['description'])), 80); ?></p>

		<?php if ($a['iDidIt']) : ?>
		<p class = 'i-did-it'>
			<i class = 'icon-star'></i>
			You completed this achievement on <?php echo parse_sql_timestamp($a['iDidIt']); ?>!
		</p>
		<?php endif; ?>
	</div>
</div>
<?php endforeach; ?>