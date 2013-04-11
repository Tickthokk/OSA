@layout('layouts.common')

@section('title') "{{ $search_term }}" results @endsection

@section('content')

<div class='alert alert-info'>
	<strong>Searched for</strong> {{ $search_term }}
</div>

<!-- Games -->
@if (empty($games))
<div class='alert alert-error'>
	<strong>No results returned, please revise your search.</strong>
</div>
@else
@include('games.list')
@endif

@endsection