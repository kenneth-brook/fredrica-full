	<ul id="listings" class="lots">
		@foreach($lots as $lot)

		<?php 

		$patterns = array(" ", ";", "@", "?", ":", "=", "&", "<", ">", '"', "#", "[", "]", "%", "{", "}", "|", "^", "~", "/", "\\", "`", "$", "'");

		$url = '/golf-course-home-sites/' . str_replace($patterns, "-", $lot->location) . "-" . $lot->lot_number . "-" . $lot->id;
		?>

		<li>     
			<h3 class="name">
				<a href="{{ $url }}">
					Lot # {{ $lot->lot_number . $lot->lot_number_ext }}
				</a>
			</h3>
			<div class="listing-photo">
				<?php 

				$imgs = '';
				if ($lot->imgs != '' ) {
					$imgs = explode(',' , $lot->imgs);
				}
				?>

				@if ($imgs != '')
					@if (!is_null(MediaTools::getImage($imgs[0])))
						<?php $featured = MediaTools::getImage($imgs[0]); ?> 
					
						@if (!is_null($featured))
							<a href="{{ $url  }}">
								<img src="{{ $featured->thumbnail }}">
							</a>
						@endif
					

						<span>	
							@foreach ($imgs as $k => $img)
							<?php $img = MediaTools::getImage($img); ?>
								@if (!is_null($img))
								<a href="{{ $img->original_filename }}" class="swipebox" rel="lot-{{  $lot->lot_number }}">{{ $k == 0 ? 'Photos' : '' }}</a>
								@endif
							@endforeach
						</span>
					@endif
				@endif
			</div>
			<div class="listing-content">
				@if (!empty($lot["view"]))
				<p class="view"><strong>View:</strong> {{ $lot['view'] }}</p>
				@endif
				<p class="price"><strong>Price:</strong> {{ $lot['price'] }}</p>
				<p class="acreage"><strong>Acreage:</strong> {{ $lot['acreage'] }}</p>
				<p class="description">{{  \Illuminate\Support\Str::words($lot['description'], 50) }}</p>
				<a href="{{ $url }}" class="btn">View Details</a>
			</div>
		</li>
		@endforeach
	</ul>

	{{ $lots }}
