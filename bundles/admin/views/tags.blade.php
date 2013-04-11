@layout('admin::layouts.admin')

@section('title') Tags - Admin Panel @endsection

@section('content')

<form action='/admin/tags' method='get' class='flr admin_search' autocomplete='off'>
	<div class='input-append'>
		<input type='text' placeholder='Search' name='search' id='search' value='{{ $search }}' data-provide='typeahead'>
		<button class='btn btn-success'><i class='icon-search'></i></button>
	</div>
</form>

<h1>Tags</h1>

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
				<a href='/admin/tags?sort={{ $key }}&amp;sort_dir=@if($sort == $key AND $sort_dir == 'asc')desc@elseasc@endif'>
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
		@foreach($tags as $tag)
		<tr data-id='{{ $tag->id }}'>
			<td>
				{{ $tag->id }}
			</td>
			<td>
				{{ $tag->name }}
			</td>
			<td>
				
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

{{ $pagination }}

@endsection