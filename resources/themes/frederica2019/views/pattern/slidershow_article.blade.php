@if (isset($gallery_id))
<?php $images = \MediaTools::getImagesByGallery($gallery_id);
  $rel = uniqid('', true);
?>
@endif
<div class="post-title container">
    <h1 class="text-center" data-aos="fade-up" data-aos-once="true">{{$title}}</h1>

    <div class="text-center intro" data-aos="fade-up" data-aos-delay="50" data-aos-once="true">
        <p>{{$content}}</p>
    </div>
</div>

<div class="post-slider">
    <div class="slider-images container">
        <div class="swiper-container" data-aos="zoom-in-up" data-aos-once="true">
            <div class="swiper-wrapper">
                 @if(isset($images))
			         @foreach ($images as $key => $image)
                     @if(isset($image->tablet_name) && $image->tablet_name != '')
			            <div class="swiper-slide" style="background:url('{!! $image->tablet_name !!}')"></div>
                     @else
                     <div class="swiper-slide" style="background:url('{!! $image->original_filename !!}');background-size:cover;"></div>
                     @endif
			        @endforeach
			    @endif
            </div>
        </div>

        <div class="slide-nav">
            <div class="swiper-button-prev">&nbsp;</div>

            <div class="swiper-button-next">&nbsp;</div>
        </div>
    </div>
</div>
<?=$text; ?> 