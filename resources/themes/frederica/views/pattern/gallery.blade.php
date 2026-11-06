{!!$text!!}
@if (isset($gallery_id))
<?php $images = \MediaTools::getImagesByGallery($gallery_id);
	$rel = uniqid('', true);
?>
<div class="element">
	<div class="textElement">
		<div id="gallery">
				
			<a data-original-title="{{ $images[0]->caption }}" title="{{ $images[0]->caption }}" class="swipebox" rel="{{ $rel }}" href="{{ $images[0]->original_filename }}">
				<img alt="Frederica Fitness Center" src="{!! $images[0]->thumbnail !!}">
				<span style="font-size:14px;">
					@if (isset($gallery_text))
						{!! $gallery_text !!}
					@endif
				</span> </a> 
			
			@foreach ($images as $image)
			<a class="swipebox hide" rel="{{ $rel }}" href="{!! $image->original_filename !!}" title="{{ $image->caption }}"></a>
			@endforeach
		</div>
	</div>
</div>
@endif