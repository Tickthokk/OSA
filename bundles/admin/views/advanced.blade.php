@layout('admin::layouts.admin')

@section('title') Advanced - Admin Panel @endsection

@section('content')

<h1>Advanced</h1>

<ul>
	<li>
		<a href = '/admin/advanced/fix_games_tally'>
			<i class = 'icon-film'></i>
			Fix All Games Achievement Tally
		</a>
	</li>
	<li>
		<a href = '/admin/advanced/fix_users_tally'>
			<i class = 'icon-user'></i>
			Fix All Users Achievement Tally
		</a>
	</li>
	<!-- <li>
		<a href = '/admin/advanced/clean_cache'>
			<i class = 'icon-refresh'></i>
			Clear Cache
		</a>
	</li> -->
	<li>
		<a href = '/admin/icons/reassess'>
			<i class = 'icon-folder-open'></i>
			Reassess Icons on File
		</a>
	</li>
	<li>
		<a href = '/admin/icons/tag_grabber' style='display:inline-block; margin-right: 10px;'>
			<i class = 'icon-heart'></i>
			Re-Grab Tags for Icons
		</a>
		<strong><small>WARNING - THIS CURL'S AN OUTSIDE DOMAIN AT LEAST 1000 TIMES</small></strong>
	</li>
</ul>

@endsection