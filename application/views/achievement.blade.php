@layout('layouts.common')

@section('title') {{ $achievement->name }} @endsection

@section('content')

<div id='achievement'>
	<div class='page-header'>
		@if ($achievement->system_exclusive)
		<div id='system-exclusive' class='label label-inverse'>
			<div>
				{{ strtoupper($achievement->system->slug) }}
				<div>Exclusive</div>
			</div>
		</div>
		@endif

		<h1><a href='/game/{{ $achievement->game->id }}'>{{ $achievement->game->name }}</a> - {{ $achievement->name }}</h1>
	</div>

	<div class='row'>
		<div class='span4'>
			<div class='center'>
				<img src='/game/image/{{ $achievement->game->id }}' />
			</div>

			<div class='well tags-wrapper'>
				<div id='tags'>
					@include('achievement.tags')
				</div>
				<span class='btn btn-small suggest-new-tag' style='display: block; width: 93%;'>
					<i class='icon-tag'></i>
					Suggest New Tag
				</span>
				<div id='suggest-tag' class='hide'>
					<h4>
						<i class='icon-tags'></i> Suggest New Tag
					</h4>
					<input type='text'>
					<span class='btn btn-success submit' title='Submit'><i class='icon-ok'></i></span>
					<span class='btn btn-danger cancel' title='Cancel'><i class='icon-remove'></i></span>
				</div>
			</div>

			<div class='well' style='margin-top: 10px;'>
				<h3>Achievers</h3>

				<div id='achievers'>
					@include('achievement.achievers')
				</div>
			</div>

			<div class='well' style='margin-top: 10px;'>
				<div class='btn-group'>
					<a class='btn dropdown-toggle' data-toggle='dropdown' href='#'>
						<i class='icon-cog'></i>
						Help keep OSA Tidy!
						<span class='caret'></span>
					</a>
					<ul class='dropdown-menu'>
						<li><a href='#' class='flag-as-inappropriate'><i class='icon-warning-sign'></i> Flag as Inappropriate</a></li>
					</ul>
				</div>
				@if(Auth::check() AND $achievement->user_id == Auth::user()->id)
				<div style='margin-top: 10px;'>
					<span class='btn btn-warning edit_achievement' data-toggle='modal' data-target='achievement_editing'>
						<i class='icon-pencil icon-white'></i>
						Edit
					</span>
					<span class='btn btn-danger delete_achievement' data-toggle='modal' data-target='achievement_deleting'>
						<i class='icon-trash icon-white'></i>
						Delete
					</span>
				</div>
				@endif
			</div>
		</div>
		<div class='span8'>
			<div class='well'>
				<img src='/img/icons/{{ $achievement->icon->filename }}.svg' class='icon svg' data-color='{{ str_pad(dechex($achievement->icon_color), 6, STR_PAD_LEFT) }}' data-bg='{{ str_pad(dechex($achievement->icon_background), 6, STR_PAD_LEFT) }}'>

				<div class='how_to_achieve'>
					<h2>How To Achieve:</h2>
					<div class='description markdown-container'>
						@if(empty($achievement->description))
						<p>
							<em>No Description</em>
						</p>
						@else
						<p>
							{{ $achievement->markdown_description }}
						</p>
						@endif
					</div>
					<p class='created-modified'>
						Created By:
						<a href='/user/profile/{{ $achievement->user_id }}#{{ $achievement->user->username }}' title='View Profile'>{{ $achievement->user->username }}</a>
						 {{ DateFmt::Format('AGO[t]IF-FAR[M_ d#, y##]', strtotime($achievement->created_at)) }}
						@if($achievement->created_at != $achievement->updated_at)
						<span>Modified: {{ DateFmt::Format('AGO[t]IF-FAR[M_ d#, y##]', strtotime($achievement->updated_at)) }}</span>
						@endif
					</p>
				</div>
			</div>

			<div class='well'>
				@unless($user_has_achieved)
				<a href='/achievement/achieve/{{ $achievement->id }}' class='btn btn-success btn-large' id='i-did-it' style='display:block;width:93%;'>
					<i class='icon-certificate icon-white'></i>
					I did it!
				</a>
				@endunless
				@if($user_has_achieved)
				<span class='i-did-it' id='you-did-it'>
					<i class='icon-star'></i>
					You completed this achievement {{ DateFmt::Format('AGO[t]IF-FAR[on M_ d#, y##]', strtotime($user_has_achieved->created_at)) }}!
				</span>
				@endif
			</div>
			<div class='well'>
				<div id="disqus_thread"></div>
				<script type="text/javascript">
					/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
					var disqus_shortname = 'oldschoolachievements'; // required: replace example with your forum shortname
					var disqus_identifier = 'a{{ $achievement->id }}';
					var disqus_developer = 1;

					/* * * DON'T EDIT BELOW THIS LINE * * */
					(function() {
						var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
						dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
						(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
					})();
				</script>
				<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
				<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
			</div>
		</div>
	</div>
</div>

@include('achievement.modals')

@endsection

@section('css')
<link href='/css/thirdparty/jquery.tagit.css' rel='stylesheet' type='text/css'>
<link href='/css/thirdparty/tagit.ui-zendesk.css' rel='stylesheet' type='text/css'>
@endsection

@section('javascript')
<script>
	var game_id = {{ $achievement->game->id }};
	var achievement_id = {{ $achievement->id }};
</script>
<script src='/js/jquery/tagcloud.min.js' type='text/javascript'></script>
<script src='/js/achievement.js' type='text/javascript'></script>
@endsection