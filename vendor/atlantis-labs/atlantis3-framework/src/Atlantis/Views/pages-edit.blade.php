@extends('atlantis-admin::admin-shell')

@section('title')
  @lang('admin::views.Edit Page') | @lang('admin::views.A3 Administration') | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
  @parent
  {{-- Add scripts per template --}}
  {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/foundation-datepicker.min.js') !!}
  {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/tagsInput/jquery.tagsinput.min.js') !!}
@stop

@section('js')
    @parent
    @include('atlantis-admin::help-sections.language-swich-script')
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
            <h1 class="huge page-title">@lang('admin::views.Edit Page')</h1>
          </div>
        </div>
      </section>

      <section class="editscreen">
        {!! Form::open(['url' => 'admin/pages/edit/' . $oPage->id, 'data-abide' => '', 'novalidate'=> '', 'id' => 'edit-page-form']) !!}
        <div class="row">
          <div class="columns">
            @if ($oPage->active == 0)
              <div class="warning callout">
                <h5>@lang('admin::views.This is revision', ['id' => $oPage->version_id])</h5>
                <a href="admin/pages/make-active-version/{{ $oPage->id }}/{{ $oPage->version_id }}/{{ $oPage->language }}">@lang('admin::views.Make this version active')</a>
              </div>
            @endif
            @if (isset($msgInfo))
              <div class="callout warning">
                @foreach($msgInfo as $mInfo)
                  <h5>{{ $mInfo }}</h5>
                @endforeach
              </div>
            @endif
            @if (isset($msgSuccess))
              <div class="callout success">
                @foreach($msgSuccess as $mSuccess)
                  <h5>{{ $mSuccess }}</h5>
                @endforeach
              </div>
            @endif
            @if (isset($msgError))
              <div class="callout alert">
                @foreach($msgError as $mError)
                  <h5>{{ $mError }}</h5>
                @endforeach
              </div>
            @endif
            <div class="float-right">
              <div class="buttons">
                <a href="admin/pages" class="back button tiny top primary" title="@lang('admin::views.Go to Pages')"
                   data-tooltip>
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
              <li class="tabs-title is-active main has-icons">
                <!-- data-status: active, disabled or dev -->
                <a href="#panel1" aria-selected="true"
                   data-status="{{ $data_status }}">{{ $oPage->name }}</a>
                <span class="actions">
                          <a data-tooltip title="@lang('admin::views.Preview Page')" target="blank" href="{{ url('/') }}/{{ $oPage->language != config('atlantis.default_language') ? $oPage->language.'/' : '' }}{!! $oPage->url == '/' ? '' : $oPage->url !!}"
                             class="icon icon-Export top"></a>
                             <a data-open="clonePage" data-tooltip title="@lang('admin::views.Clone Page')" class="icon icon-Files top"></a>
                             <a data-open="deletePage" data-tooltip title="@lang('admin::views.Delete Page')" class="icon icon-Delete top"></a>
                         </span>
              </li>
              @if(count($admin_tabs))
                @php
                  $i = 1;
                @endphp
                @foreach($admin_tabs as $tab)
                  <li class="tabs-title"><a href="#panel{{ $i + 1 }}">{{ $tab['tab_name'] }}</a></li>
                  @php
                    $i++;
                  @endphp
                @endforeach
              @endif
              <li class="tabs-title"><a href="#panel{{ count($admin_tabs) + 2 }}">@lang('admin::views.Versions')</a></li>
            </ul>
            <div class="tabs-content" data-tabs-content="example-tabs">
              <div class="tabs-panel is-active" id="panel1">

                <div class="row">
                  <div class="columns large-9">
                    <div class="row">
                      <div class="columns medium-4">
                        @if ($errors->get('name'))
                          <label for="page_name" class="is-invalid-label">
                            <span class="form-error is-visible">{{ $errors->get('name')[0] }}</span>
                            <span class="icon icon-Help top" data-tooltip title="This is the name used to indentify the pattern in the CMS."></span>
                            {!! Form::input('text', 'name', old('name', $oPage->name), ['class' => 'is-invalid-input', 'id'=>'page_name', 'required'=>'required']) !!}
                          </label>
                        @else
                          <label for="page_name">@lang('admin::views.Page Name') <span class="form-error">@lang('admin::views.is required')</span>
                            <span class="icon icon-Help top" data-tooltip
                                  title="@lang('admin::views.page_name_tip')"></span>
                            {!! Form::input('text', 'name', old('name', $oPage->name), ['id'=>'page_name', 'required'=>'required']) !!}
                          </label>
                        @endif
                      </div>
                      <div class="columns medium-4">
                        <label for="seo_title">@lang('admin::views.Seo Title') <span
                                  class="form-error">@lang('admin::views.is required')</span>
                          <span class="icon icon-Help top" data-tooltip
                                title="@lang('admin::views.seo_title_tip')"></span>
                          {!! Form::input('text', 'seo_title', old('seo_title', $oPage->seo_title), []) !!}
                        </label>
                      </div>
                      <div class="columns medium-4">
                        @if ($errors->get('url'))
                          <label for="page_url" class="is-invalid-label"><span
                                    class="form-error is-visible">{{ $errors->get('url')[0] }}</span>
                            <span class="icon icon-Help top" data-tooltip
                                  title="@lang('admin::views.Page_URL_tip')"></span>
                            {!! Form::input('text', 'url', old('url', $oPage->url), ['class' => 'is-invalid-input', 'id'=>'page_url', 'required'=>'required']) !!}
                          </label>
                        @else
                          <label for="page_url">@lang('admin::views.Page URL') <span
                                    class="form-error">@lang('admin::views.is required')</span>
                            <span class="icon icon-Help top" data-tooltip
                                  title="@lang('admin::views.Page_URL_tip')"></span>
                                  <div class="input-group">
                                    @if($oPage->language != config('atlantis.default_language'))
                                      <span class="input-group-label">{{ $oPage->language }}/</span>
                                    @endif
                                    {!! Form::input('text', 'url', old('url', $oPage->url), ['id'=>'page_url', 'required'=>'required', 'class' => 'input-group-field']) !!}
                                  </div>
                          </label>
                        @endif
                      </div>
                      <div class="columns medium-4">
                        <label for="">
                          @lang('admin::views.Meta Keywords')
                          <small id="meta_keywords_info">255</small>
                          <small> @lang('admin::views.characters left')</small>
                          <span class="icon icon-Help top" data-tooltip
                                title="@lang('admin::views.keywords_tip')"></span>
                          {!! Form::input('text', 'meta_keywords', old('meta_keywords', $oPage->meta_keywords), ['id'=>'meta_keywords']) !!}
                        </label>
                      </div>
                      <div class="columns medium-4">
                        <label for="">
                          @lang('admin::views.Meta Description')
                          <small id="meta_description_info">255</small>
                          <small> @lang('admin::views.characters left')</small>
                          <span class="icon icon-Help top" data-tooltip
                                title="@lang('admin::views.metadescription_tip')"></span>
                          {!! Form::input('text', 'meta_description', old('meta_description', $oPage->meta_description), ['id'=>'meta_description']) !!}
                        </label>
                      </div>
                      <div class="columns medium-4">
                        <label for="">@lang('admin::views.Page Tags')
                          {!! Form::input('text', 'tags', old('tags', $tags), ['class' => 'inputtags']) !!}
                        </label>
                      </div>
                      <div class="columns medium-4">
                        @if ($errors->get('template'))
                          <label for="page_template" class="is-invalid-label"><span
                                    class="form-error is-visible">{{ $errors->get('template')[0] }}</span>
                            <span class="icon icon-Help top" data-tooltip
                                  title="Which display template the page should use."></span>
                            {!! Form::select('template', $aTemplates, $oPage->template, ['class' => 'is-invalid-input', 'id' => 'page_template', 'required'=>'required']) !!}
                          </label>
                        @else
                          <label for="page_template">@lang('admin::views.Page Template') <span class="form-error">@lang('admin::views.is required')</span>
                            <span class="icon icon-Help top" data-tooltip
                                  title="@lang('admin::views.template_tip')"></span>
                            {!! Form::select('template', $aTemplates, $oPage->template, ['id' => 'page_template', 'required'=>'required']) !!}
                          </label>
                        @endif
                      </div>
                      <div class="columns medium-3 xxlarge-2">
                        <label for="categories_id">@lang('admin::views.Page Category')

                          <select id="categories_id" name="categories_id">
                            @foreach ($aCategories as $cat_key => $cat_val)
                              @if ($cat_key == $oPage->categories_id)
                                <option data-action="{!! $cat_val['category_action'] !!}"
                                        data-string="{!! $cat_val['category_string'] !!}"
                                        data-template="{!! $cat_val['category_view'] !!}"
                                        value="{!! $cat_key !!}"
                                        selected="selected">{!! $cat_val['category_name'] !!}</option>
                              @else
                                <option data-action="{!! $cat_val['category_action'] !!}"
                                        data-string="{!! $cat_val['category_string'] !!}"
                                        data-template="{!! $cat_val['category_view'] !!}"
                                        value="{!! $cat_key !!}">{!! $cat_val['category_name'] !!}</option>
                              @endif
                            @endforeach
                          </select>

                        </label>
                      </div>
                      <div class="columns medium-3 xxlarge-2">
                        @if ($errors->get('path'))
                          <label for="path" class="is-invalid-label"><span
                                    class="form-error is-visible">{{ $errors->get('path')[0] }}</span>
                            {!! Form::select('path', $aParent, $path, ['id' => 'path']) !!}
                          </label>
                        @else
                          <label for="path">@lang('admin::views.Parent Document')
                            {!! Form::select('path', $aParent, $path, ['id' => 'path']) !!}
                          </label>
                        @endif
                      </div>
                      <div class="columns medium-3 xxlarge-2">
                        <label for="">@lang('admin::views.Page Status')
                          <span class="icon icon-Help top" data-tooltip
                                title="@lang('admin::views.pagestatus_tip')"></span>
                          {!! Form::select('status', $aStatuses, $oPage->status, ['id' => '']) !!}
                        </label>
                      </div>
                      <div class="columns medium-3 xxlarge-2">
                        <label for="">
                          @lang('admin::views.Language')
                          <span class="icon icon-Help top" data-tooltip
                                title="@lang('admin::views.pagelang_tip')"></span>
                                  <select style="pointer-events: none;" name="language" id="select-language">
                                    @foreach($aLang as $k => $l)

                                    <option {{ $oPage->language == $k ? 'selected'  : '' }} data-active-version="{{ $active_versions[$k] or 0 }}" value="{{ $k }}">{{ trans('admin::languages.'.$l) }}</option>

                                    @endforeach
                                  </select>
                              </label>
                      </div>
                      <div class="columns end">
                        {!! \Editor::set('page_body', $oPage->page_body, ['rows' => 35, 'class' => '']) !!}
                      </div>

                    </div>
                  </div>
                  <div class="columns large-3">
                    <aside>
                      <ul class="accordion" data-accordion>
                        <li class="accordion-item is-active" data-accordion-item>
                          <a href="#" class="accordion-title">@lang('admin::views.Page Patterns')
                            ({{ $aPatterns['count'] }})</a>
                          <div class="accordion-content" data-tab-content>

                            <label>@lang('admin::views.Page specific')</label>
                            <ul class="page-patterns-list specific">

                              @if (isset($aPatterns['specific']))
                                @foreach ($aPatterns['specific'] as $specific)
                                  <li>
                                    <a data-status="{{ $specific['status'] != 1 ? 'disabled' : 'active'}}"
                                       href="admin/patterns/edit/{{ $specific['id'] }}">{{ $specific['name'] }}</a>
                                  <!-- <input data-tooltip title="Remove pattern from this page" type="submit" name="_remove_pattern" value="{{ $specific['id'] }}" id="update-btn" class="rmv-pattern fa fa-times top"> -->
                                    <!-- <a data-tooltip title="Remove pattern from this page" class="rmv-pattern fa fa-times top"></a> -->
                                    <a data-open="removePattern{{ $specific['id'] }}"
                                       data-tooltip
                                       title="@lang('admin::views.Remove pattern from this page')"
                                       class="rmv-pattern fa fa-times top"></a>
                                    {{-- \Atlantis\Helpers\Modal::removePattern('removePattern' . $specific['id'], $specific['name'], $specific['id']) --}}
                                  </li>
                                @endforeach
                              @endif
                            </ul>

                            <label>@lang('admin::views.Common')</label>
                            <ul class="page-patterns-list common">
                              @if (isset($aPatterns['common']))
                                @foreach ($aPatterns['common'] as $common)
                                  <li>
                                    <a data-status="{{ $common['status'] != 1 ? 'disabled' : 'active'}}"
                                       href="admin/patterns/edit/{{ $common['id'] }}">{{ $common['name'] }}</a>
                                  <!-- <input data-tooltip title="Remove pattern from this page" type="submit" name="_remove_pattern" value="{{ $common['id'] }}" id="update-btn" class="rmv-pattern fa fa-times top"> -->
                                    <a data-open="removePattern{{ $common['id'] }}"
                                       data-tooltip
                                       title="@lang('admin::views.Remove pattern from this page')"
                                       class="rmv-pattern fa fa-times top"></a>
                                    {{-- \Atlantis\Helpers\Modal::removePattern('removePattern' . $common['id'], $common['name'], $common['id']) --}}
                                  </li>
                                @endforeach
                              @endif
                            </ul>

                            <label>@lang('admin::views.Excluded')</label>
                            <ul class="page-patterns-list excluded ">
                              @if (isset($aPatterns['excluded']))
                                @foreach ($aPatterns['excluded'] as $excluded)
                                  <li>
                                    <a href="admin/patterns/edit/{{ $excluded['id'] }}">{{ $excluded['name'] }}</a>
                                  <!-- <input data-tooltip title="Remove pattern from this page" type="submit" name="_remove_pattern" value="{{ $excluded['id'] }}" id="update-btn" class="rmv-pattern fa fa-times top"> -->
                                    <!-- <a data-tooltip title="Remove pattern from this page" class="rmv-pattern fa fa-times top"></a> -->
                                    <a data-status="{{ $excluded['status'] != 1 ? 'disabled' : 'active'}}"
                                       data-open="removePattern{{ $excluded['id'] }}"
                                       data-tooltip title="@lang('admin::views.Add pattern to this page')"
                                       class="rmv-pattern fa fa-times top"></a>
                                    {{-- \Atlantis\Helpers\Modal::removePattern('removePattern' . $excluded['id'], $excluded['name'], $excluded['id']) --}}
                                  </li>
                                @endforeach
                              @endif
                            </ul>
                            @if (count($included_modules) > 0)
                              <hr>
                              <label for="">Included Modules</label>
                              <table>
                                <thead>
                                <tr>
                                  <th>Module</th>
                                  <th>Tag</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($included_modules as $k => $mod)
                                  <tr>
                                    <td>{{ empty($mod['module']) ? '-' : $mod['module']->name }}</td>
                                    <td>{{ $k }}</td>
                                  </tr>
                                @endforeach
                                </tbody>
                              </table>
                            @endif
                          </div>
                        </li>
                        <li class="accordion-item" data-accordion-item>
                          <a href="#" class="accordion-title">@lang('admin::views.Cache')</a>
                          <div class="accordion-content" data-tab-content>
                            <p>@lang('admin::views.Include this page in site cache index')</p>
                            <div class="switch tiny">
                              {!! Form::checkbox('cache', 1, $oPage->cache, ['class' => 'switch-input', 'id' => 'cacheSwitch']) !!}
                              <label class="switch-paddle" for="cacheSwitch">
                                                            <span class="show-for-sr">
                                                              @lang('admin::views.Cache Enabled')
                                                          </span>
                              </label>
                            </div>
                          </div>
                        </li>
                        <li class="accordion-item" data-accordion-item>
                          <a href="#" class="accordion-title">@lang('admin::views.Page Specific Styles')
                            ({{ $styles_count }})</a>
                          <div class="accordion-content" data-tab-content>
                            <p>@lang('admin::views.Enter declarations one per line').
                              @lang('admin::views.Shift+Enter for new row').
                              @lang('admin::views.These styles will be loaded only for this page').
                              (assets/css/test.css)</p>
                            {!! Form::textarea('styles', old('styles', $oPage->styles), ['rows' => 10, 'cols' => '30', 'id' => '']) !!}
                          </div>
                        </li>
                        <li class="accordion-item" data-accordion-item>
                          <a href="#" class="accordion-title">@lang('admin::views.Page Specific Scripts')
                            ({{ $scripts_count }})</a>
                          <div class="accordion-content" data-tab-content>
                            <p>@lang('admin::views.Enter declarations one per line').
                              @lang('admin::views.Shift+Enter for new row').
                              @lang('admin::views.These scripts will be loaded only for this page').
                              (assets/js/test.js)</p>
                            {!! Form::textarea('scripts', old('scripts', $oPage->scripts), ['rows' => 10, 'cols' => '30', 'id' => '']) !!}
                          </div>
                        </li>
                        @if ($errors->get('start_date') || $errors->get('end_date'))
                          <li class="accordion-item is-active" data-accordion-item>
                            <a href="#" class="accordion-title redtext">@lang('admin::views.Expiration')
                              <small class="form-error is-visible">@lang('admin::views.Invalid field')</small>
                            </a>
                        @else
                          <li class="accordion-item" data-accordion-item>
                            <a href="#" class="accordion-title">@lang('admin::views.Expiration')</a>
                            @endif
                            <div class="accordion-content" data-tab-content>
                              <p>@lang('admin::views.expiration_tip')</p>
                              <div class="row">
                                <div class="columns small-6">
                                  @if ($errors->get('start_date'))
                                    <label for="start_date"
                                           class="is-invalid-label">@lang('admin::views.expiration_From')</label>
                                    <span class="fa fa-calendar dtp-wrapper">
                                                                                  {!! Form::input('text', 'start_date', old('start_date', $start_date), ['class' => 'dtp is-invalid-input', 'id'=>'start_date']) !!}
                                                                              </span>
                                    <span class="form-error is-visible">{{ $errors->get('start_date')[0] }}</span>
                                  @else
                                    <label for="start_date">@lang('admin::views.expiration_From')</label>
                                    <span class="fa fa-calendar dtp-wrapper">
                                                                                  {!! Form::input('text', 'start_date', old('start_date', $start_date), ['class' => 'dtp', 'id'=>'start_date']) !!}
                                                                              </span>
                                  @endif
                                </div>
                                <div class="columns small-6">
                                  @if ($errors->get('end_date'))
                                    <label for="end_date" class="is-invalid-label">@lang('admin::views.expiration_To')</label>
                                    <span class="fa fa-calendar dtp-wrapper">
                                                                              {!! Form::input('text', 'end_date', old('end_date', $end_date), ['class' => 'dtp is-invalid-input', 'id'=>'end_date']) !!}
                                                                          </span>
                                    <span class="form-error is-visible">{{ $errors->get('end_date')[0] }}</span>
                                  @else
                                    <label for="end_date">@lang('admin::views.expiration_To')</label>
                                    <span class="fa fa-calendar dtp-wrapper">
                                                                              {!! Form::input('text', 'end_date', old('end_date', $end_date), ['class' => 'dtp', 'id'=>'end_date']) !!}
                                                                          </span>
                                  @endif
                                </div>
                                <br>
                              </div>
                            </div>
                          </li>
                          <li class="accordion-item" data-accordion-item>
                            <a href="#" class="accordion-title">SSL</a>
                            <div class="accordion-content" data-tab-content>
                              <p> @lang('admin::views.Enable SSL for this page')</p>
                              <div class="switch tiny">
                                {!! Form::checkbox('is_ssl', 1, $oPage->is_ssl, ['class' => 'switch-input', 'id' => 'sslSwitch']) !!}
                                <label class="switch-paddle" for="sslSwitch">
                                                                        <span class="show-for-sr">
                                                                          @lang('admin::views.SSL Enabled')
                                                                      </span>
                                </label>
                              </div>
                            </div>
                          </li>
                          <li class="accordion-item" data-accordion-item>
                            <a href="#" class="accordion-title">@lang('admin::views.Related Title')</a>
                            <div class="accordion-content" data-tab-content>
                              {!! Form::input('text', 'related_title', old('related_title', $oPage->related_title), []) !!}
                            </div>
                          </li>
                          <li class="accordion-item" data-accordion-item>
                           <a href="#" class="accordion-title">Related Image</a>
                           <div class="accordion-content" data-tab-content>
                            <div id="preview_thumb_id">
                              @if ($related_image != NULL) 
                              <img src="{!! $related_image->thumbnail !!}">
                              @endif
                              <br><br>
                            </div>
                            <button role="button" data-open="imagePreview" class="button">Browse</button>
                            <button role="button" href="#"  class="remove-thumb alert button">Remove</button>
                          </div>

                          {!! Form::input('hidden', 'preview_thumb_id', old('preview_thumb_id', $oPage->preview_thumb_id), []) !!}
                          </li>

                          <li class="accordion-item" data-accordion-item>
                            <a href="#" class="accordion-title">@lang('admin::views.Excerpt')</a>
                            <div class="accordion-content" data-tab-content>
                              {!! Form::textarea('excerpt', old('excerpt', $oPage->excerpt), ['rows' => 10, 'cols' => '30', 'id' => '']) !!}
                            </div>
                          </li>
                          <li class="accordion-item" data-accordion-item>
                            <a href="#" class="accordion-title">@lang('admin::views.Notes')</a>
                            <div class="accordion-content" data-tab-content>
                              {!! Form::textarea('notes', old('notes', $oPage->notes), ['rows' => 10, 'cols' => '30', 'id' => '']) !!}
                            </div>
                          </li>
                          <li class="accordion-item" data-accordion-item>
                            <a href="#" class="accordion-title">@lang('admin::views.Author')</a>
                            <div class="accordion-content" data-tab-content>
                              {!! Form::input('text', 'author', old('author', $oPage->author), []) !!}
                            </div>
                          </li>
                          <li class="accordion-item" data-accordion-item>
                            <a href="#" class="accordion-title">@lang('admin::views.Page Protected')</a>
                            <div class="accordion-content" data-tab-content>
                              <p>@lang('admin::views.Enable page protection')</p>
                              <div class="switch tiny">
                                {!! Form::checkbox('protected', 1, $oPage->protected, ['class' => 'switch-input', 'id' => 'protSwitch']) !!}
                                <label class="switch-paddle" for="protSwitch">
                                                            <span class="show-for-sr">
                                                              @lang('admin::views.Protection Enabled')
                                                          </span>
                                </label>
                              </div>
                            </div>
                          </li>


                          @if ($errors->has('canonical_url'))
                            <li class="accordion-item is-active" data-accordion-item>
                              <a href="#" class="accordion-title redtext">@lang('admin::views.Canonical URL')</a>
                              <div class="accordion-content" data-tab-content>
                                <p>
                                  <small class="form-error is-visible">{{ $errors->get('canonical_url')[0] }}</small>
                                </p>
                          @else
                            <li class="accordion-item" data-accordion-item>
                              <a href="#" class="accordion-title">@lang('admin::views.Canonical URL')</a>
                              <div class="accordion-content" data-tab-content>
                                @endif
                                <p>@lang('admin::views.canonical_tip')</p>
                                {!! Form::input('text', 'canonical_url', old('canonical_url', $oPage->canonical_url), ['class' => $errors->has('canonical_url') ? 'is-invalid-input' : NULL]) !!}
                              </div>
                            </li>

                      </ul>

                      <!-- modals remove pattern -->
                    @if (isset($aPatterns['specific']))
                      @foreach ($aPatterns['specific'] as $specific)
                        {!! \Atlantis\Helpers\Modal::removePattern('removePattern' . $specific['id'], $specific['name'], $specific['id'], 'specific', $oPage) !!}
                      @endforeach
                    @endif

                    @if (isset($aPatterns['common']))
                      @foreach ($aPatterns['common'] as $common)
                        {!! \Atlantis\Helpers\Modal::removePattern('removePattern' . $common['id'], $common['name'], $common['id'], 'common', $oPage) !!}
                      @endforeach
                    @endif

                    @if (isset($aPatterns['excluded']))
                      @foreach ($aPatterns['excluded'] as $excluded)
                        {!! \Atlantis\Helpers\Modal::removePattern('removePattern' . $excluded['id'], $excluded['name'], $excluded['id'], 'excluded', $oPage) !!}
                      @endforeach
                    @endif
                    <!-- end modals remove pattern -->

                    </aside>
                  </div>
                </div>
              </div>
              @if(count($admin_tabs))
                @php
                  $i = 1;
                @endphp
                @foreach($admin_tabs as $tab)
                  <div class="tabs-panel" id="panel{{ $i + 1 }}">
                    {!! $tab['tab_content'] !!}
                  </div>
                  @php
                    $i++;
                  @endphp
                @endforeach
              @endif

              {!! Form::close() !!}
              <div class="tabs-panel" id="panel{{ count($admin_tabs) + 2 }}">
                {!! DataTable::set(\Atlantis\Controllers\Admin\PageVersionsDataTable::class, ['page_id' => $oPage->id, 'lang' => $oPage->language]) !!}
              </div>
            </div>
          </div>
        </div>
      </section>
      
      @include('atlantis-admin::help-sections/page-sidebar')
      
    </main>

    <footer>
      {{-- @include('atlantis-admin::help-sections/pages') --}}
      <div class="row">
        <div class="columns">
        </div>
      </div>
      {!! \Atlantis\Helpers\Modal::set('deletePage', trans('admin::views.Delete Page'), trans('admin::views.Are you sure you want to delete' , ['object' => $oPage->name]), trans('admin::views.Delete'), 'admin/pages/delete-page/' . $oPage->id) !!}
      {!! \Atlantis\Helpers\Modal::setClonePage('clonePage', 'admin/pages/clone-page/' . $oPage->id, $oPage->name . '-clone', $oPage->url) !!}
      {!! \Atlantis\Helpers\Modal::pagePreview('imagePreview', $oPage->preview_thumb_id) !!}
    </footer>
  @endif
@stop