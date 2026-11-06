<?php 
$imgs = '';
if (!is_null($oListing->images) ) {
    $imgs = explode(',' , $oListing->images);
    $featured = MediaTools::getImage($imgs[0]);
}
?>
<section class="container-lg container">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>      
      <li class="breadcrumb-item"><a href="{{ url('/st-simons-island-real-estate') }}">Real Estate</a></li> 
      <li class="breadcrumb-item"><a href="{{ url('/st-simons-homes-for-sale') }}">Home Offerings</a></li>  
      <li class="breadcrumb-item active">
        {{$oListing->title}}
      </li>     
    </ol>
  </nav>
</section>
 <section class="estate-post">
    <div class="estate-title container"><div data-aos="fade-up" data-aos-delay = "20"><span class="square blue"></span></div>
      <h1 class="text-center" data-aos="fade-up" data-aos-delay = "50"><?= $oListing->title; ?></h1>
    </div>
    <div class="estate-slider" data-aos="fade-up" data-aos-delay = "70">
      <div class="slider-images container">
        <div class="swiper-container">
          <div class="swiper-wrapper">
            @if ($imgs != '')     

            @foreach ($imgs as $k => $img)
                <?php $img = MediaTools::getImage($img); ?>
                    @if (!is_null($img))                    
                         <a class="swiper-slide" style="background:url('/{{ $img->original_filename }}');background-size: contain !important;background-position: center center!important;background-repeat: no-repeat!important;" href = "/{{ $img->original_filename }}" data-lightbox = "gallery"></a>
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
    <div class="content-estate" data-aos="fade-up">
      <p><?= $oListing->description ?></p>
    </div>
    <!-- VIDEO -->
    <!-- @include('listings::embed_video', ['video_link' => $oListing->video]) -->
    @include('listings::embed_video', ['video_link' => $oListing->you_tube])
    <div class="line"></div>
  </section>
   
  <section class="details" data-aos="fade-up" data-aos-delay = "70">
    <div class="container list-details">
      <h2 class="text-center" >Listing Details</h2>
      <div class="inner-details">
        <div class="table-list">
         <table>

        <?php if (!empty($oListing->price)) : ?>
            <tr>
                <th>Price:</th><td><?= str_replace('$', "&#36;", $oListing->price); ?></td>
            </tr>
        <?php endif; ?>

        <?php if (!empty($oListing->lot_type)) : ?>
            <tr>
                <th>Lot:</th><td><?= $oListing->lot_type; ?></td>
            </tr>
        <?php endif; ?>


        <?php if (!empty($oListing->heated_sq_ft)) : ?>
            <tr>
                <th>Footage:</th><td><?= $oListing->heated_sq_ft; ?> heated sq. ft.</td>
            </tr>
        <?php endif; ?>

        <?php if (!empty($oListing->bedrooms)) : ?>
            <tr>
                <th>Bedrooms:</th><td><?= $oListing->bedrooms; ?></td>
            </tr>
        <?php endif; ?>


        <?php if (!empty($oListing->full_baths) && !empty($oListing->half_baths)) : ?>
            <tr>
                <th>Bath:</th> 
                <td><?php
                    if (!empty($oListing->full_baths)) : echo $oListing->full_baths . " Full  ";
                    endif;
                    ?><?php
                    if (!empty($oListing->half_baths)) : echo $oListing->half_baths . " Half";
                    endif;
                    ?></td>
            </tr>
        <?php endif; ?>


        <tr>
            <th>Furnished:</th> 
            <td><?php
                if ($oListing->furnished == '1') : echo "Yes";
                else : echo "No";
                endif;
                ?></td>
        </tr>

        <?php if (!empty($oListing->acres)) : ?>
            <tr>
                <th>Acreage:</th><td><?= $oListing->acres; ?></td>
            </tr>
        <?php endif; ?>

        <?php if (!empty($oListing->architect)) : ?>
            <tr>
                <th>Architect:</th><td><?= $oListing->architect; ?></td>
            </tr>
        <?php endif; ?>

        <?php if (!empty($oListing->builder)) : ?>
            <tr>
                <th>Builder:</th><td><?= $oListing->builder; ?></td>
            </tr>
        <?php endif; ?>

    </table>
        </div>
        <div class="more-details">
             @if (!is_null($oListing->floor_plan))  
        <div class="box">
            @if (!is_null(MediaTools::getImage($oListing->floor_plan)))
            <?php $floor_plan = MediaTools::getImage($oListing->floor_plan); ?>
            <a href="/{{ $floor_plan->original_filename }}" class = "btn btn-default"target="_blank">View floor plan</a>
            @endif
        </div>
        @endif
        
        <!-- BROCHURE -->
        @if (!is_null($oListing->brochure))  
        <div class="box">
            @if (!is_null(MediaTools::getImage($oListing->brochure)))
            <?php $brochure = MediaTools::getImage($oListing->brochure); ?>
            <a href="/{{ $brochure->original_filename }}" class = "btn btn-default"target="_blank">View brochure</a>
            @endif
        </div>
        @endif
         <!-- Finishes -->
        @if (!is_null($oListing->finishes))  
        <div class="box">
            @if (!is_null(MediaTools::getImage($oListing->finishes)))
            <?php $finishes = MediaTools::getImage($oListing->finishes); ?>
            <a href="/{{ $finishes->original_filename }}" class = "btn btn-default"target="_blank">New Home Finishes</a>
            @endif
        </div>
        @endif


        
        <div class="box">
            <a href="/contact" class = "btn btn-default">Request more information</a>
        </div>

        <?php if (!empty($oListing->video)) : ?>  
            <div class="box">
                <a href="<?= $oListing->video; ?>" class = "btn btn-default"target="_blank">In the News</a>
            </div>
        <?php endif; ?>
      </div>
    </div>
</div>
  </section>