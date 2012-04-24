<ul class = 'game-list thumbnails'>
	<?php 
		foreach ($games as $g) : 
			$link = '/game/' . $g['id'] . '#' . $g['slug'];
	?>
	<li class = 'span3'>
		<div class = 'thumbnail'>
			<!-- <a class = 'thumbnail' href = '/game/<?php echo $g['slug']; ?>'><img alt = '' src = 'http://placehold.it/260x180' /></a> -->
			<div class = 'game-thumb thumbnail'>
				<a href = '<?php echo $link; ?>'><img alt = '' src = '/images/game/<?php echo $g['slug']; ?>' /></a>
			</div>
			<div class = 'caption'>
				<h4>
					<a href = '<?php echo $link; ?>'>
						<?php echo $g['name']; ?>
					</a>
				</h4>
				<?php if (isset($g['systemSlug'])) : ?>
				<p>
					<?php echo strtoupper($g['systemSlug']); ?>
				</p>
				<?php endif; ?>
				<p>
					<?php echo rand(12,26); ?> Achievements
				</p>
			</div>
		</div>
	</li>
	<?php endforeach; ?>
</ul>