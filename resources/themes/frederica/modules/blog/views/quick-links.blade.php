@if(count($prev))
    <a style="float:right;" href="/{{ $single_url  }}/{{ $prev->url }}">NEXT POST</a>
@endif

@if(count($next))
    <a style="float:left;" href="/{{ $single_url  }}/{{ $next->url }}">NEXT POST</a>
@endif