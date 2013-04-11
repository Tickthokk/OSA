@layout('admin::layouts.admin')

@section('title') Edit - Links - Admin Panel @endsection

@section('content')

<h2>Link Edit</h2>

<h3>{{ $link->game->name }}</h3>

<form action='/admin/links/edit/{{ $link->id }}' method='post' class='form-horizontal'>
	<fieldset>
		<div class='control-group'>
			<label class='control-label'>
				Site
			</label>
			<div class='controls'>
				<input type='text' id='site' name='site' class='input-xxlarge' value='{{ htmlentities($link->site, ENT_QUOTES) }}'>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>
				URL
			</label>
			<div class='controls'>
				<input type='text' id='url' name='url' class='input-xxlarge' value='{{ htmlentities($link->url, ENT_QUOTES) }}'>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>
				Approved
			</label>
			<div class='controls'>
				<label class='checkbox'>
					<input name='approved' type='checkbox' value='1'@if($link->admin_id) checked='checked'@endif>
					Yes
				</label>
				@if($admin)
				<span class='help-inline'>
					Approved by {{ $admin->username }}
				</span>
				@endif
			</div>
		</div>
		<div class='form-actions'>
			<button name='submit' value='submit' type='submit' class='btn btn-primary'>Save changes</button>
			<button name='cancel' class='btn'>Cancel</button>
		</div>
	</fieldset>
</form>

@endsection