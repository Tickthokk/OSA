<h1>Reset Your Password</h1>
<br />

<?php echo form_open('user/forgotten-password', 'class="form-horizontal well"'); ?>
	<fieldset>
		<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>

		<p>Your new password will be emailed to you</p>

		<div class = 'control-group'>
			<label class = 'control-label' for = 'email_or_username'>Email or Username</label>
			<div class = 'controls'>
				<?php echo form_input('email_or_username', $email_or_username, 'id="email_or_username"'); ?>
			</div>
		</div>
		<div class = 'form-actions'>
			<button class = 'btn-large btn-primary btn'>
				Reset My Password
			</button>
		</div>
	</fieldset>
</form>