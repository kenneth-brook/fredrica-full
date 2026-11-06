@if (isset($gallery_id))
<?php $images = \MediaTools::getImagesByGallery($gallery_id);
  $rel = uniqid('', true);
?>
@endif

<section class="slider-section">
    <div class="swiper-container">
      <div class="swiper-wrapper"> 
        <?=$text; ?> 
        @if(isset($images))
         @foreach ($images as $key => $image)
        <div class="swiper-slide" style="background:url('{!! $image->tablet_name !!}');background-position: center center;">
          <div>
            @if(isset($title))
            <div class="slide-title" data-aos="fade-up"><?=$title; ?></div>    
            @endif      
          </div>
        </div>
        @endforeach
        @endif;
      </div>
      <div class="slide-nav">
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
      <div class="icon-arrow"></div>
    </div>
</section>