<div class = 'page-header'>
	<h1><?php echo $game->name; ?></h1>
</div>

<div>
	<img src = 'http://placehold.it/200x200' />
</div>

<?php foreach ($this->achievements->get_all() as $a) : ?>
<div class = 'well'>
	<?php echo $a['name']; ?>
</div>
<?php endforeach; ?>