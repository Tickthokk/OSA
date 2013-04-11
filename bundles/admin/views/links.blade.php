@layout('admin::layouts.admin')

@section('title') Links - Admin Panel @endsection

@section('content')

<form action='/admin/links' method='get' class='flr admin_search' autocomplete='off'>
	<div class='input-append'>
		<input type='text' placeholder='Search' name='search' id='search' value='{{ $search }}' data-provide='typeahead'>
		<button class='btn btn-success'><i class='icon-search'></i></button>
	</div>
</form>

<div class='flr' style='margin: 25px 30px 0 0;'>
	<label class='checkbox'><input type='checkbox' id='hide_urls'> Hide URLs</label>
</div>

<h1>Links</h1>

<table class='table table-striped table-bordered table-hover'>
	<thead>
		<tr>
			<?php
				$columns = array(
					'id' => 'ID',
					'game' => 'Game',
					'site' => 'Site / URL',
					'created_by' => 'Created By',
					'created_at' => 'Created',
					//'updated_at' => 'Updated',
					9 => 'Actions' // No sorting, so key is numeric
				);
			?>
			@foreach($columns as $key => $value)
			<th data-column='{{ $key }}'>
				@unless(is_numeric($key))
				<a href='/admin/links?sort={{ $key }}&amp;sort_dir=@if($sort == $key AND $sort_dir == 'asc')desc@elseasc@endif'>
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
		@foreach($links as $link)
		<tr data-id='{{ $link->id }}'>
			<td rowspan='2'>
				{{ $link->id }}
			</td>
			<td>
				{{ $link->game->name }}
			</td>
			<td>
				{{ $link->site }}
			</td>
			<td>
				<a href='/user/{{ $link->user->username }}'>{{ $link->user->username }}</a>
			</td>
			<td>
				{{ DateFmt::Format('AGO[t]IF-FAR[M_ d#, y##]', strtotime($link->created_at)) }}
			<!-- </td>
			<td> -->
				<!-- 
				<br>
				@if($link->created_at != $link->updated_at)
				{{ DateFmt::Format('AGO[t]IF-FAR[M_ d#, y##]', strtotime($link->updated_at)) }}
				@endif 
			-->
			</td>
			<td class='actions'>
				<a href='/admin/links/edit/{{ $link->id }}' rel='tooltip' title='Edit Link'><i class='icon-pencil'></i></a>
				<a href='/admin/links/flags/{{ $link->id }}' title='{{ $link->flag_statistics->open }} Open Flags' rel='tooltip'>
					<i class='icon-flag @if($link->flag_statistics->open!=0)red@elseopaque@endif'></i>
				</a>
				<span class='link link-delete' rel='tooltip' title='Delete Link'><i class='icon-trash red'></i></span>
				@if( ! $link->admin_id)
				<span class='link link-approve' rel='tooltip' title='Approve Link'><i class='icon-thumbs-up green'></i></span>
				@else
				<i class='icon-thumbs-up opaque' rel='tooltip' title='Approved by {{ User::find($link->admin_id)->username }}'></i>
				@endif
			</td>
		</tr>
		<tr>
			<td colspan='6' class='url'>
				<a href='{{ $link->url }}' class='external-game-link' rel='tooltip' title='Open Link Externally' target='_blank'>{{ $link->url }}</a>
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
{{ HTML::script('js/admin/links.js') }}
{{ HTML::script('js/admin/links/edit.js') }}
<script>
	var search_typeahead = [
		'Special: Flagged',
		'Special: Unapproved'
	];
</script>
@endsection