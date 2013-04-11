<section>
	<header>
		<h1>Manage <?php echo $username; ?>'s Active Status</h1>
	</header>
	<form class = 'form-horizontal' action = '/admin/users/active/<?php echo $user_id; ?>' method = 'POST'>
		<div class = 'row-fluid'>
			<div class = 'span12'>
				<div class = 'control-group'>
					<label class = 'control-label'>Active Status</label>
					<label class = 'checkbox'>
						<?php echo $active_checkbox; ?> Active
					</label>
				</div>
			</div>
		</div>
		<div class = 'form-actions'>
			<button type = 'submit' name = 'submit' class = 'btn btn-primary'>Update User</button>
			<button type = 'submit' name = 'cancel' class = 'btn'>Cancel</button>
		</div>
	</form>
</section>