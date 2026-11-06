<?php 

$imgs = '';
if (!is_null($oListing->images) ) {
    $imgs = explode(',' , $oListing->images);
    $featured = MediaTools::getImage($imgs[0]);
}
?>
<div id="listing">
    <h1><?= $oListing->title; ?></h1>
    <div id="photo">
        @if (!is_null($featured))
            <img src="{{ $featured->original_filename }}">
        @endif

        @if (!is_null($oListing->floor_plan))  
        <div class="box">
            @if (!is_null(MediaTools::getImage($oListing->floor_plan)))
            <?php $floor_plan = MediaTools::getImage($oListing->floor_plan); ?>
            <a href="{{ $floor_plan->original_filename }}" target="_blank">View floor plan</a>
            @endif
        </div>
        @endif
        
        <!-- BROCHURE -->
        @if (!is_null($oListing->brochure))  
        <div class="box">
            @if (!is_null(MediaTools::getImage($oListing->brochure)))
            <?php $brochure = MediaTools::getImage($oListing->brochure); ?>
            <a href="{{ $brochure->original_filename }}" target="_blank">View brochure</a>
            @endif
        </div>
        @endif

        <?php if (!empty($oListing->video)) : ?>  
            <div class="box">
                <a href="<?= $oListing->video; ?>" target="_blank">In the News</a>
            </div>
        <?php endif; ?>
        <div class="box">
            <a href="/contact#mainColumn">Request more information</a>
        </div>
    </div>
    <?php if (!empty($oListing->description)) : ?>
        <div id="description">
            <h2>Home Description</h2>
            <div><?= $oListing->description ?></div>
        </div>
    <?php endif; ?>
    <?php if (!empty($oListing->details)) : ?>
        <div id="description2">
            <h2>Property Details</h2>
            <div><?= $oListing->details; ?></div>
        </div>
    <?php endif; ?>
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
</div>  <!-- /#listing -->
