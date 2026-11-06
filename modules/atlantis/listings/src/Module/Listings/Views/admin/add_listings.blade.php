@extends('atlantis-admin::admin-shell')

@section('scripts')
@parent
{!! Html::script('/modules/atlantis/listings/src/Module/Listings/Assets/admin-map.js') !!}
{!! Html::script('https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyAoRNz5enqHeRufpal3jIo3CmfMRFsrFNY') !!}
@stop

@section('scripts')
@parent
<script>
  $(document).ready(function () {

    mapinit("maplat", "maplng", false, false);

});

</script>
@stop

@section('content')

<main>
    <section class="greeting">
        <div class="row">
            <div class="columns ">
                <h1 class="huge page-title">
                    Add Listing
                </h1>
                @if (isset($msgInfo))
                <div class="callout warning">
                    <h5>{!! $msgInfo !!}</h5>
                </div>
                @endif
                @if (isset($msgSuccess))
                <div class="callout success">
                    <h5>{!! $msgSuccess !!}</h5>
                </div>
                @endif
                @if (isset($msgError))
                <div class="callout alert">
                    <h5>{!! $msgError !!}</h5>
                </div>
                @endif
                @if (isset($errors))
                @foreach ($errors->all() as $e)
                <div class="callout alert">

                    <h5>{!! $e !!}</h5>
                </div>
                @endforeach
                @endif

                <div class="float-right">
                        <!-- <div class="buttons">
                                <a id="save-close-btn" class="alert button" href="#">New</a>
                            </div> -->
                        </div>
                    </div>
                </div>
            </section>
            <section class="editscreen">
                {!! Form::open(['url' => 'admin/modules/listings/add-listings', 'data-abide' => '', 'novalidate'=> '']) !!}
                <div class="row">
                    <div class="columns">
                        <div class="float-right">
                            <div class="buttons">
                                <a href="/admin/modules/listings/listings-list" class="back button tiny top primary"
                                title="Go to Category list" data-tooltip>
                                <span class=" back icon icon-Goto"></span>
                            </a>
                            {!! Form::input('submit', '_save_close', 'Save & Close', ['class' => 'alert button', 'id'=>'save-close-btn']) !!}
                            {{--   Form::input('submit', '_update', 'Update', ['class' => 'alert button', 'id'=>'update-btn']) --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12">
                    <ul class="tabs" data-tabs id="example-tabs">
                        <li class="tabs-title is-active main">
                            <a href="#panel1" aria-selected="true">
                                New listing
                            </a>
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">
                            <div class="row">

                                <div class="columns large-7">
                                    <div class="row">
                                        <div class="column large-2">
                                            <div class="switch">
                                                <label for="active">Active</label>
                                                {!! Form::checkbox('active', 1, TRUE, ['class' => 'switch-input', 'id' => 'active']) !!}
                                                <label class="switch-paddle" for="active"></label>
                                            </div>

                                        </div>

                                        <div class="column large-5">
                                            <label for="name">Title

                                                {!! Form::input('text', 'title', old('title')) !!}
                                            </label>                                            
                                        </div>
                                        <div class="column large-5">
                                            <label for="name">URL
                                                {!! Form::input('text', 'url') !!}
                                            </label>                                            
                                        </div>
                                        <div class="column large-2">
                                            <label for="name">Home Number
                                                {!! Form::input('number','home_site_number') !!}
                                            </label>  
                                        </div>
                                    </div>
                                    
                                    <label for="name">Meta Description
                                        {!! Form::input('text','meta_desc') !!}
                                    </label>                                    
                                    <label for="name">Meta Keywords
                                        {!! Form::input('text','meta_key') !!}
                                    </label>
                                    <div class="row">
                                        <div class="column large-5">
                                          <label for="name">Type
                                            {!! Form::select('type', array(
                                                'Standard' => 'Standard',
                                                'Custom Home' => 'Custom Home',
                                                'Alford Lane Homes' => 'Alford Lane Homes')
                                                )!!}
                                            </label>                                        
                                        </div>
                                        <div class="column large-5">

                                          <label for="name">Listing Type
                                            {!! Form::select('listing_type', array(
                                                'Unspecified' => 'Unspecified',
                                                'Phase 1' => 'Phase 1',
                                                'Phase 2' => 'Phase 2')
                                                )!!}
                                            </label>                                         
                                        </div>
                                        <div class="column large-2">
                                            <div class="switch">
                                                <label for="furnished">furnished</label>
                                                {!! Form::checkbox('furnished', 1, FALSE, ['class' => 'switch-input', 'id' => 'furnished']) !!}
                                                <label class="switch-paddle" for="furnished"></label>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="column large-3">
                                            <label for="name">Price
                                                {!! Form::input('text','price') !!}
                                            </label>
                                        </div>
                                        <div class="column large-3">
                                            <label for="name">Architect
                                                {!! Form::input('text','architect') !!}
                                            </label>
                                        </div>
                                        <div class="column large-3">
                                            <label for="name">Builder
                                                {!! Form::input('text','builder') !!}
                                            </label>
                                        </div>
                                        <div class="column large-3">
                                            <label for="name">Lot Type
                                                {!! Form::input('text','lot_type') !!}
                                            </label>
                                        </div>
                                        <div class="column large-3">
                                            <label for="name">Heated Sq Ft
                                                {!! Form::input('text','heated_sq_ft') !!}
                                            </label>
                                        </div>
                                        <div class="column large-3">
                                            <label for="name">Acres
                                                {!! Form::input('text','acres') !!}
                                            </label>
                                        </div>
                                        <div class="column large-3">
                                            <label for="name">Bedrooms
                                                {!! Form::input('number','bedrooms') !!}
                                            </label>
                                        </div>
                                        <div class="column large-3">
                                            <label for="name">Full Baths
                                                {!! Form::input('number','full_baths') !!}
                                            </label>
                                        </div>
                                        <div class="column large-3">
                                            <label for="name">Half Baths
                                                {!! Form::input('number','half_baths') !!}
                                            </label>
                                        </div>
                                        <div class="column">

                                            <label for="name">In the News Link
                                                {!! Form::input('text','video') !!}
                                            </label>
                                            <label for="name">Video Link
                                                {!! Form::input('text','you_tube') !!}
                                            </label>
                                            <label for="name">Description
                                                {!! Form::textarea('description', '', ['rows'=>4]) !!}
                                            </label>
                                            <label for="name">Property Details
                                                {!! Form::textarea('details','', ['rows'=>2]) !!}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="columns large-5">
                                    <div class="row">
                                        <div class="column large-4">
                                            <label for="name">Lat</label>
                                            {!! Form::input('text','lat', old('lat'), array('id' => 'lat', 'readonly' =>'readonly')) !!}
                                        </div>
                                        <div class="column large-4">
                                            <label for="name">Long</label>
                                            {!! Form::input('text','lng', old('lng'), array('id' => 'lng', 'readonly' =>'readonly')) !!}
                                        </div>
                                        <div class="column large-4">
                                          <label for="name">Map Category
                                            {!! Form::select('map_category', array(
                                                'river' => 'River',
                                                'marsh' => 'Marsh',
                                                'lake' => 'Lake',
                                                'golf' => 'Golf',
                                                'wooded' => 'Wooded')
                                                )!!}
                                            </label>    
                                        </div>
                                    </div>

                                    <div id="map" name="map" style="width:  100%; height: 500px;"></div>

                                    <br><br>

                                    <div class="row">
                                        <div class="column large-6">
                                            <label for="name">Upload new Floor Plan (.pdf)</label>
                                            {!! \MediaTools::createImageSelector(NULL,FALSE,array(),FALSE, 'floor_plan') !!}
                                        </div>
                                        <div class="column large-6">
                                            <label for="name">Upload new Brochure (.pdf)</label>
                                            {!! \MediaTools::createImageSelector(NULL,FALSE,array(),FALSE, 'brochure') !!}
                                        </div>
                                         <div class="column large-6">
                                            <label for="name">Upload new Finishes</label>
                                            {!! \MediaTools::createImageSelector(NULL,FALSE,[],FALSE, 'finishes') !!}
                                        </div>
                                    </div>


                                </div>
                                <div class="column">
                                    <hr>
                                    <label for="name">Listing Images</label>
                                    {!! \MediaTools::createImageSelector(NULL,TRUE,array(),FALSE, 'images[]') !!}
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </section>
    </main>

    @stop