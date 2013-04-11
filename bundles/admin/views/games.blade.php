@layout('admin::layouts.admin')

@section('title') Games - Admin Panel @endsection

@section('content')

<form action='/admin/games' method='get' class='flr admin_search' autocomplete='off'>
	<div class='input-append'>
		<input type='text' placeholder='Search' name='search' id='search' value='{{ $search }}' data-provide='typeahead'>
		<button class='btn btn-success'><i class='icon-search'></i></button>
	</div>
</form>

<h1>Games</h1>

<table class='table table-striped table-bordered table-hover'>
	<thead>
		<tr>
			<?php
				$columns = array(
					'id' => 'ID',
					'name' => 'Name',
					9 => 'Actions' // No sorting, so key is numeric
				);
			?>
			@foreach($columns as $key => $value)
			<th data-column='{{ $key }}'>
				@unless(is_numeric($key))
				<a href='/admin/games?sort={{ $key }}&amp;sort_dir=@if($sort == $key AND $sort_dir == 'asc')desc@elseasc@endif'>
					<i class='icon-chevron-@if($sort == $key AND $sort_dir == 'desc')up@elsedown@endif flr'></i>
				@endunless
				{{ $value }}
				@unless(is_numeric($key))
				</a>
				@endunless
			</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach($games as $game)
		<tr data-id='{{ $game->id }}'>
			<td>{{ $game->id }}</td>
			<td><a href='/game/{{ $game->id }}#{{ $game->name }}'>{{ $game->name }}</a></td>
			<td class='actions'>
				<a href='/admin/games/flags/{{ $game->id }}' title='{{ $game->flag_statistics->open }} Open Flags' rel='tooltip'>
					<i class='icon-flag @if($game->flag_statistics->open!=0)red@elseopaque@endif'></i>
				</a>
				<a href='/admin/games/achievements/{{ $game->id }}' title='{{ count($game->achievements) }} Achievements' rel='tooltip'>
					<i class='icon-certificate'></i>
				</a>
				<a href='/admin/games/links/{{ $game->id }}' title='{{ count($game->links) }} Links' rel='tooltip'>
					<i class='icon-link'></i>
				</a>
				<a href='/admin/games/edit/{{ $game->id }}' title='Edit' rel='tooltip'>
					<i class='icon-pencil'></i>
				</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

{{ $pagination }}

@endsection

@section('css')
<style>
	.actions { width: 90px; }
</style>
@endsection

@section('javascript')
{{ HTML::script('js/admin/games.js') }}
<script>
	var search_typeahead = [
		'Special: Flagged'
	];
</script>
@endsection