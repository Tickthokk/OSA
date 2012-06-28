<h1>Register</h1>
<br />

<?php echo form_open('user/register', 'class="form-horizontal well"'); ?>
	<fieldset>
		<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>

		<div class = 'control-group'>
			<label class = 'control-label' for = 'email'>Email</label>
			<div class = 'controls'>
				<?php echo form_input('email', $email, 'id="email"'); ?>
			</div>
		</div>
		<div class = 'control-group'>
			<label class = 'control-label' for = 'username'>Username</label>
			<div class = 'controls'>
				<?php echo form_input('username', $username, 'id="username"'); ?>
			</div>
		</div>
		<div class = 'control-group'>
			<label class = 'control-label' for = 'password'>
				Password
			</label>
			<div class = 'controls'>
				<?php echo form_password('password', '', 'id="password"'); ?>
			</div>
		</div>
		<div class = 'form-actions'>
			<button class = 'btn-large btn-primary btn'>
				Register
			</button>
		</div>
	</fieldset>
</form>