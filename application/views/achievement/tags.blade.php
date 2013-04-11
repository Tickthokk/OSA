@foreach ($achievement->tags as $tag)
<span data-id = '{{ $tag->id }}' data-name = '{{ strtolower($tag->name) }}'>{{ strtolower($tag->name) }}</span>
@endforeach