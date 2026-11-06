
<?php 

$imgs = '';
if (!is_null($oLot->imgs) ) {
    $imgs = explode(',' , $oLot->imgs);
    $featured = MediaTools::getImage($imgs[0]);
}
?>
<section class="container-lg container">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>      
      <li class="breadcrumb-item"><a href="{{ url('/st-simons-island-real-estate') }}">Real Estate</a></li> 
      <li class="breadcrumb-item"><a href="{{ url('/st-simons-home-sites') }}">Home Site Offerings</a></li>  
      <li class="breadcrumb-item active">
        Home Site {{ $oLot->lot_number . $oLot->lot_number_ext }}
      </li>     
    </ol>
  </nav>
</section>
 <section class="estate-post">
    <div class="estate-title container"><div data-aos="fade-up" data-aos-delay = "20"><span class="square blue"></span></div>
      <h1 class="text-center" data-aos="fade-up" data-aos-delay = "50">Home Site {{ $oLot->lot_number . $oLot->lot_number_ext }}</h1>
    </div>
    <div class="estate-slider" data-aos="fade-up" data-aos-delay = "70">
      <div class="slider-images container">
        <div class="swiper-container">
          <div class="swiper-wrapper">
            @if ($imgs != '')     

            @foreach ($imgs as $k => $img)
                <?php $img = MediaTools::getImage($img);
                //var_dump($img); ?>
                    @if (!is_null($img))                    
                         <a class="swiper-slide" style="background:url('/{{ $img->original_filename }}');background-size: contain !important;background-position: center center !important;background-repeat: no-repeat !important;width: 1110px;" href = "/{{ $img->original_filename }}" data-lightbox = "gallery"></a>
                    @endif
                @endforeach
            @endif           
          </div>
        </div>
        <div class="slide-nav">
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </div>
    </div>
    @if (!empty($oLot->description))
    <div class="content-estate" data-aos="fade-up">
      <p><?= $oLot->description ?></p>
     @endif
    </div>
    <!-- VIDEO -->
	@include('lots::embed_video', ['video_link' => $oLot->video])
    <div class="line"></div>
  </section>
  <section class="details" data-aos="fade-up" data-aos-delay = "70">
    <div class="container list-details">
      <h2 class="text-center" >Lot Details</h2>
      <div class="inner-details">
        <div class="table-list">
        
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
        <div class="more-details">
        @if (!is_null($oLot->sitemap))  
		<div class="box">
			@if (!is_null(MediaTools::getImage($oLot->sitemap)))
			<?php $sitemap = MediaTools::getImage($oLot->sitemap); ?>
			<a href="/{{ $sitemap->original_filename }}" target="_blank" class = "btn btn-default">View sitemap</a>
			@else
				<!-- <a href="/resources/media/a2users/{{ $oLot->sitemap }}" target="_blank">View sitemap</a> -->
			@endif
		</div>
		@endif		
        <div class="box">
            <a href="/contact" class = "btn btn-default">Request more information</a>
        </div>
      </div>
    </div>
</div>
  </section>