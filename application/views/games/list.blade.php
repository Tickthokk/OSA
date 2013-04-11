<ul class='game-list thumbnails'>
	<?php $i = 0; ?>
	@foreach ($games as $game)
	<li class='span3<?php if ($i++ % 4 == 0) echo ' clear'; ?>'>
		<div class='thumbnail'>
			<div class='game-thumb thumbnail'>
				<a href='/game/{{ $game->id }}#{{ $game->slug }}'><img alt='' src='/game/image/{{ $game->id }}/180/180' /></a>
			</div>
			<div class='caption'>
				<h4>
					<a href='/game/{{ $game->id }}#{{ $game->slug }}'>
						{{ $game->name }}
					</a>
				</h4>
				<p>
					{{ $game->achievement_tally }} Achievements
				</p>
			</div>
		</div>
	</li>
	@endforeach
</ul>
<div class='center'>
	{{ $pagination }}
</div>