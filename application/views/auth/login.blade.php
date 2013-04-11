@layout('layouts.common')

@section('title') Login @endsection

@section('content')
<h1>Login</h1>
<br>

{{ Form::open('/auth/login', 'POST', array('class' => 'form-horizontal well')) }}
	<fieldset>
		<div class='control-group'>
			<label class='control-label' for='username'>Username</label>
			<div class='controls'>
				<input type='text' name='username' id='username' value='{{ $username }}'>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label' for='password'>
				Password
			</label>
			<div class='controls'>
				<input type='password' name='password' id='password' value=''>
				<a href='/auth/forgotten-password' style='margin-left: 10px;'>I forgot my password &#9785;</a>
			</div>
		</div>
		@if ($username)
		<p class='help-block'>Remember: usernames and passwords are case sensetive.</p>
		@endif
		<div class='form-actions'>
			<a href='/auth/register' class='btn btn-info btn-large flr'>No Account? Register!</a>

			<button class='btn-large btn-primary btn'>
				Log In!
			</button>
		</div>
	</fieldset>
{{ Form::close() }}

@endsection