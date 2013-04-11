<h4>Editing {{ $flag->id }}</h4>
<div class='control-group'>
	<label class='control-label'>
		Status
	</label>
	<div class='controls'>
		<select name='admin_id'>
			<option value='0'@unless($flag->admin_id) selected='selected'@endunless>Open</option>
			<option value='{{ Auth::user()->id }}'@if($flag->admin_id) selected='selected'@endif>Closed</option>
		</select>
	</div>
	<label class='control-label'>
		Reason
	</label>
	<div class='controls'>
		<textarea name='reason' rows='4'>{{ $flag->reason }}</textarea>
	</div>
</div>
