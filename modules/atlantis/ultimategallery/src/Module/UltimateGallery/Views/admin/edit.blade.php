@extends('atlantis-admin::admin-shell')

@section('styles')
@parent 

{!! Html::style('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/admin/codemirror/lib/codemirror.css') !!}
@stop


@section('scripts')
@parent
{!! Html::script('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/admin/codemirror/lib/codemirror.js') !!}
{!! Html::script('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/admin/codemirror/mode/javascript.js') !!}
{!! Html::script('modules/atlantis/ultimategallery/src/Module/UltimateGallery/Assets/admin/codemirror/mode/css.js') !!}
@stop

@section('js')
@parent
<script type="text/javascript">
    CodeMirror.fromTextArea($('#cm-options').get(0), {
        lineNumbers: true,
        mode: "javascript"
    }).setSize(null, 200);

    CodeMirror.fromTextArea($('#cm-css').get(0), {
        lineNumbers: true,
        mode: "css"
    }).setSize(null, 200);
</script>
@stop

@section('title')
Edit Gallery | A3 | {{ config('atlantis.site_name') }}
@stop

@section('content')
<main>
    <section class="greeting">
        <div class="row">
            <div class="columns ">
                <h1 class="huge page-title">Ultimate Gallery</h1>
                @if (isset($msgInfo))
                <div class="callout warning">
                    <h5>{!! $msgInfo !!}</h5>
                </div>
                @endif @if (isset($msgSuccess))
                <div class="callout success">
                    <h5>{!! $msgSuccess !!}</h5>
                </div>
                @endif
                @if (isset($msgError))
                <div class="callout alert">
                    <h5>{!! $msgError !!}</h5>
                </div>
                @endif
                @if(count($errors->all()))
                <div class="alert callout">
                    @foreach($errors->all() as $error)
                    <span>{{ $error }}</span><br />
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    <section class="editscreen">
        {!! Form::open(['url' => 'admin/modules/ultimategallery/edit/'.$id, 'data-abide' => '', 'novalidate'=> '']) !!}
        <div class="row">
            <div class="columns">
                <div class="float-right">
                    <div class="buttons">
                        <a href="{{ url('/') }}/admin/modules/ultimategallery" class="back button tiny top primary" title="Go to List" data-tooltip>
                            <span class=" back icon icon-Goto"></span>
                        </a>
                        {!! Form::input('submit', '_save_close', 'Save & Close', ['class' => 'alert button', 'id'=>'save-close-btn']) !!}
                        {!! Form::input('submit', '_update', 'Update', ['class' => 'alert button', 'id'=>'update-btn']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12">
                <ul class="tabs" data-tabs id="example-tabs">
                    <li class="tabs-title is-active main">
                        <a href="#panel1" aria-selected="true">{{ $name }}</a>
                    </li>
                </ul>
                <div class="tabs-content" data-tabs-content="example-tabs">
                    <div class="tabs-panel is-active" id="panel1">

                        <div class="row">
                            <div class="columns large-4">
                                <label for="name">Name
                                    {!! Form::input('text', 'name', $name, ['id'=>'name']) !!}
                                </label>

                                <label for="name">Description
                                    {!! Form::input('text', 'description', $description, ['id'=>'description']) !!}
                                </label>

                                <label>View
                                    {!! Form::select('view', $aViews, $view) !!}
                                </label>
                                <label for="cm-options">Plugin Options
                                </label>
                                <p class="help-text" style="margin:0">Each view uses different jQuery 
                                plugin. These plugins usually accept settings from an object of key/value pairs. If you are not familiar with the plugin, you might leave this field blank. ex. <kbd>{transition:"elastic"}</kbd></p>
                                {!! Form::textarea('options', $options, ['id'=>'cm-options']) !!}
                                <br>
                                <label>Custom CSS</label>
                                  <p class="help-text" style="margin:0">
                                    If you are familiar with CSS, you can append custom styles for this gallery. <br>ex. <kbd>.caption{color: red;}</kbd>
                                </p>
                                {!! Form::textarea('css', $css,  ['id'=>'cm-css']) !!}
                                 <hr>
                            <p class="help-text">
                                Manual: 
                                <a target="_blank" href="http://www.jacklmoore.com/colorbox/">colorbox</a> |
                                <a target="_blank" href="http://fancyapps.com/fancybox/">fancybox</a> |
                                <a target="_blank" href="http://fotorama.io/">fotorama</a> |  
                                <a target="_blank" href="https://owlcarousel2.github.io/OwlCarousel2/docs/api-options.html">owl carousel</a> |  
                                <a target="_blank" href="https://isotope.metafizzy.co/">isotype</a> |  
                                <a target="_blank" href="https://docs.galleria.io/">galleria</a> |  
                                <a target="_blank" href="https://github.com/kawshar/lightboxgallery">lightbox</a>  
                            </p>
                                
                            </div>
                            <div class="columns large-8 end">
                             <label for="">Images</label>
                             {!! \MediaTools::createImageSelector(null, true, $imgs, false) !!}
                         </div>
                         <div class="columns">

                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     {!! Form::close() !!}
 </section>
</main>

@include('ultimategallery-admin::admin.tips')

@stop