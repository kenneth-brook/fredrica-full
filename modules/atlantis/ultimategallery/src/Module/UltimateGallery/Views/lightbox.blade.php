{!! \Atlantis\Helpers\Assets::registerScript('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/lightbox/js/lightboxgallery-min.js', 10); !!}

{!! \Atlantis\Helpers\Assets::registerStyle('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/lightbox/css/lightboxgallery-min.css', 10); !!}

<h2>{{ $gallery->name }}</h2>
<p>{{ $gallery->description }}</p>
<p>view: {{ $gallery->view }} </p>
<div id="{{ $instance }}">
	@if(count($images))
	@foreach($images as $image)
		<a data-lightbox="{{ $instance }}" data-alt="{{ $image->alt }}" class="lightboxgallery-gallery-item" target="_blank" data-title="{{ $image->caption }}" data-description="{{ $image->description }}"   href="/{{ $image->original_filename }}">
			
            <img {{ $image->css ? 'style="'.$image->css.'"' : '' }}  alt="{{ $image->alt }}" src="/{{ $image->thumbnail }}">
           
		</a>
	@endforeach
	@else
	<h3>No images</h3>
	@endif
</div>

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function(){ 

		$(document).on('click', '.lightboxgallery-gallery-item', function(event) {
			event.preventDefault();
			$(this).lightboxgallery({{ $gallery->options }});
		});

	}, false);
</script>

<style type="text/css">

{{ $gallery->css or ''}}
</style>
