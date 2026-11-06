{!!$text!!}
@if (isset($gallery_id))
<?php $images = \MediaTools::getImagesByGallery($gallery_id);
	$rel = uniqid('', true);
?>
    @foreach ($images as $key => $image)
			  @if($key == 0)
			   
			    <a data-original-title="{{ $images[0]->caption }}" title="{{ $images[0]->caption }}" data-lightbox="swipebox" rel="{{ $rel }}" href="{{ $images[0]->original_filename }}">
			    @if(isset($images[0]->tablet_name) && $images[0]->phone_name != '')
				<div class="image-background lazy" data-src="/{!! $images[0]->phone_name !!}"></div>
				@else
				<div class="image-background lazy" data-src="/{!! $images[0]->original_filename !!}"></div>
				@endif
				<div class = "title-background">
					@if (isset($gallery_text))
						{!! $gallery_text !!}
					@endif
				</div></a> 
              @else
              <a class="swipebox d-none" rel="{{ $rel }}" href="{!! $image->original_filename !!}" data-lightbox="swipebox" title="{{ $image->caption }}"></a>
              @endif
			
	@endforeach

@endif