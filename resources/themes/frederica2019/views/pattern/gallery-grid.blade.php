{!!$text!!}
@if (isset($gallery_id))
<?php $images = \MediaTools::getImagesByGallery($gallery_id);
	$rel = uniqid('', true);
?>
  <div class="container">
      @if (isset($title))
      <h2 class="text-center entry-title" data-aos="fade-in">{!!$title!!}</h2>
      @endif
      @if (isset($blurb))
      <p class="text-center pd-b-50" data-aos="fade-in">{!!$blurb!!}</p>
       @endif
      <div class="inner-grid row">
        <?php $bool = true; ?>
        @for ($i = 0; $i <= count($images)-2; $i=$i+2) 
          <?php $j = $i; ?>
            @if($bool)
          <div class="col col-sm-6 col-12 col-lg-8" data-aos="fade-up" data-aos-once="true"><a href="{!! $images[$j]->original_filename !!}" data-lightbox="grid">
            <div class="image-background lazy" data-src="{!!$images[$j]->tablet_name!!}"></div>
            @if(isset($images[$j]->anchor_link) && ($images[$j]->caption != ''))
            <div class="title-background" data-href = "{{$images[$j]->anchor_link}}">{{ $images[$j]->caption }}</div>
            @elseif( $images[$j]->caption != '')
           
            <div class="title-background">{{ $images[$j]->caption }}</div>
            @endif
          </a></div>
        <div class="col col-sm-6 col-12 col-lg-4" data-aos="fade-up" data-aos-delay="50" data-aos-once="true"><a href="{!! $images[$j+1]->original_filename !!}" data-lightbox="grid">
            <div class="image-background lazy" data-src="{!!$images[$j+1]->tablet_name!!}"></div>
            @if(isset($images[$j+1]->anchor_link) && ($images[$j+1]->caption != ''))
            <div class="title-background" data-href = "{{$images[$j+1]->anchor_link}}">{{ $images[$j+1]->caption }}</div>
             @elseif( $images[$j+1]->caption != '')           
            <div class="title-background">{{ $images[$j+1]->caption }}</div>
            @endif
          </a></div>  
          <?php $bool = false; ?>   
        
        @else
        <div class="col col-sm-6 col-12 col-lg-4" data-aos="fade-up" data-aos-once="true"><a href="{!! $images[$j]->original_filename !!}" data-lightbox="grid">
         <div class="image-background lazy" data-src="{!!$images[$j]->tablet_name!!}"></div>
            @if(isset($images[$j]->anchor_link) && ($images[$j]->caption != ''))
            <div class="title-background" data-href = "{{$images[$j]->anchor_link}}">{{ $images[$j]->caption }}</div>
            @elseif( $images[$j]->caption != '')            
            <div class="title-background">{{ $images[$j]->caption }}</div>
            @endif
          </a></div>      
        <div class="col col-sm-6 col-12 col-lg-8" data-aos="fade-up" data-aos-delay="50" data-aos-once="true"><a href="{!!$images[$j+1]->original_filename!!}" data-lightbox="grid">
            <div class="image-background lazy" data-src="{!!$images[$j+1]->tablet_name!!}"></div>
            @if(isset($images[$j+1]->anchor_link) && ($images[$j+1]->caption != ''))
            <div class="title-background" data-href = "{{$images[$j+1]->anchor_link}}">{{ $images[$j+1]->caption }}</div>
            @elseif( $images[$j+1]->caption != '')          
            <div class="title-background">{{ $images[$j+1]->caption }}</div>
            @endif
        </a></div>
        <?php $bool = true; ?>   
      
      @endif
        @endfor

       </div>
       @if (isset($CTA_text))
      <div class="button-center pd-top-50" data-aos="fade-up" data-aos-once="true"><a href="{!!$CTA_link!!}" class="btn btn-blue">{!!$CTA_text!!}</a></div>
      @endif
       @if (isset($blockquote))
      <blockquote class="text-center pd-top-50" data-aos="zoom-out-up" data-aos-once="true">“{!!$blockquote!!}”</blockquote>
       @endif
    </div>
  </section>
  @endif