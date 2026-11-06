     
@foreach($related as $listing)      	
	<div class = "col-lg-6 col-sm-6 col-12">
	          <div class="box-homes" data-aos="fade-up" data-aos-once="true" data-aos-delay="50"><a href="/st-simons-homes-for-sale/{{ $listing['url'] }}">
	          	    <?php $imgs = '';
					    if ($listing->images != '' ):
						$imgs = explode(',' , $listing->images);		
					    endif;
					    ?>
		            	@if ($imgs != '')
						@if (!is_null(MediaTools::getImage($imgs[0])))

						<?php $featured = MediaTools::getImage($imgs[0]); ?> 

						<img class = "lazy" data-src="/{{ $featured->thumbnail }}">

						@else

						<img class = "lazy" data-src="/resources/media/a2users/{{ $imgs[0] }}">

						@endif
						@endif
		              <p>{{ $listing['title'] }}</p>
		              <p class="price">Offered: {{ $listing['price'] }}</p>
		              <div class="info">		             
		                <div>View Details</div>		                
		              </div>
		            </a>
		        </div>	     
		  </div>          
 @endforeach
   
