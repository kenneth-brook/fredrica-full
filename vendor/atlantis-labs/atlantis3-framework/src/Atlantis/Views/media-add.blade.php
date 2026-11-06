
@extends('atlantis-admin::admin-shell')

@section('title')
    @lang('admin::views.Add Media') | @lang('admin::views.A3 Administration') | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
    @parent
    {{-- Add scripts per template --}}
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/tagsInput/jquery.tagsinput.min.js') !!}

    {!! Html::script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js') !!}
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/plupload.full.min.js') !!}
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/jquery.ui.plupload/jquery.ui.plupload.js') !!}
    <!-- activate localization script -->
     {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/i18n/'. \App::getLocale() .'.js') !!}

    {{-- Load smartcrop scripts if checked in config --}}
    @if(config('atlantis.use_smart_crop'))
        {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/smartcrop/smartcrop.js') !!}

        {{-- Load facedetection scripts if checked in config --}}
        @if(config('atlantis.use_smart_crop') && config('atlantis.use_facedetection'))
            {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/jquery-facedetection/jquery.facedetection.min.js') !!}
        @endif
    @endif

@stop

@section('styles')
    @parent
    {!! Html::style('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/jquery.ui.plupload/css/jquery.ui.plupload.css') !!}
@stop

@section('content')
    <main>
        <section class="greeting">
            <div class="row">
                <div class="columns ">
                    <h1 class="huge page-title">@lang('admin::views.Add Media')</h1>
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
                </div>
            </div>
        </section>
        <section class="editscreen">
            {!! Form::open(['url' => 'admin/media/media-add', 'data-abide' => '', 'novalidate'=> '', 'id'=> 'media-form']) !!}
            <div class="row">
                <div class="columns">
                    <div class="float-right">
                        <div class="buttons">
                            <a href="admin/media" class="back button tiny top primary" title="@lang('admin::views.Go to Media')" data-tooltip>
                                <span class=" back icon icon-Goto"></span>
                            </a>
                            <a href="javascript:void(0)" class="button alert" onclick="$('#uploader').plupload('start')">@lang('admin::views.Upload')</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12">
                    <ul class="tabs" data-tabs id="example-tabs">
                        <li class="tabs-title is-active main">
                            <!-- data-status: active, disabled or dev -->
                            <a href="#panel1" aria-selected="true">@lang('admin::views.New Media')</a>
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">

                            <div class="row">
                                <div class="columns large-7">
                                    <div class="row">
                                        <div class="columns medium-4">
                                            <label for="filename">@lang('admin::views.Name')
                                                {!! Form::input('text', 'filename', old('filename'), ['id'=>'filename']) !!}
                                            </label>
                                        </div>
                                        <div class="columns medium-4">
                                            <label for="tags">Tags
                                                {!! Form::input('text', 'tags', old('tags'), ['class' => 'inputtags', 'id' => 'tags']) !!}
                                            </label>
                                        </div>
                                        <div class="columns medium-4">
                                            <label for="credit">@lang('admin::views.Credit')
                                                {!! Form::input('text', 'credit', old('credit'), ['id'=>'credit']) !!}
                                            </label>
                                        </div>
                                        <div class="columns medium-4">
                                            <label for="alt">@lang('admin::views.Alt')
                                                {!! Form::input('text', 'alt', old('alt'), ['id'=>'alt']) !!}
                                            </label>
                                        </div>
                                        <div class="columns medium-4">
                                            <label for="weight">@lang('admin::views.Weight')
                                                {!! Form::input('number', 'weight', old('weight', 1), ['min'=>'1']) !!}
                                            </label>
                                        </div>
                                        <div class="columns medium-4">
                                            <label for="css">@lang('admin::views.CSS')<span class="icon icon-Help top" data-tooltip title="@lang('admin::views.Inline styles')"></span>
                                                {!! Form::input('text', 'css', old('css'), ['id'=>'css']) !!}
                                            </label>
                                        </div>
                                        <div class="columns medium-4">
                                            <label for="anchor_link">@lang('admin::views.Anchor Link')<span class="icon icon-Help top" data-tooltip title="@lang('admin::views.Wrap img src tag')"></span>
                                                {!! Form::input('text', 'anchor_link', old('anchor_link'), ['id'=>'anchor_link']) !!}
                                            </label>
                                            <label for="resize">@lang('admin::views.Resize')
                                                {!! Form::select('resize', $aResize, NULL, ['id' => 'resize']) !!}
                                            </label>
                                        </div>
                                        <div class="columns medium-4">
                                            <label for="caption">@lang('admin::views.Caption')
                                                {!! Form::textarea('caption', old('caption'), ['rows' => 4, 'cols' => '30', 'id' => 'caption']) !!}
                                            </label>
                                        </div>
                                        <div class="columns medium-4">
                                            <label for="description">@lang('admin::views.Description')
                                                {!! Form::textarea('description', old('description'), ['rows' => 4, 'cols' => '30', 'id' => 'description']) !!}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="columns large-5">
                                    <div id="uploader">
                                            <p>@lang('admin::views.plupload_notsupported')</p>
                                    </div>
                                    @if(trait_exists('\Module\Relativity\Traits\RelativityTrait'))
                                    <div class="row">
                                        <div class="column">

                                            {!! \Module\Relativity\Controllers\RelativityController::theVocabulariesBySlugs(['media']) !!}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </section>
    </main>
    <footer>

        <div class="row">
            <div class="columns">
            </div>
        </div>
    </footer>
@stop

@section('js')
    @parent
    @include('atlantis-admin::media.media-add-edit-script', array('action'=>'add'))
@stop