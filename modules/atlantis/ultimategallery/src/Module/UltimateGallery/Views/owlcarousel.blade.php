{!! \Atlantis\Helpers\Assets::registerScript('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/owlcarousel/owl.carousel.min.js', 10); !!}



{!! \Atlantis\Helpers\Assets::registerStyle('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/owlcarousel/assets/owl.carousel.min.css', 10); !!}
{!! \Atlantis\Helpers\Assets::registerStyle('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/owlcarousel/assets/owl.theme.default.min.css', 11); !!}


<h2>{{ $gallery->name }}</h2>
<p>{{ $gallery->description }}</p>
<p>view: {{ $gallery->view }} </p>
<div id="{{ $instance }}" class="owl-theme owl-carousel">
	@if(count($images))
	@foreach($images as $image)
		
			<img {{ $image->css ? 'style="'.$image->css.'"' : '' }}  alt="{{ $image->alt }}" src="/{{ $image->thumbnail }}">
	@endforeach
	@else
	<h3>No images</h3>
	@endif
</div>

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function(){ 
		$('#{{ $instance }}').owlCarousel({{ $gallery->options }});
	}, false);
</script>

<style type="text/css">

{{ $gallery->css or ''}}
</style>
