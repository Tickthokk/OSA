@if(empty($achievement->users))
<p>
	<em>None!</em>  Be the first!
</p>
@else
<ul>
	@foreach ($achievement->users as $user)
	<li>
		<a href = '/user/profile/{{ $user->id }}#{{ $user->username }}' title = 'View Profile'>{{ $user->username }}</a>
		<i class = 'icon-certificate' rel = 'tooltip' title = '{{ number_format($user->achievement_tally, 0, '.', ',') }} Achievements'></i>
		<i class = 'icon-time' rel = 'tooltip' title = 'Earned {{ DateFmt::Format('AGO[t]IF-FAR[d##my]', strtotime($user->pivot->created_at)) }}'></i>
	</li>
	@endforeach
</ul>
@endif