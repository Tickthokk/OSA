@layout('admin::layouts.admin')

@section('title') Edit - Achievement - Admin Panel @endsection

@section('content')

<h1>Edit</h1>

<form action='/admin/achievements/edit/{{ $achievement->id }}' method='post' class='form-horizontal'>
	<fieldset>
		<div class='control-group'>
			<label class='control-label'>
				Name
			</label>
			<div class='controls'>
				<input type='text' id='name' name='name' class='input-xxlarge' value='{{ htmlentities($achievement->name, ENT_QUOTES) }}'>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>
				Description
			</label>
			<div class='controls'>
				<textarea id='description' name='description' class='input-xxlarge'>{{ $achievement->description }}</textarea>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>
				System Exclusive
			</label>
			<div class='controls'>
				<select name='system_exclusive' id='system_exclusive'>
					<option value=''>All Systems</option>
					@foreach($systems as $system)
					@if(in_array($system->id, $game_systems))
					<option value='{{ $system->id }}'
						@if($achievement->system_exclusive==$system->id) 
						selected='selected'
						@endif
					>{{ strtoupper($system->slug) }}</option>
					@endif
					@endforeach
				</select>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>
				Created At
			</label>
			<div class='controls'>
				<input type='text' id='created_at' name='created_at' class='input-large' value='{{ htmlentities($achievement->created_at, ENT_QUOTES) }}'>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>
				Updated At
			</label>
			<div class='controls'>
				<input type='text' id='updated_at' name='updated_at' class='input-large' value='{{ htmlentities($achievement->updated_at, ENT_QUOTES) }}'>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>
				Icon
			</label>
			<div class='controls'>
				<select name='icon_id' id='icon_id'>
					@foreach($icons as $icon)
					<option value='{{ $icon->id }}'@if($achievement->icon_id == $icon->id) selected='selected'@endif>{{ $icon->filename }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>
				Icon Color
			</label>
			<div class='controls'>
				<input type='text' id='icon_color' name='icon_color' class='input-small' 
					@unless(is_null($achievement->icon_color))
					value='{{ str_pad(dechex($achievement->icon_color), 6, STR_PAD_LEFT) }}'
					@endunless
				>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>
				Icon Background Color
			</label>
			<div class='controls'>
				<input type='text' id='icon_background' name='icon_background' class='input-small' 
					@unless(is_null($achievement->icon_background))
					value='{{ str_pad(dechex($achievement->icon_background), 6, STR_PAD_LEFT) }}'
					@endunless
				>
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
{{ HTML::script('js/admin/achievements/edit.js') }}
@endsection