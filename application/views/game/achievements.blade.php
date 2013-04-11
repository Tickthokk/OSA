@foreach ($game->achievements as $achievement)
<div class='well achievement' data-name='{{ htmlentities($achievement->name, ENT_QUOTES) }}' data-tags='{{ htmlentities($achievement->compact_tags, ENT_QUOTES) }}' data-achieved='@if($achievement->user_achieved)yes@endif' data-id='{{ $achievement->id }}' data-achievers='{{ $achievement->achiever_tally }}'>
	<a href='/achievement/{{ $achievement->id }}'><img src='/img/icons/{{ $achievement->icon->filename }}.svg' class='icon svg' data-color='{{ str_pad(dechex($achievement->icon_color), 6, STR_PAD_LEFT) }}' data-bg='{{ str_pad(dechex($achievement->icon_background), 6, STR_PAD_LEFT) }}'></a>
	<div class='info'>
		<div class='flr'>
			<div class='stats'>
				<div class='flr'>
					<span class='bit-font'>{{ $achievement->achiever_tally }}</span>
					<i class='icon-certificate' title='Achievers' rel='tooltip'></i>
				</div>
			</div>
			@if ($achievement->system_exclusive)
			<div class='label label-inverse system-exclusive clear_right' title='{{ htmlentities(strtoupper($achievement->system->slug), ENT_QUOTES) }} Exclusive' rel='tooltip'>
				<div>
					{{ strtoupper($achievement->system->slug) }}
				</div>
			</div>
			@endif
		</div>
		<h2 class='title'><a href='/achievement/{{ $achievement->id }}'>{{ Str::limit($achievement->name, 25) }}</a></h2>
		
		<p class='description'>{{ Str::limit(strip_tags($achievement->description), 80) }}</p>
		
		@if ($achievement->user_achieved)
		<p class='i-did-it bit-font'>
			<i class='icon-star'></i>
			You completed this achievement {{ DateFmt::Format('AGO[t]IF-FAR[on M_ d#, y##]', strtotime($achievement->users[0]->pivot->created_at)) }}!
		</p>
		@endif
	</div>
</div>
@endforeach