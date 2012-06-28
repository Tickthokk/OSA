<h1>Login</h1>
<br />

<?php echo form_open('user/login', 'class="form-horizontal well"'); ?>
	<fieldset>
		<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>

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
				<a href = '/user/forgotten-password' style = 'margin-left: 10px;'>I forgot my password &#9785;</a>
			</div>
		</div>
		<div class = 'form-actions'>
			<a href = '/user/register' class = 'btn btn-info btn-large' style = 'float: right;'>No Account? Register!</a>

			<button class = 'btn-large btn-primary btn'>
				Log In!
			</button>
		</div>
	</fieldset>
</form>