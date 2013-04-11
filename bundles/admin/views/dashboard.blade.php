@layout('admin::layouts.admin')

@section('title') Dashboard - Admin Panel @endsection

@section('content')

<h1>Dashboard</h1>

<div class='row-fluid'>
	<div class='span2 well center'>
		<div>{{ User::count() }}</div>
		<small>Users</small>
	</div>
	<div class='span2 well center'>
		<div>{{ Game::count() }}</div>
		<small>Games</small>
	</div>
	<div class='span2 well center'>
		<div>{{ Link::count() }}</div>
		<small>Links</small>
	</div>
	<div class='span2 well center'>
		<div>{{ Achievement::count() }}</div>
		<small>Achievements</small>
	</div>
	<div class='span2 well center'>
		<div>{{ AchievementUser::count() }}</div>
		<small>Achievers</small>
	</div>
</div>

<div class='row-fluid attention'>
	<div class='span3 well center'>
		<div>{{ Flag::where('section', '=', 'g')->where('admin_id', 'IS', DB::raw('NULL'))->count() }}</div>
		<small><a href='/admin/game/flags'>Game Flags</a></small>
	</div>
	<div class='span3 well center'>
		<div>{{ Flag::where('section', '=', 'l')->where('admin_id', 'IS', DB::raw('NULL'))->count() }}</div>
		<small><a href='/admin/game/flags'>Link Flags</a></small>
	</div>
	<div class='span3 well center'>
		<div>{{ Flag::where('section', '=', 'a')->where('admin_id', 'IS', DB::raw('NULL'))->count() }}</div>
		<small><a href='/admin/game/flags'>Achievement Flags</a></small>
	</div>
</div>

@endsection

@section('css')
<style>
	small { font-size: .8em; color: #999; }
	.attention { color: #cc3333; }
	.attention a { color: #cc3333; font-weight: bold; }
</style>
@endsection