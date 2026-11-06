@extends('atlantis-admin::admin-shell')

@section('title')
  @lang('admin::views.Edit Media') | @lang('admin::views.A3 Administration') | {{ config('atlantis.site_name') }}
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

  {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/Jcrop/Jcrop.js') !!}

  @if(config('atlantis.use_smart_crop'))
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/smartcrop/smartcrop.js') !!}
  @endif
  {{-- Load facedetection scripts if checked in config --}}
  @if(config('atlantis.use_smart_crop') && config('atlantis.use_facedetection'))
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/jquery-facedetection/jquery.facedetection.min.js') !!}
  @endif

@stop
@section('styles')
  @parent
  {!! Html::style('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/jquery.ui.plupload/css/jquery.ui.plupload.css') !!}
  {!! Html::style('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/css/Jcrop.css') !!}
@stop

@section('content')
  @if (isset($invalid_item))
    <div class="callout alert">
      <h5>{{ $invalid_item }}</h5>
    </div>
  @else
    <main>
      <section class="greeting">
        <div class="row">
          <div class="columns ">
            <h1 class="huge page-title">@lang('admin::views.Edit Media')</h1>
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
        {!! Form::open(['url' => 'admin/media/media-edit/' . $media->id, 'data-abide' => '', 'novalidate'=> '', 'id'=> 'media-form']) !!}
        <div class="row">
          <div class="columns">
            <div class="float-right">
              <div class="buttons">
                <a href="admin/media" class="back button tiny top primary" title="@lang('admin::views.Go to Media')" data-tooltip>
                  <span class=" back icon icon-Goto"></span>
                </a>
                {!! Form::input('submit', '_save_close', trans('admin::views.Save & Close'), ['class' => 'alert button', 'id'=>'save-close-btn']) !!}
                {!! Form::input('submit', '_update', trans('admin::views.Update'), ['class' => 'alert button', 'id'=>'update-btn']) !!}
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="columns small-12">
            <ul class="tabs" data-tabs id="example-tabs">
              <li class="tabs-title is-active main">
                <!-- data-status: active, disabled or dev -->
                <a href="#panel1" aria-selected="true">{!! !empty($media->filename) ? $media->filename : $media->original_filename !!}</a>
              </li>
            </ul>
            <div class="tabs-content" data-tabs-content="example-tabs">
              <div class="tabs-panel is-active" id="panel1">

                <div class="row">
                  <div class="columns large-7">
                    <div class="row">
                      <div class="columns medium-4">
                        <label for="filename">@lang('admin::views.Name')
                          {!! Form::input('text', 'filename', old('filename', $media->filename), ['id'=>'filename']) !!}
                        </label>
                      </div>
                      <div class="columns medium-4">
                        <label for="tags">@lang('admin::views.Tags')
                          {!! Form::input('text', 'tags', old('tags', $tags), ['class' => 'inputtags', 'id' => 'tags']) !!}
                        </label>
                      </div>
                      <div class="columns medium-4">
                        <label for="credit">@lang('admin::views.Credit')
                          {!! Form::input('text', 'credit', old('credit', $media->credit), ['id'=>'credit']) !!}
                        </label>
                      </div>
                      <div class="columns medium-4">
                        <label for="alt">@lang('admin::views.Alt')
                          {!! Form::input('text', 'alt', old('alt', $media->alt), ['id'=>'alt']) !!}
                        </label>
                      </div>
                      <div class="columns medium-4">
                        <label for="weight">@lang('admin::views.Weight')
                          {!! Form::input('number', 'weight', old('weight', $media->weight), ['min'=>'1']) !!}
                        </label>
                      </div>
                      <div class="columns medium-4">
                        <label for="css">@lang('admin::views.CSS')<span class="icon icon-Help top" data-tooltip title="@lang('admin::views.Inline styles')"></span>
                          {!! Form::input('text', 'css', old('css', $media->css), ['id'=>'css']) !!}
                        </label>
                      </div>
                      <div class="columns medium-4">
                        <label for="anchor_link">@lang('admin::views.Anchor Link')<span class="icon icon-Help top" data-tooltip title="@lang('admin::views.Wrap img src tag')"></span>
                          {!! Form::input('text', 'anchor_link', old('anchor_link', $media->anchor_link), ['id'=>'anchor_link']) !!}
                        </label>
                        <label for="resize">@lang('admin::views.Resize')
                          {{--!! Form::select('resize', $aResize, $selected_resize, ['id' => 'resize', 'disabled'=>'disabled']) !!--}}
                          {!! Form::input('text', 'resize',  $selected_resize, ['id'=>'resize', 'readonly'=>'readonly']) !!}
                        </label>
                      </div>
                      <div class="columns medium-4">
                        <label for="caption">@lang('admin::views.Caption')
                          {!! Form::textarea('caption', old('caption', $media->caption), ['rows' => 4, 'cols' => '30', 'id' => 'caption']) !!}
                        </label>
                      </div>
                      <div class="columns medium-4">
                        <label for="description">@lang('admin::views.Description')
                          {!! Form::textarea('description', old('description', $media->description), ['rows' => 4, 'cols' => '30', 'id' => 'description']) !!}
                        </label>
                      </div>
                    </div>
                    <hr>
                    <h5>@lang('admin::views.File Versions')</h5>
                    <table class="dataTable">
                      <thead>
                      <tr>
                        <th style="width: 130px;">
                          @lang('admin::views.Size')
                        </th>
                        <th>
                          @lang('admin::views.Path')
                        </th>
                      </tr>
                      </thead>
                      <tr>
                        <td>
                          @lang('admin::views.Original / Large')
                        </td>
                        <td class="copy-path name">
                          <div class="text text-center">@lang('admin::views.Copied')</div>
                          <code class="path">{{ config('atlantis.user_media_upload') . $media->original_filename }}</code>
                          <div class="actions">
                            <a data-tooltip title="@lang('admin::views.Copy file path')" class="copy-path icon icon-Files top"></a>
                            <a data-tooltip title="@lang('admin::views.Open image in new tab')" target="blank" href="{!! Atlantis\Helpers\Tools::getFilePath() . $media->original_filename !!}" class="icon icon-Export top"></a>
                          </div>
                        </td>
                      </tr>
                      @if (!empty($media->tablet_name))
                        <tr>
                          <td>
                            Medium
                          </td>
                          <td class="copy-path name">
                            <div class="text text-center">@lang('admin::views.Copied')</div>
                            <code class="path">{{ config('atlantis.user_media_upload') . $media->tablet_name }}</code>

                            <div class="actions">
                              <a data-tooltip title="@lang('admin::views.Copy file path')" class="copy-path icon icon-Files top"></a>
                              <a data-tooltip title="@lang('admin::views.Open image in new tab')" target="blank" href="{!! Atlantis\Helpers\Tools::getFilePath() . $media->tablet_name !!}" class="icon icon-Export top"></a>
                              <a data-open="manualCrop" data-version="tablet" data-tooltip title="@lang('admin::views.Recrop Manually')" class="icon icon-ExitFullScreen top show-for-large" aria-hidden="true"></a>
                            </div>
                          </td>
                        </tr>
                      @endif @if (!empty($media->phone_name))
                        <tr>
                          <td>Small</td>
                          <td class="copy-path name">
                            <div class="text text-center">@lang('admin::views.Copied')</div>
                            <code class="path">{{ config('atlantis.user_media_upload') . $media->phone_name }}</code>

                            <div class="actions">
                              <a data-tooltip title="@lang('admin::views.Copy file path')" class="copy-path icon icon-Files top"></a>
                              <a data-tooltip title="@lang('admin::views.Open image in new tab')" target="blank" href="{!! Atlantis\Helpers\Tools::getFilePath() . $media->phone_name !!}" class="icon icon-Export top"></a>
                              <a data-open="manualCrop" data-version="phone" data-tooltip title="@lang('admin::views.Recrop Manually')" class="icon icon-ExitFullScreen top show-for-large" aria-hidden="true"></a>
                            </div>
                          </td>
                        </tr>
                      @endif @if (!empty($media->thumbnail))
                        <tr>
                          <td>
                            Thumbnail
                          </td>
                          <td class="copy-path name">
                            <div class="text text-center">@lang('admin::views.Copied')</div>
                            <code class="path">{{ config('atlantis.user_media_upload') . $media->thumbnail }}</code>

                            <div class="actions">
                              <a data-tooltip title="@lang('admin::views.Copy file path')" class="copy-path icon icon-Files top"></a>
                              <a data-tooltip title="@lang('admin::views.Open image in new tab')" target="blank" href="{!! Atlantis\Helpers\Tools::getFilePath() . $media->thumbnail !!}" class="icon icon-Export top"></a>
                              <a data-open="manualCrop" data-version="thumbnail" data-tooltip title="@lang('admin::views.Recrop Manually')" class="icon icon-ExitFullScreen top show-for-large" aria-hidden="true"></a>
                            </div>
                          </td>
                        </tr>
                      @endif
                    </table>
                  </div>
                  <div class="columns large-5">
                    <div id="thumb">
                      <label for="description">@lang('admin::views.Thumbnail')</label>
                      @if (empty($media->thumbnail))
                        <a href="{!! $filePath . $media->original_filename !!}">{{ $media->original_filename }}</a>
                      @else
                        <img src="{!! $filePath . $media->thumbnail !!}">
                      @endif
                      <br> <br><a data-toggle="uploader" class="button alert">@lang('admin::views.Change File')</a>
                    </div>
                    <div id="uploader" class="hidden" data-toggler=".hidden">
                      <p>@lang('admin::views.plupload_notsupported')</p>
                    </div>
                  @if(trait_exists('\Module\Relativity\Traits\RelativityTrait'))
                  <div class="row">
                    <div class="column">
                      <br>
                      {!! \Module\Relativity\Controllers\RelativityController::theVocabulariesByslugs(['media'], 'Atlantis\Models\Media', $media->id) !!}
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
      <!-- MANUAL RECROP WINDOW -->
      <div class="reveal full crop" id="manualCrop" data-reveal>
        <div class="row">
          <div class="columns large-9">
            <h5>{{ $media->original_filename }}</h5>
          </div>
          <div class="columns large-9">
            <img style="max-width: 100%" class="original-image" data-src="{!! $filePath . $media->original_filename !!}">
            <button class="close-button" data-close aria-label="Close reveal" type="button">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="columns large-3">
            <label for="">@lang('admin::views.Preview') <span class="version-name"></span></label>
            <br>
            <div class="jcrop-thumb-replacer">
              <!-- preview thumb -->
            </div>
            {!! Form::open(['url' => 'admin/media/media-recrop-version/' . $media->id, 'data-abide' => '', 'novalidate'=> '', 'id'=> 'media-recropform']) !!}
            <input type="hidden" name="resize" value="{{$selected_resize}}" />
            <input type="hidden" name="version" value="" />
            <input type="hidden" name="cropData[x]" id="cropx" value="0" />
            <input type="hidden" name="cropData[y]" id="cropy" value="0" />
            <input type="hidden" name="cropData[width]" id="cropw" value="0" />
            <input type="hidden" name="cropData[height]" id="croph" value="0" />
            <button type="submit" href="javascript:void(0)" class="button alert">@lang('admin::views.Save')</button>
            <a data-close aria-label="Close reveal" class="button primary">@lang('admin::views.Close')</a>
            </form>
          </div>
        </div>
      </div>

    </footer>
  @endif
@stop

@section('js')
  @parent

  @if (!isset($invalid_item))

    @include('atlantis-admin::media.media-add-edit-script', array('action'=>'edit'))

  @endif

@stop