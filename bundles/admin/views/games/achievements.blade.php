@layout('admin::layouts.admin')

@section('title') Achievements - Games - Admin Panel @endsection

@section('content')

<h2>Achievements</h2>

<h3>{{ $game->name }}</h3>

<table class='table table-striped table-bordered table-hover'>
	<thead>
		<tr>
			<td>ID</td>
			<td>Name</td>
			<td>Created By</td>
			<td>Created<br>Updated</td>
			<td>Actions</td>
		</tr>
	</thead>
	<tbody>
		@foreach($game->achievements as $achievement)
		<tr data-id='{{ $achievement->id }}'>
			<td>
				{{ $achievement->id }}
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

@endsection

@section('css')
<style>
	.name { width: 320px; overflow: hidden; }
	.actions { width: 55px; }
</style>
@endsection

@section('javascript')
{{ HTML::script('js/admin/games/achievements.js') }}
@endsection