  <?php 
    $random_post =  mt_rand(0,count($related)-1);
   ?>

     
      	@foreach($related as  $key => $lot) 
      	 
      	     <?php $patterns = array(" ", ";", "@", "?", ":", "=", "&", "<", ">", '"', "#", "[", "]", "%", "{", "}", "|", "^", "~", "/", "\\", "`", "$", "'");

		     $url = '/st-simons-home-sites/' . str_replace($patterns, "-", $lot->location) . "-" . $lot->lot_number; //. "-" . $lot->id;
		     ?>
	   <div class = "col-lg-6 col-sm-6 col-12">
	          <div class="box-homes" data-aos="fade-up" data-aos-once="true" data-aos-delay="50"><a href="{{  $url }}">
	          	     <?php $imgs = '';
					    if ($lot->imgs != '' ):
						$imgs = explode(',' , $lot->imgs);		
					    endif;
					    ?>
		            	@if ($imgs != '')
						@if (!is_null(MediaTools::getImage($imgs[0])))

						<?php $featured = MediaTools::getImage($imgs[0]); ?> 

						<img class= "lazy" data-src="/{{ $featured->thumbnail }}">

						@else

						<img class= "lazy" data-src="/resources/media/a2users/{{ $imgs[0] }}">

						@endif
						@endif
		              <p>Home site {{ $lot->lot_number . $lot->lot_number_ext }}</p>
		              <p class="price">Offered: {{ $lot['price'] }}</p>
		              <div class="info">		             
		                <div>View Details</div>		                
		              </div>
		            </a>
		        </div>
		    </div>       
        @endforeach
    