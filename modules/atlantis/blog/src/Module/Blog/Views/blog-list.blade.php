@foreach ($oBlogs as $blog)
<div class="entry">
    <?php
        $previeImage =  MediaTools::getImage($blog->imgs);
    ?>
    @if (!is_null($previeImage))
        <img src="{{ $previeImage->thumbnail }}" alt="">
    @endif
    <h3>{{ $blog->title }}</h3>
    @if(!is_null($blog->posted_date))
        <p class="time-class">{!! \Carbon\Carbon::createFromFormat('Y-m-d h:i:s',$blog->posted_date)->format('F d, Y') !!}</p>
    @endif
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
{{-- use just $oBlogs for old A3 installs --}}
{{$oBlogs->links('blog::pagination')}}