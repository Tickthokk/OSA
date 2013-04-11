<div class='game-letters pagination pagination-centered'>
	<ul>
		<li>
			<a href='/games/{{ $chosen_developer ?: 'all' }}/{{ $chosen_system ?: 'all' }}/Special'>#</a>
		</li>
		@foreach(range('A', 'Z') as $l)
		<li>
			<a href='/games/{{ $chosen_developer ?: 'all' }}/{{ $chosen_system ?: 'all' }}/{{ $l }}'>{{ $l }}</a>
		</li>
		@endforeach
	</ul>
</div>