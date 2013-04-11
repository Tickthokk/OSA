@if ($chosen_developer != 'all' || $chosen_letter != 'all')
<div class='alert alert-info'>
	<h4>
		Now viewing
		@if ($chosen_developer != 'all' || $chosen_system != 'all')
		<span>
			<u>@if ($chosen_system AND $chosen_system != 'all')
				{{ strtoupper($chosen_system) }}
				@else
				{{ ucfirst($chosen_developer) }}
				@endif
				games</u>
			@if ($chosen_developer != 'all')
			<sup>
				<a href='/games{{ $chosen_letter != 'all' ? '/all/all/' . (is_null($chosen_letter) ? 'Special' : $chosen_letter) : '' }}'><i class='icon-remove'></i></a>
			</sup>
			@endif
		</span>
		@else
		games
		@endif

		@if (is_null($chosen_letter))
		beginning with a 
		<span>
			<u>special character</u>
			<sup>
				<a href='/games/{{ $chosen_developer ?: 'all' }}/{{ $chosen_system ?: 'all' }}/all'><i class='icon-remove'></i></a>
			</sup>
		</span>
		@elseif ( ! empty($chosen_letter) AND $chosen_letter != 'all')
		beginning with the
		<span>
			<u>letter {{ strtoupper($chosen_letter) }}</u>
			<sup>
				<a href='/games/{{ $chosen_developer ?: 'all' }}/{{ $chosen_system ?: 'all' }}/all'><i class='icon-remove'></i></a>
			</sup>
		</span>
		@endif
	</h4>
</div>
@else
<div class='alert alert-info'>
	Only eight random games are shown.  Please filter game results below, or search above!
</div>
@endif