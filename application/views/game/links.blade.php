@foreach ($game->links as $link)
<li class='@unless($link->admin_id)unapproved@endunless'>
	<i class='star icon-star@unless($link->admin_id)-empty' rel='tooltip' title='Warning: Link has not been approved by a Moderator@endunless'></i>
	<a href='{{ $link->url }}' class='external-game-link' data-id='{{ $link->id }}' data-flag-tally='{{ $link->flag_statistics->tally }}' data-flag-unique-users='{{ $link->flag_statistics->unique_users }}' data-flag-solved='{{ $link->flag_statistics->solved }}' data-flag-open='{{ $link->flag_statistics->open }}' target='_blank'>{{ $link->site }}</a>
	@if ($link->flagged)
	<i class='icon-thumbs-down' rel='tooltip' title='Link has been reported as bad'></i>
	@endif
</li>
@endforeach