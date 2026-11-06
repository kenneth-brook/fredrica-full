@extends('atlantis-admin::admin-shell')

@section('title')
    @lang('admin::views.Edit Tags') | @lang('admin::views.A3 Administration') | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
    @parent
    {{-- Add scripts per template --}}
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/tagsInput/jquery.tagsinput.min.js') !!}

    {!! Html::script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js') !!}
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/plupload.full.min.js') !!}
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/jquery.ui.plupload/jquery.ui.plupload.js') !!}
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
                    <h1 class="huge page-title">@lang('admin::views.Add Tags')</h1>
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
            {!! Form::open(['url' => 'admin/media/add-tags-to-multiple-images', 'data-abide' => '', 'novalidate'=> '', 'id'=> 'media-form']) !!}
            <div class="row">
                <div class="columns">
                    <div class="float-right">
                        <div class="buttons">
                            <a href="admin/media" class="back button tiny top primary" title="@lang('admin::views.Go to Media')" data-tooltip>
                                <span class=" back icon icon-Goto"></span>
                            </a>
                            {!! Form::input('submit', '_save_close', trans("admin::views.Save & Close"), ['class' => 'alert button', 'id'=>'save-close-btn']) !!}
                            {!! Form::input('submit', '_update', trans("admin::views.Update"), ['class' => 'alert button', 'id'=>'update-btn']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12">
                    <ul class="tabs" data-tabs id="example-tabs">
                        <li class="tabs-title is-active main">
                            <!-- data-status: active, disabled or dev -->
                            <a href="#panel1" aria-selected="true">@lang('admin::views.New Tags')</a>
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">

                            <div class="row">
                                <div class="columns">
                                   <label for="tags">@lang('admin::views.Tags')
                                    {!! Form::input('text', 'tags', '', ['class' => 'inputtags', 'id' => 'tags']) !!}
                                    </label>
                                </div>
                                <div class="columns">
                                    {!! DataTable::set(\Atlantis\Controllers\Admin\MediaAddTagsToImageDataTable::class,['iImageId'=>$sImageIds]) !!}
                                </div>
                                <input type="hidden" name="imageID" value="{{$sImageIds}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </section>
    </main>
    <footer>
        {{-- @include('atlantis-admin::help-sections/gallery') --}}
        <div class="row">
            <div class="columns">
            </div>
        </div>
    </footer>
@stop

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
        $("#gal-container").sortable({
            containerSelector: 'div',
            itemSelector : 'span',
            tolerance : -10,
            placeholder: 'placeholder item' //<img src="http://placehold.it/150x150">
        });
    });
</script>

