@foreach ($developers as $developer)
<ul class='breadcrumb game-developer'>
	<li>
		<big>
			<strong>
				<a href='/games/{{ $developer->slug }}/all/{{ $chosen_letter ?: 'all' }}'>{{ ucfirst($developer->slug) }}</a>
			</strong>
		</big>
	</li>
	@foreach (array('c' => 'consoles', 'p' => 'portables', '' => 'other') as $type_key => $type)
	<?php 
		$systems = array(); 
		foreach ($developer->systems as $system)
			if ($system->type == $type_key)
				$systems[] = $system;

		if ( ! count($systems))
			continue;
	?>
	<li>
		<ul class='breadcrumb game-platform'>
			<li class='portable-divider'>
				<span class='divider'>|</span>
				{{ ucfirst($type) }}
				<span class='divider'>|</span>
			</li>
			@foreach ($systems as $system)
			<li class='game-system' title='{{ $system->slug }}'>
				<em>
					<a href='/games/{{ $developer->slug }}/{{ $system->slug }}/{{ $chosen_letter ?: 'all' }}'>{{ strtoupper($system->slug) }}</a>
				</em>
			</li>
			@endforeach
		</ul>
	</li>
	@endforeach
</ul>
@endforeach