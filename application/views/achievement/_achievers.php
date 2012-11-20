			<?php if (empty($achievers)) : ?>
			<p>
				<em>None!</em>  Be the first!
			</p>
			<?php else : ?>
		
			<ul>
				<?php foreach ($achievers as $a) : ?>
				<li>
					<a href = '/user/profile/<?php echo $a['id']; ?>#<?php echo $a['username']; ?>' title = 'View Profile'><?php echo $a['username']; ?></a>
					<i class = 'icon-certificate' rel = 'tooltip' title = '<?php echo number_format($a['achievement_tally'], 0, '.', ','); ?> Achievements'></i>
					<i class = 'icon-time' rel = 'tooltip' title = 'Earned <?php echo time_elapsed($a['achieved']); ?> ago'></i>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>