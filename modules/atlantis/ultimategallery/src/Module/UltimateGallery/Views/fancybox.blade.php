{!! \Atlantis\Helpers\Assets::registerScript('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/fancybox/jquery.fancybox.pack.js', 10); !!}
{!! \Atlantis\Helpers\Assets::registerScript('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/fancybox/helpers/jquery.fancybox-buttons.js', 11); !!}
{!! \Atlantis\Helpers\Assets::registerScript('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/fancybox/helpers/jquery.fancybox-media.js', 12); !!}
{!! \Atlantis\Helpers\Assets::registerScript('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/fancybox/helpers/jquery.fancybox-thumbs.js', 13); !!}



{!! \Atlantis\Helpers\Assets::registerStyle('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/fancybox/jquery.fancybox.css', 10); !!}
{!! \Atlantis\Helpers\Assets::registerStyle('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/fancybox/helpers/jquery.fancybox-buttons.css', 11); !!}
{!! \Atlantis\Helpers\Assets::registerStyle('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/fancybox/helpers/jquery.fancybox-thumbs.css', 12); !!}


<h2>{{ $gallery->name }}</h2>
<p>{{ $gallery->description }}</p>
<p>view: {{ $gallery->view }} </p>
<div id="{{ $instance }}">
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
		$('#{{ $instance }} a').fancybox({{ $gallery->options }});
	}, false);
</script>

<style type="text/css">

{{ $gallery->css or ''}}
</style>
