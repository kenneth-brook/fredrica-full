@if(!is_null($list))
    <h1>Archives for: {{ strftime('%B %Y', strtotime("01-".$month)) }}</h1>
    @foreach ($list as $blog)
        <div>
            <a href="{{ $anchor_url . $blog->url }}">{{ $blog->title }}</a>
            <br />
            {{ strftime('%m/%d/%Y', strtotime($blog->posted_date)) }}
            <br />
            {{ $blog->blurb }}
        </div>
    @endforeach
@else
    <p>We are sorry but we can't find any archives for this period.</p>
@endif
