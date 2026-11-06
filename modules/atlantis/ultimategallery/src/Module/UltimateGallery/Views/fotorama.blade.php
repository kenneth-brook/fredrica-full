{!! \Atlantis\Helpers\Assets::registerScript('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/fotorama/fotorama.js', 10); !!}



{!! \Atlantis\Helpers\Assets::registerStyle('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/fotorama/fotorama.css', 10); !!}


<h2>{{ $gallery->name }}</h2>
<p>{{ $gallery->description }}</p>
<p>view: {{ $gallery->view }} </p>
<div id="{{ $instance }}" class="fotorama">
	@if(count($images))
	@foreach($images as $image)
		<a title="{{ $image->caption }}" rel="{{ $instance }}" href="/{{ $image->original_filename }}">
			<img {{ $image->css ? 'style="'.$image->css.'"' : '' }}  alt="{{ $image->alt }}" src="/{{ $image->thumbnail }}">
		</a>
	@endforeach
	@else
	<h3>No images</h3>
	@endif
</div>

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function(){ 
		$('#{{ $instance }}').fotorama({{ $gallery->options }});
	}, false);
</script>

<style type="text/css">

{{ $gallery->css or ''}}
</style>
