@layout('layouts.common')

@section('title') Create Achievement @endsection

@section('content')

<div class='page-header'>
	<h1>Create Achievement for {{ $game->name }}</h1>
</div>

{{ Form::open('/achievement/create/' . $game->id, 'POST', array('class' => 'form-horizontal hide-on-icon-chooser'))}}
	<fieldset>

		<div class='control-group'>
			<label class='control-label' for='name'>
				Name
			</label>
			<div class='controls'>
				<input type='text' name='name' id='name' class='input-xxlarge' placeholder='Beat the Game' value='{{ $old_values['name'] }}'>
			</div>
		</div>

		<div class='control-group'>
			<label class='control-label' for='description'>
				How To Achieve
			</label>
			<div class='controls'>
				<textarea name='description' id='description' rows='5' class='input-xxlarge' placeholder='Defeat the last boss'>{{ $old_values['description'] }}</textarea>
				<p class="help-block markdown-container">
					<em>We use a restricted version of 
					<span class='btn btn-small' id='markdown_popup' data-trigger='click' data-placement='left' data-original-title='Restricted Markdown Example'>
						<i class='icon-align-right'></i>
						Markdown
					</span>.</em>
				</p>
				@include('markdown.example')
			</div>
		</div>

		<div class='control-group' id='system-exclusive-box'>
			<label class='control-label'>
				System Exclusive
			</label>
			<div class='controls'>
				<div class='row-fluid'>
					<div class='span1'>
						<label class='radio' rel='tooltip' title='No, this game is not exclusive to one system or version.'>
							<input type='radio' name='system-exclusive' value='all'@if(in_array($old_values['system-exclusive'], array('', 'all'))) checked='checked'@endif>
							No
						</label>
					</div>
					@foreach($game->systems as $system)
					<div class='span1'>
						<label class='radio' rel='tooltip' title='<u>{{ ucwords($system->developer->slug) }}</u><br>{{ $system->name }}'>
							<input type='radio' name='system-exclusive' value='{{ $system->id }}'@if($old_values['system-exclusive'] == $system->id) checked='checked'@endif>
							{{ strtoupper($system->slug) }}
						</label>
					</div>
					@endforeach
				</div>
			</div>
		</div>

		<hr>

		<div class='control-group'>
			<div class='control-label'>
				Select an Icon
				<div class='well' style='margin-top: 10px;'>
					Like the icons?<br>
					You can thank<br>
					<a href='http://game-icons.net/'>Game-icons.net</a>!
				</div>
			</div>
			<div class='controls'>
				<div class='row-fluid'>
					<div class='span3'>
						<em>&nbsp;</em>
						<div style='line-height:0;'><img src='/img/icons/{{ $old_values['icon'] ?: 'ace' }}.svg' class='svg' id='main-icon' data-color='{{ $old_values['icon-color'] }}' data-bg='{{ $old_values['icon-bg'] }}'></div>
						<br>
						<span class='btn btn-primary' id='select_icon'>Choose Icon</span>
						<input type='hidden' name='icon' value='{{ $old_values['icon'] ?: 'ace' }}' id='icon'>
					</div>
					<div class='span3'>
						<em>Color</em>
						<input type='hidden' name='icon-color' id='icon-color' class='colorpicker' value='{{ $old_values['icon-color'] }}'>
						<br>
						<span class='btn clear-color'>Reset Color</span>
					</div>
					<div class='span3'>
						<em>Background</em>
						<input type='hidden' name='icon-bg' id='icon-bg' class='colorpicker' value='{{ $old_values['icon-bg'] }}'>
						<br>
						<span class='btn clear-bg'>Reset Background</span>
					</div>
				</div>
			</div>
		</div>

		<hr>

		<div class='control-group'>
			<div class='control-label'>
				Tags
			</div>
			<div class='controls'>
				<ul id='tag-chooser'>
					@foreach((array) $old_values['item']['tags'] as $tag)
					<li>{{ $tag }}</li>
					@endforeach
				</ul>

				<strong>Suggestions:</strong>
				<ul class='tag-suggestions tagit ui-widget ui-widget-content ui-corner-all'>
					@foreach($default_tags as $tag)
					<li class='tag tagit-choice ui-widget-content ui-state-default ui-corner-all' data-tag='{{ htmlentities($tag->name, ENT_QUOTES) }}'><span class='tagit-label'>{{ $tag->name }}</span></li>
					@endforeach
				</ul>
			</div>
		</div>

		<hr>

		<div class='controls'>
			<button class='btn btn-success btn-large'>
				Create Achievement
			</button>
		</div>
	</fieldset>
{{ Form::close() }}
		
<div class='well hidden' id='icon-chooser'>
	<button type="button" class="close" data-dismiss="well">&times;</button>
	<h3>Icon Chooser</h3>

	<span class='btn btn-info flr' id='random-icon-search'>Random Icons</span>
	<span class='btn flr' id='clear-icon-search' style='margin-right: 10px;'>Clear Search</span>
	
	<div class='input-append'>
		<input type='text' id='icon-search' class='input-xxlarge' placeholder='Search...'>
		<span class='btn btn-success'><i class='icon-search'></i></span>
	</div>

	<div id='icon-tags' class='icon_tags' style='margin-top: 10px;'>
		@foreach($unique_icon_tags as $tag)
		<span class='icon_tag' data-id='{{ $tag->id }}' data-name='{{ htmlentities($tag->name, ENT_QUOTES) }}'>
			{{ $tag->name }} ({{ $tag->icons}})
		</span>
		@endforeach
	</div>
	<div id='icons-found' style='margin-top: 10px;' class='hidden'></div>
</div>

@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/ui-lightness/jquery-ui.css">
<link href='/css/thirdparty/evol.colorpicker.css' rel='stylesheet' type='text/css'>
<link href='/css/thirdparty/jquery.tagit.css' rel='stylesheet' type='text/css'>
<style>
	.evo-colorind { float: none; border-radius: 64px; }
	.svg, svg, .evo-colorind { width: 128px; height: 128px; }
	.found-icon { cursor: pointer; width: 88px; height: 88px; }
	.tag-suggestions li { cursor: pointer; }
	ul.tagit.tag-suggestions li.tagit-choice { padding-right: .5em; }
</style>
@endsection

@section('javascript')
<script src='/js/jquery/evol.colorpicker.custom.min.js' type='text/javascript'></script>
<script src='/js/jquery/tag-it.js' type='text/javascript'></script>
<script>
	var game_id = {{ $game->id }};
	var icon_list = {{ json_encode($icons) }};
</script>
<script src='/js/create.js' type='text/javascript'></script>
@endsection