@if(count($oBlogs))

<h1>Category: {!! strftime ('%B, %Y', strtotime($month . '/01/' . $year)) !!}</h1>


@foreach ($oBlogs as $blog)
<div class="entry">
    <h3>{{ $blog->title }}</h3>
    <p class="time-class">{!! \Carbon\Carbon::createFromFormat('Y-m-d h:i:s',$blog->posted_date)->format('F d, Y') !!}</p>
    <p>                        
        @if($blog->use_blurb == 0)
        {{ \Illuminate\Support\Str::words( strip_tags($blog->body), $blog->body_words, '...') }}
        @else
        {{ $blog->blurb }}
        @endif
    </p>
    <a href="{{ $single_url . '' . $blog->url }}">READ STORY</a>
    <br>
    <hr>
</div>
@endforeach

{{$oBlogs->links('blog::pagination')}}

@else
    <p>We are sorry but we can't find any posts for this category.</p>
@endif
{{-- use just $oBlogs for old A3 installs --}}