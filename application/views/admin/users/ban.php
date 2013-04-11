<section>
	<header>
		<h1>Manage <?php echo $username; ?>'s Ban Status</h1>
	</header>
	<form class = 'form-horizontal' action = '/admin/users/ban/<?php echo $user_id; ?>' method = 'POST'>
		<div class = 'row-fluid'>
			<div class = 'span12'>
				<div class = 'control-group'>
					<label class = 'control-label'>Ban Status</label>
					<label class = 'checkbox'>
						<?php echo $ban_checkbox; ?> Banned
					</label>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label' for = 'reason'>Reason</label>
					<div class = 'controls'>
						<?php echo $ban_reason; ?>
					</div>
				</div>
			</div>
		</div>
		<div class = 'form-actions'>
			<button type = 'submit' name = 'submit' class = 'btn btn-primary'>Update User</button>
			<button type = 'submit' name = 'cancel' class = 'btn'>Cancel</button>
		</div>
	</form>
</section>