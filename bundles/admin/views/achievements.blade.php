@layout('admin::layouts.admin')

@section('title') Achievements - Admin Panel @endsection

@section('content')

<form action='/admin/achievements' method='get' class='flr admin_search' autocomplete='off'>
	<div class='input-append'>
		<input type='text' placeholder='Search' name='search' id='search' value='{{ $search }}' data-provide='typeahead'>
		<button class='btn btn-success'><i class='icon-search'></i></button>
	</div>
</form>

<h1>Achievements</h1>

<table class='table table-striped table-bordered table-hover'>
	<thead>
		<tr>
			<?php
				$columns = array(
					'id' => 'ID',
					'game' => 'Game',
					'name' => 'Name',
					'user_id' => 'Created By',
					'created_at' => 'Created<br>Updated',
					9 => 'Actions' // No sorting, so key is numeric
				);
			?>
			@foreach($columns as $key => $value)
			<th data-column='{{ $key }}'>
				@unless(is_numeric($key))
				<a href='/admin/achievements?sort={{ $key }}&amp;sort_dir=@if($sort == $key AND $sort_dir == 'asc')desc@elseasc@endif'>
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
		@foreach($achievements as $achievement)
		<tr data-id='{{ $achievement->id }}'>
			<td>
				{{ $achievement->id }}
			</td>
			<td>
				{{ $achievement->game->name }}
			</td>
			<td>
				<div class='name'>{{ $achievement->name }}</div>
			</td>
			<td>
				@if($achievement->user)
				<a href='/user/{{ $achievement->user->username }}'>{{ $achievement->user->username }}</a>
				@endif
			</td>
			<td>
				<div>
					{{ DateFmt::Format('AGO[t]IF-FAR[M_ d#, y##]', strtotime($achievement->created_at)) }}
				</div>
				@if($achievement->created_at != $achievement->updated_at)
				<div>
					{{ DateFmt::Format('AGO[t]IF-FAR[M_ d#, y##]', strtotime($achievement->updated_at)) }}
				</div>
				@endif
			</td>
			<td class='actions'>
				<a href='/admin/achievements/edit/{{ $achievement->id }}' rel='tooltip' title='Edit Achievement'><i class='icon-pencil'></i></a>
				<a href='/admin/achievements/flags/{{ $achievement->id }}' title='{{ $achievement->flag_statistics->open }} Open Flags' rel='tooltip'>
					<i class='icon-flag @if($achievement->flag_statistics->open!=0)red@elseopaque@endif'></i>
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
	.name { width: 180px; overflow: hidden; }
</style>
@endsection

@section('javascript')
{{ HTML::script('js/admin/achievements.js') }}
<script>
	var search_typeahead = [
		'Special: Flagged'
	];
</script>
@endsection