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

<div class="container-lg container">
      <span class="square blue"></span>
      <h2 class="entry-title text-center pl-7" data-aos="fade-up" data-aos-once="true">Keep up with the latest from Frederica Living</h2>
      <div class="row">
        @foreach ($oBlogs as $k => $blog)
        <div class="col col-lg-4 col-sm-6 col-12"  data-aos="fade-up" data-aos-once="true" data-aos-delay = "">
          <a href="{{ $single_url . '' . $blog->url }}" >
            <?php 
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
            } ?>
            <div>
              <div class="image-post">
                @if ($previewImage != '' )
                    <img class = "lazy" data-src="/{{ $previewImage }}" alt="{{ $blog->title }}">
                @else
                    <img src="/resources/themes/frederica2019/assets/images/news.png" alt="{{ $blog->title }}">
                @endif     
                <span class="btn">Read more</span>
              </div>
              <div class="info-news">
                <p class="title">{{ $blog->title }}</p>
                <!--<p class="content">{{ str_limit($blog->blurb, $limit = 90, $end = '...') }}</p> -->
              </div>
            </div>  
            <div class="overflow"></div>           
          </a>
        </div>   
         @endforeach     
      </div>
    </div>  

  {!!  $oBlogs->links('blog::pagination') !!}