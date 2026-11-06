{!! \Atlantis\Helpers\Assets::registerScript('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/galleria/galleria-1.5.7.min.js', 20); !!}

{!! \Atlantis\Helpers\Assets::registerScript('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/galleria/plugins/flickr/galleria.flickr.min.js', 21); !!}

{!! \Atlantis\Helpers\Assets::registerStyle('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/galleria/themes/classic/galleria.classic.css', 21); !!}

<h2>{{ $gallery->name }}</h2>
<p>{{ $gallery->description }}</p>
<p>view: {{ $gallery->view }} </p>
<div id="{{ $instance }}" class="galleria">
	@if(count($images))
	@foreach($images as $image)
			
			@if ($image->anchor_link && (strpos($image->anchor_link, 'youtube.com') || strpos($image->anchor_link, 'vimeo.com') )  )
			
			<a href="{{ $image->anchor_link }}">
			
			@else
			
			<a href="{{ $image->original_filename }}">
			
			@endif
			
			<img {{ $image->css ? 'style="'.$image->css.'"' : '' }}
				alt="{{ $image->alt }}"
				src="/{{ $image->thumbnail }}"
				data-title="{{ $image->caption }}"
				data-description="{{ $image->description }}">
			</a>
	@endforeach
	@else
	<h3>No images</h3>
	@endif
</div>

<script>
	document.addEventListener('DOMContentLoaded', function(){ 
		Galleria.loadTheme('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/galleria/themes/classic/galleria.classic.min.js');
		Galleria.run('#{{ $instance }}');


		@if($gallery->options == '')

		Galleria.configure({
			imageCrop: true,
			transition: 'fade'
		});

		@else

			Galleria.configure({{ $gallery->options }});
			
		@endif

	});
</script>

<style type="text/css">
	#{{ $instance }}{height:467px}
	{{ $gallery->css or ''}}
</style>
