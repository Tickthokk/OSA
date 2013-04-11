@layout('layouts.common')

@section('title') Games @endsection

@section('content')

<!-- Now Viewing -->
@include('games.now_viewing')

<!-- Filtration -->

<!-- Developers and Systems -->
@include('games.developers')

<!-- Game Letters -->
@include('games.letters')

<!-- Games -->
@if (empty($games))
<div class='alert alert-error'>
	<strong>No results returned</strong>
</div>
@else
@include('games.list')
@endif

@endsection