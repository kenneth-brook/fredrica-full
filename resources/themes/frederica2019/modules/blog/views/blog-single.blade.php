<?php 

//var_dump($data);
$links = explode("\n", $data->description);
/*if $previewImage is string imported from the old site use the old iamge name else use A3 media object*/
$previewImage = '';
if(strpos($data->imgs, '.jpg') !== false || strpos($data->imgs, '.png') !== false || strpos($data->imgs, '.pdf') !== false || strpos($data->imgs, '.gif')) {            
  $previewImage = '/resources/media/user/'.$data->imgs;
  $thumb = $previewImage;
}
elseif ($data->imgs != ""){
  $previewImage = MediaTools::getImage(intval($data->imgs));
  
  if(!is_null($previewImage)){

    $thumb = $previewImage->original_filename;
    $previewImage = $previewImage->original_filename;
    
  }
}
$gallery = '';
if(isset($data->gallery_id) && $data->gallery_id != ""){
  $gallery = MediaTools::getImagesByGallery($data->gallery_id);
}
?>


<section class="container-lg container">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>      
      <li class="breadcrumb-item"><a href="{{ url('/whats-new') }}">In the News</a></li>  
      <li class="breadcrumb-item active">
        {{$data->title}}
      </li>     
    </ol>
  </nav>
</section>
<section class="single-post">
    <div class="post-title container ">
      <span class="square blue"></span>
      <h1 class="text-center" data-aos="fade-up" data-aos-once="true">{{ $data->title }}</h1>
      <div class="text-center intro" data-aos="fade-up" data-aos-once="true" data-aos-delay = "50">
        <p>{{ $data->blurb }}</p>
      </div>
    </div>
    @if (isset($previewImage) && $previewImage != '' && isset($thumb))
    <div class="post-slider">
     @if(isset($gallery) && $gallery != '')         

      <div class="slider-images container">
        <div class="swiper-container" data-aos="zoom-in-up" data-aos-once="true">
          <div class="swiper-wrapper" >
              @foreach ($gallery  as $k => $img)
                <?php //var_dump($img); ?>
                
                    @if (!is_null($img)) 
                       @if (!is_null($img->tablet_name))
                       <a class="swiper-slide" style="background:url('/{{ $img->tablet_name }}')" href = "/{{ $img->original_filename }}" data-lightbox = "gallery"></a>
                       @else             
                        <a class="swiper-slide" style="background:url('/{{ $img->original_filename }}')" href = "/{{ $img->original_filename }}" data-lightbox = "gallery"></a>
                       @endif 
                    @endif
                @endforeach
         
          </div>
        </div> 
        <div class="slide-nav">
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>           
      </div>
       @else 
            <div class="swiper-slide-feature container" style = "z-index:1;margin-top:40px;">
              <img src="/{{ $thumb }}" alt="{{ $data->title }}" >
            </div>

   
    @endif
  </div>
      @endif

    <div class="content-post {{($data->blurb || $previewImage) ? '' : 'no-pt'}}" data-aos="zoom-in" data-aos-once="true">
     {{ $data->body }}
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
</section>