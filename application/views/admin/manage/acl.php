<section>
	<header>
		<h1>Manage <?php echo $username; ?>'s Access Control Level</h1>
	</header>
	<form class = 'form-horizontal' action = '/admin/manage/acl/<?php echo $user_id; ?>' method = 'POST'>
		<div class = 'row-fluid'>
			<div class = 'span12'>
				<div class = 'control-group'>
					<label class = 'control-label'>Access Control Level</label>
					<div class = 'controls'>
						<select>
							<option value = '0'>None</option>
							<option value = '1'>Administrator</option>
							<option value = '9'>Moderator</option>
						</select>
					</div>
				</div>
				<div class = 'control-group'>
					<label class = 'control-label' for = 'pass'>Your Password, to Confirm</label>
					<div class = 'controls'>
						<input type = 'password' class = 'input-large' id = 'pass'>
					</div>
				</div>
			</div>
		</div>
		<div class = 'form-actions'>
			<button type = 'submit' name = 'submit' class = 'btn btn-primary'>Save changes</button>
			<button type = 'submit' name = 'cancel' class = 'btn'>Cancel</button>
		</div>
	</form>
</section>
<section style = 'min-height: 150px;'>
	<!-- Looks weird with a bunch of non-white space -->
</section>