@foreach($recent as $r)
    <li>
        <a href="{{ url($anchor_url."/". $r->url) }}">{{ $r->title }}</a>
    </li>
@endforeach
