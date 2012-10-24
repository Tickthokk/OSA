<h1>Welcome {username}</h1>

<?php if (@$success) : ?>
<div class = 'alert alert-success'>
	{success}
</div>
<?php elseif (@$error) : ?>
<div class = 'alert alert-error'>
	{error}
</div>
<?php endif; ?>

<a href = '/user/logout' class = 'btn btn-primary'>Logout</a>