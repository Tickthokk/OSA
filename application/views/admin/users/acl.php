<section>
	<header>
		<h1>Manage <?php echo $username; ?>'s Access Control Level</h1>
	</header>
	<form class = 'form-horizontal' action = '/admin/users/acl/<?php echo $user_id; ?>' method = 'POST'>
		<div class = 'row-fluid'>
			<div class = 'span12'>
				<div class = 'control-group'>
					<label class = 'control-label'>Access Control Level</label>
					<div class = 'controls'>
						<?php echo $level_select; ?>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label' for = 'pass'>Your Password, to Confirm</label>
					<div class = 'controls'>
						<input type = 'password' name = 'password' class = 'input-large' id = 'pass'>
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