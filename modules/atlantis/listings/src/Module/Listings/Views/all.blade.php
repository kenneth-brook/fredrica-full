	<ul id="listings" class="listings">
		@foreach($listings as $listing)
		<li>			
		<a href="/st-simons-homes-for-sale/{{ $listing['url'] }}">
			<?php 

			$imgs = '';
			if ($listing->images != '' ) {
				$imgs = explode(',' , $listing->images);
			}
			?>
			@if ($imgs != '')
			@if (!is_null(MediaTools::getImage($imgs[0])))

			<?php $featured = MediaTools::getImage($imgs[0]); ?> 

			<img src="{{ $featured->thumbnail }}">

			@else

			<img src="/resources/media/a2users/{{ $imgs[0] }}">

			@endif
			@endif

			<h3 class="name">{{ $listing['title'] }}</h3>
			<p class="price">{{ $listing['price'] }}</p>
		</a>
		</li>
	</li>
	@endforeach
</ul>

{{ $listings }}