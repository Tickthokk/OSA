@layout('layouts.common')

@section('title') {{ $game->name }} @endsection

@section('content')

<div class='page-header'>
	<h1>{{ $game->name }}</h1>
</div>

<div class='row'>
	<div class='span4'>
		<div class='center'>
			<img src='/game/image/{{ $game->id }}' />
		</div>

		<div class='well' style='margin-top: 10px;'>
			<p><strong>Systems:</strong></p>
			<ul class='clearfix' id='game-systems'>
				@foreach ($game->systems as $system)
				<li>
					<a href='/games/{{ $system->developer->slug }}/{{ $system->slug }}/all' title='{{ htmlentities($system->name, ENT_QUOTES) }}' rel='tooltip'>{{ strtoupper($system->slug) }}</a>
				</li>
				@endforeach
			</ul>

			<hr>

			<p><strong>Links:</strong></p>

			<ul class='clearfix' id='links'>
				@include('game.links')
			</ul>

			<hr>

			<div class='btn-group'>
				<a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>
					<i class='icon-cog'></i>
					Help keep OSA Tidy!
					<span class='caret'></span>
				</a>
				<ul class='dropdown-menu'>
					<li><a href='#' class='suggest-link'><i class='icon-share-alt'></i> Suggest a new link</a></li>
					<li><a href='#' class='bad-link'><i class='icon-fire'></i> Bad link, Kill it with fire!</a></li>
					<li><a href='#' class='flag-as-inappropriate'><i class='icon-warning-sign'></i> Flag as Inappropriate</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class='span8'>
		<a href='/achievement/create/{{ $game->id }}' class='btn btn-primary flr'>
			Create Achievement
		</a>

		<h1>Achievements</h1>

		<div class="btn-group flr achieved-toggle" data-toggle="buttons-checkbox" style='margin-right: 10px;'>
			<button class="btn btn-info" data-filter='achieved' rel='tooltipOFF' title='Only show my achievements'>
				<i class='icon-white icon-star'></i> 
				(<span>{{ $user_achieve_tally }}</span>)
			</button>
			<button class="btn btn-info" data-filter='unachieved' rel='tooltipOFF' title='Only show unachieved'>
				<i class='icon-white icon-star-empty'></i> 
				(<span>{{ count($game->achievements) - $user_achieve_tally }}</span>)
			</button>
			<button class='btn dropdown-toggle btn-info' data-toggle='dropdown' data-filter='tag' rel='tooltipOFF' title='Tag Filter'>
				<i class='icon-white icon-tag'></i>
				<span class='caret'></span>
			</button>
			<ul class='dropdown-menu' style='left: auto; right: 0;'>
				<li class='tag' data-tag=''>
					<a href='#'>Clear Filter</a>
				</li>
				<li class='divider'></li>
				@foreach($tag_list as $tag => $tag_count)
				<li class='tag' data-tag='{{ htmlentities($tag, ENT_QUOTES) }}'>
					<a href='#'>{{ $tag }} ({{ $tag_count }})</a>
				</li>
				@endforeach
			</ul>
		</div>

		<div class="btn-group fll sort-toggle" data-toggle="buttons-radio" style='margin-bottom: 10px;'>
			<button class="btn btn-info active" data-sort='id' data-order='desc' rel='tooltipOFF' title='Sort by Date'>
				<i class='icon-white icon-calendar'></i> &nbsp;
				<i class='chevron icon-white icon-chevron-down'></i>
			</button>
			<button class="btn btn-info" data-sort='achievers' data-order='desc' rel='tooltipOFF' title='Sort by Achievers'>
				<i class='icon-white icon-certificate'></i> &nbsp;
				<i class='chevron icon-white icon-chevron-down'></i>
			</button>
			<button class="btn btn-info" data-sort='name' data-order='desc' rel='tooltipOFF' title='Sort Alphabetically'>
				<i class='icon-white icon-font'></i> &nbsp;
				<i class='chevron icon-white icon-chevron-down'></i>
			</button>
		</div>

		<div id='achievements' class='row'>
			<div class='span8'>
			@include('game.achievements')
			</div>
		</div>

		<div id='achievements_tank' class='hidden'>

		</div>
	</div>
</div>

@include('game.modals')

@endsection

@section('css')
<link href='/css/thirdparty/bootstrap-wizard.css' rel='stylesheet' type='text/css'>
@endsection

@section('javascript')
<script src='/js/jquery/bootstrap-wizard.js' type='text/javascript'></script>
<script>
	var game_id = {{ $game->id }};
</script>
<script src='/js/game.js' type='text/javascript'></script>
@endsection