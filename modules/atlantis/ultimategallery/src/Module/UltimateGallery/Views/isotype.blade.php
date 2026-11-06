{!! \Atlantis\Helpers\Assets::registerScript('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/isotype/isotope.pkgd.min.js', 20); !!}
{!! \Atlantis\Helpers\Assets::registerScript('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/plugins/isotype/imagesloaded.pkgd.min.js', 21); !!}

<?php 
$allTags = [];
?>

<h2>{{ $gallery->name }}</h2>
<p>{{ $gallery->description }}</p>
<p>view: {{ $gallery->view }} </p>

@if(count($images))

	@foreach($images as $image)
		
		<?php $allTags = array_merge($allTags, $image->tags); ?>
	
	@endforeach
	<div class="filter-button-group">
		
			<a data-filter="*" class="button btn btn-outline-secondary" >All</a>
		@foreach(array_unique($allTags) as $tag)
			<a data-filter=".{{ $tag }}" class="button btn btn-outline-secondary" >{{ $tag }}</a>
		@endforeach

	</div>

@endif

<div id="{{ $instance }}" class="isotype-grid">

	@if(count($images))

	@foreach($images as $image)

	<div class="grid-item {{ implode(' ', $image->tags) }}">

		<img {{ $image->css ? 'style="'.$image->css.'"' : '' }}  alt="{{ $image->alt }}" src="/{{ $image->thumbnail }}">

	</div>

	@endforeach

	@else

	<h3>No images</h3>

	@endif
</div>

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function(){

		@if($gallery->options == '')


		var $grid = $('#{{ $instance }}').isotope({
			itemSelector: '.grid-item',
			layoutMode: 'masonry',
		});

		$grid.imagesLoaded().progress( function() {
			$grid.isotope('layout');
		});


		@else

		var $grid =  $('#{{ $instance }}').isotope({{ $gallery->options }});

		@endif

		$('.filter-button-group').on( 'click', 'a', function() {

			var filterValue = $(this).attr('data-filter');
			$grid.isotope({ filter: filterValue });

		});

	}, false);
</script>
<style type="text/css">
.grid-item { width: 20%; float: left; padding:10px}

.grid-item.width2 { width: 40%;}

{{ $gallery->css or ''}}
</style>
