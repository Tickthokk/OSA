<!-- Result Notice -->

<div class = 'alert alert-info'>
	<strong>Searched for</strong> <?php echo $search; ?>
	<?php if (empty($search)) : ?>
	<em>Nothing</em>
</div>
<div class = 'alert alert-error'>
	<strong>Warning</strong>
	The search has been limited to 4 random entries.  Please revise your search.
	<?php endif; ?>
</div>

<!-- Games -->

<h3>Games</h3>

<?php if (empty($games)) : ?>
<div class = 'alert'>No Game Results</div>
<?php else : ?>
<?php include('games/game-list.php'); ?>
<?php endif; ?>

<!-- Achievements -->

<h3>Achievements</h3>

<div class = 'alert'>No Achievement Results</div>