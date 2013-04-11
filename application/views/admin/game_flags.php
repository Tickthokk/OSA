<script type = 'text/javascript'>
	var default_oSearch = '{search}';
</script>
<section>
	<header>
		<h1>Game Flags</h1>
	</header>
	<div class = 'row-fluid'>
		<div class = 'flr'>
			<ul class = 'special_search'>
				<li>
					<strong>Special Searches:</strong>
				</li>
				<li>
					<a href = '#' data-search = 'unsolved'>Unsolved</a>
				</li>
				<li>
					<a href = '#' data-search = 'solved'>Solved</a>
				</li>
			</ul>
		</div>
		<div class = 'span12 clearfix'>
			<?php include APPPATH . 'views/admin/game_flags/_datatable.php'; ?>
		</div>
	</div>
</section>