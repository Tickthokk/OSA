<section>
	<header>
		<h1>Manage <?php echo $site; ?>'s Status</h1>
	</header>
	<form class = 'form-horizontal' action = '/admin/links/edit/<?php echo $link_id; ?>' method = 'POST'>
		<div class = 'row-fluid'>
			<div class = 'span12'>
				<div class = 'control-group'>
					<label class = 'control-label'>Game</label>
					<div class = 'controls'>
						<?php echo $game_input; ?>
					</div>
				</div>
				<div class = 'help-block'>
					<p>
						<strong>Safety First:</strong> Visit URL's at your own risk
					</p>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>URL</label>
					<div class = 'controls'>
						<?php echo $url_input; ?>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label'>Site</label>
					<div class = 'controls'>
						<?php echo $site_input; ?>
					</div>
				</div>
			</div>
		</div>
		<div class = 'form-actions'>
			<a href='/admin/links/delete/<?php echo $link_id; ?>' class = 'btn btn-danger fll'>Delete Link</a>
			<?php if ( ! $approved) : ?>
			<button type = 'submit' name = 'approve' class = 'btn btn-success'>Approve &amp; Update Link</button>
			<?php endif; ?>
			<button type = 'submit' name = 'submit' class = 'btn btn-primary'>Update Link</button>
			<button type = 'submit' name = 'cancel' class = 'btn'>Cancel</button>
		</div>
	</form>
</section>
<section>
	<header>
		<h1>Flags</h1>
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
		<script type = 'text/javascript'>
			var default_oSearch = '';
			var only_for = parseInt(<?php echo $link_id; ?>);
		</script>
		<div class = 'span12 clearfix'>
			<?php include APPPATH . 'views/admin/link_flags/_datatable.php'; ?>
		</div>
	</div>
</section>