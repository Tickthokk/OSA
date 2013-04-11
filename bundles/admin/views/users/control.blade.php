<h4>Editing {{ $user->username }}</h4>
<div class='control-group'>
	<label class='control-label'>
		Banned
	</label>
	<div class='controls'>
		<select name='banned'>
			<option value='0'@unless($user->banned) selected='selected'@endunless>Not Banned</option>
			<option value='1'@if($user->banned) selected='selected'@endif>Banned</option>
		</select>
	</div>
	<label class='control-label'>
		Ban Reason
	</label>
	<div class='controls'>
		<textarea name='ban_reason' rows='2'>{{ $user->ban_reason }}</textarea>
	</div>
	<label class='control-label'>
		Active
	</label>
	<div class='controls'>
		<select name='activated'>
			<option value='0'@unless($user->activated) selected='selected'@endunless>Not Activated</option>
			<option value='1'@if($user->activated) selected='selected'@endif>Activated</option>
		</select>
	</div>
</div>
