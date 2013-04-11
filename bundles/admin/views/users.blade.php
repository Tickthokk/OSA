@layout('admin::layouts.admin')

@section('title') Users - Admin Panel @endsection

@section('content')

<form action='/admin/users' method='get' class='flr admin_search' autocomplete='off'>
	<div class='input-append'>
		<input type='text' placeholder='Search' name='search' id='search' value='{{ $search }}' data-provide='typeahead'>
		<button class='btn btn-success'><i class='icon-search'></i></button>
	</div>
</form>

<h1>Users</h1>

<table class='table table-striped table-bordered table-hover'>
	<thead>
		<tr>
			<?php
				$columns = array(
					'id' => 'ID',
					'username' => 'Username',
					'last_login' => 'Last Login',
					9 => 'Actions' // No sorting, so key is numeric
				);
			?>
			@foreach($columns as $key => $value)
			<th data-column='{{ $key }}'>
				@unless(is_numeric($key))
				<a href='/admin/users?sort={{ $key }}&amp;sort_dir=@if($sort == $key AND $sort_dir == 'asc')desc@elseasc@endif'>
				@endunless
				{{ $value }}
				@unless(is_numeric($key))
					<i class='icon-chevron-@if($sort == $key AND $sort_dir == 'desc')up@elsedown@endif flr'></i>
				</a>
				@endunless
			</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach($users as $user)
		<tr data-id='{{ $user->id }}'>
			<td>{{ $user->id }}</td>
			<td><a href='/user/{{ $user->username }}'>{{ $user->username }}</a></td>
			<td>{{ $user->last_login }}</td>
			<td class='actions'>

				<i class='user_control icon-cog pointer' title='Control User' rel='tooltip'></i>

				@if($user->banned)
				<i class='icon-flag red' title='Banned' rel='tooltip'></i>
				@endif
				@unless($user->activated)
				<i class='icon-user' title='Not Activated' rel='tooltip'></i>
				@endunless
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

{{ $pagination }}

<div class='modal hide fade' id='control_user'>
	<div class='modal-header'>
		<button class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
		<h3>Control User</h3>
	</div>
	<div class='modal-body'></div>
	<div class='modal-footer'>
		<button class='btn' data-dismiss='modal'>Close</a>
		<button class='btn btn-primary'>Save Changes</button>
	</div>
</div>

@endsection

@section('css')
<style>
	.actions { width: 90px; }
</style>
@endsection

@section('javascript')
{{ HTML::script('js/admin/users.js') }}
<script>
	var search_typeahead = [
		'Special: Banned',
		'Special: Inactive'
	];
</script>
@endsection