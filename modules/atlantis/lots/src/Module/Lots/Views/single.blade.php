<?php 

$imgs = '';
if (!is_null($oLot->imgs) ) {

	$imgs = explode(',' , $oLot->imgs);
	$featured = MediaTools::getImage($imgs[0]);
}
?>


<div id="listing">
	<h1>Home Site#{{ $oLot->lot_number . $oLot->lot_number_ext }}</h1>
	<div id="photo">
		@if (!is_null($featured) )
			<img src="{{ $featured->original_filename }}">
		@endif

		<!-- VIDEO -->
		@include('lots::embed_video', ['video_link' => $oLot->video])
		
		@if (!is_null($oLot->sitemap))  
		<div class="box">
			@if (!is_null(MediaTools::getImage($oLot->sitemap)))
			<?php $sitemap = MediaTools::getImage($oLot->sitemap); ?>
			<a href="{{ $sitemap->original_filename }}" target="_blank">View sitemap</a>
			@else
				<!-- <a href="/resources/media/a2users/{{ $oLot->sitemap }}" target="_blank">View sitemap</a> -->
			@endif
		</div>
		@endif
		<div class="box">
			<a href="/contact#mainColumn">Request more information</a>
		</div>
	</div>

	@if (!empty($oLot->description))
	<div id="description">
		<h2>Home Site Description</h2>
		<div>{{ $oLot->description }}</div>
	</div>
	@endif
	<br class="clear">
	<h2>Featured Photography</h2>
	<ul id="photos">

		@if ($imgs != '')
			@foreach ($imgs as $k => $img)
			<?php $img = MediaTools::getImage($img); ?>
				@if (!is_null($img))
				<li>
					<a class="swipebox" href="{{ $img->original_filename }}"><img src="{{ $img->thumbnail }}"></a>
				</li>
				@endif

			@endforeach
		@endif
	</ul>
	<br class="clear">
	<h2>Listing Details</h2>
	<table>
		@if (!empty($oLot->price))
		<tr>
			<th>Price:</th><td>{{ str_replace('$', "&#36;", $oLot->price) }}</td>
		</tr>
		@endif

		@if (!empty($oLot->view_orientation))
		<tr>
			<th>View Orientation:</th><td>{{ $oLot->view_orientation }}</td>
		</tr>
		@endif

		@if (!empty($oLot->acreage))
		<tr>
			<th>Acreage:</th><td>{{ $oLot->acreage }}</td>
		</tr>
		@endif

		@if (!empty($oLot->frontage))
		<tr>
			<th>Frontage:</th><td>{{ $oLot->frontage }}</td>
		</tr>
		@endif

	</table>
</div> 