@layout('admin::layouts.admin')

@section('title') Links - Games - Admin Panel @endsection

@section('content')

<h2>Links</h2>

<p class='label label-warning flr'>Safety first, open links with caution.</p>

<h3>{{ $game->name }}</h3>

<table class='table table-striped table-bordered table-hover'>
	<thead>
		<tr>
			<td>Link ID</td>
			<td>Site / URL</td>
			<td>Created By</td>
			<td>Created</td>
			<td>Updated</td>
			<td>Actions</td>
		</tr>
	</thead>
	<tbody>
		@foreach($game->links as $link)
		<tr data-id='{{ $link->id }}'>
			<td rowspan='2'>
				{{ $link->id }}
			</td>
			<td>
				{{ $link->site }}
			</td>
			<td>
				<a href='/user/{{ $link->user->username }}'>{{ $link->user->username }}</a>
			</td>
			<td>
				{{ DateFmt::Format('AGO[t]IF-FAR[M_ d#, y##]', strtotime($link->created_at)) }}
			</td>
			<td>
				@if($link->created_at != $link->updated_at)
				{{ DateFmt::Format('AGO[t]IF-FAR[M_ d#, y##]', strtotime($link->updated_at)) }}
				@endif
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
			<td colspan='5'>
				<a href='{{ $link->url }}' class='external-game-link' rel='tooltip' title='Open Link Externally' target='_blank'>{{ $link->url }}</a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>

@endsection

@section('css')
<style>
	.actions { width: 85px; }
</style>
@endsection

@section('javascript')
{{ HTML::script('js/admin/links/edit.js') }}
@endsection