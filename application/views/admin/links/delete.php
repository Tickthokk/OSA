<section>
	<header>
		<h1>Delete Link: <?php echo $site; ?></h1>
	</header>
	<form class = 'form-horizontal' action = '/admin/links/delete/<?php echo $link_id; ?>' method = 'POST'>
		<div class = 'row-fluid'>
			<div class = 'span12'>
				<p>
					<?php echo $url; ?>
				</p>
				<p>
					This cannot be undone.
				</p>
			</div>
		</div>
		<div class = 'form-actions'>
			<button type = 'submit' name = 'submit' class = 'btn btn-danger'>Delete Link</button>
			<button type = 'submit' name = 'cancel' class = 'btn'>Cancel</button>
		</div>
	</form>
</section>