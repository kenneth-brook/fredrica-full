@extends('atlantis-admin::admin-shell')

@section('scripts')
    @parent
    {!! Html::script('/modules/atlantis/lots/src/Module/Lots/Assets/admin-map.js') !!}
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
                        Add Lot
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
                    @if (isset($msgError))
                        <div class="callout alert">
                            <h5>{!! dd($errors) !!}</h5>
                        </div>
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
            {!! Form::open(['url' => 'admin/modules/lots/add-lots', 'data-abide' => '', 'novalidate'=> '']) !!}
            <div class="row">
                <div class="columns">
                    <div class="float-right">
                        <div class="buttons">
                            <a href="/admin/modules/lots/lots-list" class="back button tiny top primary"
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
                            <!-- data-status: active, disabled or dev -->
                            <a href="#panel1" aria-selected="true">
                                {{ ((!empty($aCategoryEdit[0]['sCategoryName'])) ? 'Edit '.$aCategoryEdit[0]['sCategoryName'] : '') }}
                            </a>

                            @if(!empty($aCategoryEdit[0]['id']))
                                <span class="actions">
                                    <a data-tooltip title="Delete Property"
                                       href="/admin/modules/restaurantmenu/deletecategory/{{$aCategoryEdit[0]['id']}}"
                                       class="icon icon-Delete top"></a>
                                </span>
                            @endif
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">
                            <div class="row">

                                <div class="columns large-6">
                                    <div class="row">
                                        <div class="column large-2">
                                           <div class="switch tiny">
                                            <label for="sold">Sold</label>
                                            {!! Form::checkbox('sold', 1, FALSE, ['class' => 'switch-input', 'id' => 'sold']) !!}
                                            <label class="switch-paddle" for="sold"></label>
                                        </div>
                                            
                                        </div>
                                        <div class="column large-5">
                                            <label for="name">Lot Number
                                            {!! Form::input('text', 'lot_number', old('title')) !!}
                                            </label>                                            
                                        </div>
                                        <div class="column large-5">
                                            <label for="name">Lot Number Extention
                                            {!! Form::input('text', 'lot_number_ext') !!}
                                            </label>                                            
                                        </div>
                                    </div>
                                    <label for="name">Location
                                        {!! Form::input('text','location') !!}
                                    </label>                                    
                                    <label for="name">Acreage
                                        {!! Form::input('text','acreage') !!}
                                    </label>                                    
                                    <label for="name">View Orientation
                                        {!! Form::input('text','view_orientation') !!}
                                    </label>                                    
                                    <label for="name">Frontage
                                        {!! Form::input('text','frontage') !!}
                                    </label>                                    
                                    <label for="name">Price
                                        {!! Form::input('text','price') !!}
                                    </label>                                    
                                    <label for="name">Description
                                        {!! Form::textarea('description') !!}
                                    </label>
                                    <label for="name">Video link 
                                        <small class="help-text">https://vimeo.com/11111111 <strong>OR</strong> https://www.youtube.com/watch?v=t1t1r2</small>
                                        {!! Form::input('text','video') !!}
                                    </label>                                    

                                </div>

                                <div class="columns large-6">
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
                                   
                                </div>
                                  <div class="column">
                                    <hr>
                                    <div class="row">
                                        <div class="column large-6">
                                         <label for="name">Upload new Site Map (.pdf)</label>
                                         {!! \MediaTools::createImageSelector(NULL,FALSE,array(),FALSE, 'sitemap') !!}
                                        </div>
                                        <div class="column large-6">
                                            <label for="name">Lot Images</label>
                                    {!! \MediaTools::createImageSelector(NULL,TRUE,array(),FALSE, 'imgs[]') !!}
                                        </div>
                                    </div>
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