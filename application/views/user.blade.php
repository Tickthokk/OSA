@layout('layouts.common')

@section('title') {{ $user->username }} @endsection

@section('content')

<script type = 'text/javascript'>
	var user_id = parseInt({{ $user->id }});
</script>

@if (Auth::check() AND Auth::user()->id == $user->id)
<a href = '/auth/logout' class = 'flr btn btn-primary'>Logout</a>
@endif
<h1>{{ $user->username }}</h1>

@endsection