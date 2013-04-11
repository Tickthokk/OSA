@layout('admin::layouts.admin')

@section('title') Icons - Admin Panel @endsection

@section('content')

<form action='/admin/icons' method='get' class='flr admin_search' autocomplete='off'>
	<div class='input-append'>
		<input type='text' placeholder='Search' name='search' id='search' value='{{ $search }}' data-provide='typeahead'>
		<button class='btn btn-success'><i class='icon-search'></i></button>
	</div>
</form>

<h1>Icons</h1>

<table class='table table-striped table-bordered table-hover'>
	<thead>
		<tr>
			<?php
				$columns = array(
					'id' => 'ID',
					'filename' => 'Filename',
					9 => 'Tags' // No sorting, so key is numeric
				);
			?>
			@foreach($columns as $key => $value)
			<th data-column='{{ $key }}'>
				@unless(is_numeric($key))
				<a href='/admin/icons?sort={{ $key }}&amp;sort_dir=@if($sort == $key AND $sort_dir == 'asc')desc@elseasc@endif'>
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
		@foreach($icons as $icon)
		<tr data-id='{{ $icon->id }}'>
			<td>
				{{ $icon->id }}
			</td>
			<td>
				{{ $icon->filename }}
			</td>
			<td>
				@foreach($icon->tags as $tag)
				{{ $tag->name }}, 
				@endforeach
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

{{ $pagination }}

@endsection