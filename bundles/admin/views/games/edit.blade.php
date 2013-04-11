@layout('admin::layouts.admin')

@section('title') Edit - Games - Admin Panel @endsection

@section('content')

<h1>Edit</h1>

<form action='/admin/games/edit/{{ $game->id }}' method='post' class='form-horizontal'>
	<fieldset>
		<div class='control-group'>
			<label class='control-label'>
				Name
			</label>
			<div class='controls'>
				<input type='text' id='name' name='name' class='input-xxlarge' value='{{ htmlentities($game->name, ENT_QUOTES) }}'>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>
				Slug
			</label>
			<div class='controls'>
				<span class='input-xxlarge uneditable-input' id='slug_display'>{{ htmlentities($game->slug, ENT_QUOTES) }}</span>
				<input type='hidden' id='slug' name='slug' value='{{ htmlentities($game->slug, ENT_QUOTES) }}'>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>
				First Letter
			</label>
			<div class='controls'>
				<span class='input-mini uneditable-input' id='first_letter_display'>{{ htmlentities($game->first_letter, ENT_QUOTES) }}</span>
				<input type='hidden' id='first_letter' name='first_letter' value='{{ htmlentities($game->first_letter, ENT_QUOTES) }}'>
				<span class='help-inline'>
					Blank is an acceptable value for non-alpha names
				</span>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>
				Systems
			</label>
			<div class='controls'>
				@foreach($systems as $system)
				<div>
					<label class='checkbox'>
						<input name='systems[]' type='checkbox' value='{{ $system->id }}'@if(in_array($system->id, $game_systems)) checked='checked'@endif>
						{{ $system->name }}
					</label>
				</div>
				@endforeach
			</div>
		</div>
		<div class='form-actions'>
			<button name='submit' value='submit' type='submit' class='btn btn-primary'>Save changes</button>
			<button name='cancel' class='btn'>Cancel</button>
		</div>
	</fieldset>
</form>

@endsection

@section('javascript')
{{ HTML::script('js/admin/games/edit.js') }}
@endsection