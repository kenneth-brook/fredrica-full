<div class = "row">
@foreach($recent as $r)
    <?php 
     /*if $previewImage is string imported from the old site use the old iamge name else use A3 media object*/
    $previewImage = '';
    if(strpos($r->imgs, '.jpg') !== false || strpos($r->imgs, '.png') !== false || strpos($r->imgs, '.pdf') !== false || strpos($r->imgs, '.gif')) {            
        $previewImage = '/resources/media/user/'.$r->imgs;
    }
    elseif ($r->imgs != ""){
            $previewImage = MediaTools::getImage(intval($r->imgs));
            if ($previewImage) {

                $previewImage = $previewImage->thumbnail;
                
            }
    } ?>
     <div class="col col-lg-4 col-12" data-aos="fade-up" data-aos-once="true" data-aos-delay = "">
          <a href="{{ url($anchor_url."/". $r->url) }}">
            <div>
              <div class="image-post">
                @if ($previewImage != '' )
                    <img class = "lazy" data-src="/{{ $previewImage }}" alt="{{ $r->title }}">
                @else
                    <img class = "lazy" data-src="/resources/themes/frederica2019/assets/images/news.jpg" alt="{{ $r->title }}">
                @endif  
                <span class="btn">Read more</span>
              </div>
              <div class="info-news">
                <p class="title">{{ $r->title }}</p>
                <!--<p class="content">{{ str_limit($r->blurb, $limit = 90, $end = '...') }} </p>-->
              </div>
            </div>
            <div class="overflow"></div>
            </span>
          </a>
        </div>       
@endforeach
</div>
