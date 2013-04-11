@layout('admin::layouts.admin')

@section('title') Flags - Links - Admin Panel @endsection

@section('content')

<h2>Flags</h2>

<h3>{{ $link->site }} - {{ $link->game->name }}</h3>

<p>
	<a href='{{ $link->url }}' target='_blank'>{{ $link->url }}</a>
</p>

<table class='table table-striped table-bordered table-hover'>
	<thead>
		<tr>
			<th>
				Flag ID
			</th>
			<th>
				Flagger<br>
				IP Address
			</th>
			<th>
				Created<br>
				Updated
			</th>
			<th>
				Reason
			</th>
			<th>
				Actions
			</th>
		</tr>
	</thead>
	<tbody>
		@foreach($flags as $flag)
		<tr data-id='{{ $flag->id }}'>
			<td>{{ $flag->id }}</td>
			<td>
				<div>
					@if($flag->user)
					{{ $flag->user->username }}
					@endif
				</div>
				<div>
					{{ long2ip($flag->flagger_ip) }}
				</div>
			</td>
			<td>
				<div>
					{{ DateFmt::Format('AGO[t]IF-FAR[M_ d#, y##]', strtotime($flag->created_at)) }}
				</div>
				@if($flag->created_at != $flag->updated_at)
				<div>
					{{ DateFmt::Format('AGO[t]IF-FAR[M_ d#, y##]', strtotime($flag->updated_at)) }}
				</div>
				@endif
			</td>
			<td>
				@if($flag->admin_id)
				<strong>Resolved by {{ User::find($flag->admin_id)->username }}</strong>
				@endif
				<div>
					{{ $flag->reason }}
				</div>
			</td>
			<td class='actions'>
				<i class='icon-cog flag_control pointer' rel='tooltip' title='Mark Resolved'></i>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

<div class='modal hide fade' id='control_flag'>
	<div class='modal-header'>
		<button class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
		<h3>Control Flag</h3>
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
	.actions { width: 55px; }
</style>
@endsection

@section('javascript')
{{ HTML::script('js/admin/flags.js') }}
@endsection