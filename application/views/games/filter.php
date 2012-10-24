<!-- Now Viewing -->

<?php if ($manufacturer != 'all' || $letter != 'all') : ?>
<div class = 'alert alert-info'>
	<h4>
		Now viewing
		<?php if ($manufacturer != 'all' || $system != 'all') : ?>
		<span>
			<u><?php if ($system && $system != 'all') : ?>
				<?php echo strtoupper($system); ?>
				<?php else : ?>
				<?php echo ucfirst($manufacturer); ?>
				<?php endif; ?>
				games</u>
			<?php if ($manufacturer != 'all') : ?>
			<sup>
				<a href = '/games<?php echo $letter != 'all' ? '/all/all/' . $letter : ''; ?>'><i class = 'icon-remove'></i></a>
			</sup>
			<?php endif; ?>
		</span>
		<?php else : ?>
		games
		<?php endif; ?>
		<?php if ($letter && $letter != 'all') : ?>
		beginning with the
		<span>
			<u>letter <?php echo strtoupper($letter); ?></u>
			<sup>
				<a href = '/games/<?php echo $manufacturer ?: 'all'; ?>/<?php echo $system ?: 'all'; ?>'><i class = 'icon-remove'></i></a>
			</sup>
		</span>
		<?php endif; ?>
	</h4>
</div>
<?php else : ?>
<div class = 'alert alert-info'>
	<strong>Warning!</strong> Only 4 random games are shown.  Please filter game results below!
</div>
<?php endif; ?>

<!-- Filtration -->

<!-- Developers and Systems -->
<?php foreach ($developer_systems as $d) : ?>
<ul class = 'breadcrumb'>
	<li>
		<big>
			<strong>
				<a href = '/games/<?php echo $d['slug']; ?>/all/<?php echo $letter ?: 'all'; ?>'><?php echo ucfirst($d['slug']); ?></a>
			</strong>
		</big>
	</li>
	<?php foreach (array('other', 'consoles', 'portables') as $type) : ?>
	<?php if ( ! empty($d[$type])) : ?>
	<li class = 'portable-divider'>
		<span class = 'divider'>|</span>
		<?php echo ucfirst($type); ?>
		<span class = 'divider'>|</span>
	</li>
	<?php foreach ($d[$type] as $s) : ?>
	<li title = '<?php echo $s['title']; ?>'>
		<em>
			<a href = '/games/<?php echo $d['slug']; ?>/<?php echo $s['slug']; ?>/<?php echo $letter ?: 'all'; ?>'><?php echo strtoupper($s['slug']); ?></a>
		</em>
		<?php if ($s != end($d[$type])) : ?>
		<span class = 'divider'>&nbsp;</span>
		<?php endif; ?>
	</li>
	<?php endforeach; ?>
	<?php endif; ?>
	<?php endforeach; ?>
</ul>
<?php endforeach; ?>

<!-- Game Letters -->
<div class = 'game-letters pagination pagination-centered'>
	<ul>
		<li>
			<a href = '/games/'>#</a>
		</li>
		<?php foreach(range('A', 'Z') as $l) : ?>
		<li>
			<a href = '/games/<?php echo $manufacturer ?: 'all'; ?>/<?php echo $system ?: 'all'; ?>/<?php echo $l; ?>'><?php echo $l; ?></a>
		</li>
		<?php endforeach; ?>
	</ul>
</div>

<!-- Games -->
<?php if (empty($games)) : ?>
<div class = 'alert alert-error'>
	<strong>No Results Returned</strong>
</div>
<?php else : ?>
<?php include('game-list.php'); ?>
<?php endif; ?>