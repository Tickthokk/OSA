@layout('layouts.common')

@section('title') Achieve Anything @endsection

@section('content')
<div class='hero-unit'>
	<h1>Achieve Anything</h1>
</div>

<h2>Leaderboard</h2>
<table id='leaderboard' class='table table-bordered table-striped table-hover table-condensed'>
	<thead>
		<tr>
			<th>User</th>
			<th>Tally</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($leaderboard as $leader)
		<tr>
			<td><a href='/user/{{ $leader->username }}'>{{ $leader->username }}</a></td>
			<td>{{ $leader->achievement_tally }} Achievements</td>
		</tr>
		@endforeach
	</tbody>
</table>

<h2>Achievement Activity</h2>
<table id='achievement_activity' class='table table-bordered table-striped table-hover table-condensed'>
	<thead>
		<tr>
			<th>Game</th>
			<th>Achievement</th>
			<th>User</th>
			<th>When</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($achievements as $au)
		<tr>
			<td><a href='/game/{{ $au->achievement->game->slug }}#{{ $au->achievement->game->name }}'>{{ $au->achievement->game->name }}</a></td>
			<td><a href='/achievement/{{ $au->achievement->id }}'>{{ $au->achievement->name }}</a></td>
			<td><a href='/user/{{ $au->user->username }}'>{{ $au->user->username }}</a></td>
			<td>{{ DateFmt::Format('AGO[d.h]', strtotime($au->created_at)) }}</td>
		</tr>
		@endforeach
	</tbody>
</table>

@endsection