<section>
	<header>
		<h1>Delete Tag: <?php echo $name; ?></h1>
	</header>
	<form class = 'form-horizontal' action = '/admin/tags/delete/<?php echo $tag_id; ?>' method = 'POST'>
		<div class = 'row-fluid'>
			<div class = 'span12'>
				<p>
					Deleting this tag will also remove it from all related achievements.
				</p>
				<p>
					This cannot be undone.
				</p>
			</div>
		</div>
		<div class = 'form-actions'>
			<button type = 'submit' name = 'submit' class = 'btn btn-danger'>Delete Tag</button>
			<button type = 'submit' name = 'cancel' class = 'btn'>Cancel</button>
		</div>
	</form>
</section>