<?php 
function shorten_string($string, $wordsreturned)
{

$retval = $string;

$array = explode(" ", $string);
if (count($array)<=$wordsreturned)
{
    $retval = $string;
}
else
{
    array_splice($array, $wordsreturned);
    $retval = implode(" ", $array)." ...";
}
return $retval;
}

?>
<div class="blog-listing">
    @foreach ($oBlogs as $k => $blog)
	
    <?php 
        $links = explode("\n", $blog->description);
        /*if $previewImage is string imported from the old site use the old iamge name else use A3 media object*/
        $previewImage = '';
        if(strpos($blog->imgs, '.jpg') !== false || strpos($blog->imgs, '.png') !== false || strpos($blog->imgs, '.pdf') !== false || strpos($blog->imgs, '.gif')) {            
            $previewImage = '/resources/media/user/'.$blog->imgs;
        }
        elseif ($blog->imgs != ""){
                $previewImage = MediaTools::getImage(intval($blog->imgs));
    			if ($previewImage) {

                	$previewImage = $previewImage->thumbnail;
    				
    			}
        }
    ?>
    <article class="blog-entry contents">
        <div class="blog-entry-content">
            <h1>{{ $blog->title }}</h1>
            <small class="entry-date">{!! \Carbon\Carbon::createFromFormat('Y-m-d h:i:s',$blog->posted_date)->format('F d, Y') !!}<small>
                <p>                        
                    @if($blog->use_blurb == 0 && $blog->body != "" )
                    {{ shorten_string(($blog->body), $blog->body_words > 0 ? $blog->body_words : 22) }}
                    @else
                    {{ $blog->blurb }}
                    @endif
                    <a class="read-more clearfix" href="{{ $single_url . '' . $blog->url }}">Read More</a>
                </p>
                <ul class="links">
                    @if (!empty($links))
                    @foreach ($links as $l)
                    @if(strpos($l, '.pdf') !== false)
                    <li>
                        <a class="pdf" target="_blank" href="{{ $l }}">Download PDF</a>
                    </li>
                    @elseif(strpos($l, 'youtube.com') !== false || strpos($l, 'youtu.be') !== false)
                    <li>
                        <a class="video" target="_blank" href="{{ $l }}">Watch Video</a>
                    </li>
                    @elseif($l != "")
                    <li>
                        <a target="_blank" href="{{ $l }}" class="external">Visit External Site</a>
                    </li>
                    @endif
                    @endforeach
                    @endif
                </ul>
            </div>
            <aside class="blog-entry-photos">
                @if ($previewImage != '' )
                    <img src="{{ $previewImage }}" alt="{{ $blog->title }}">
                @endif
            </aside>
        </article>
        @endforeach
        {{-- use just $oBlogs for old A3 installs --}}
        {!!  $oBlogs !!}
    </div>