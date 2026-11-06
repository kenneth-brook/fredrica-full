@extends('atlantis-admin::admin-shell')

@section('title')
@lang('admin::views.Config') | @lang('admin::views.A3 Administration') | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
@parent
{{-- Add scripts per template --}}
{{-- <script src="http://a3.angel.dev.gentecsys.net/media/js/vendor/jquery.js"></script> --}}
@stop

@section('styles')
@parent
{{-- Add styles per template --}}
@stop

@section('content')
<main>
    <section class="greeting">
        <div class="row">
            <div class="columns ">
                <h1 class="huge page-title">@lang('admin::views.System Config')</h1>
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
        {!! Form::open(['url' => 'admin/config/update', 'data-abide' => '', 'novalidate'=> '']) !!}
        <div class="row">
            <div class="columns">
                <div class="float-right">
                    <div class="buttons">
                        <input type="submit" name="_update" value="@lang('admin::views.Update')" id="update-btn" class="alert button">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12">
                <ul class="tabs" data-tabs id="example-tabs">
                    <li class="tabs-title is-active">
                        <a href="#panel1" aria-selected="true">
                            @lang('admin::views.System Configuration') - @lang('admin::views.Framework Version', ['version' => $framework_version])
                        </a>
                    </li>
                </ul>
                <div class="tabs-content" data-tabs-content="example-tabs">
                    <div class="tabs-panel is-active" id="panel1">
                        <div class="row">
                            <div class="columns large-9">
                                <div class="row">
                                    <div class="columns medium-6">
                                        <label for="site_name">@lang('admin::views.Site Name')
                                            <span class="form-error">@lang('admin::views.is required')</span>
                                            <span class="icon icon-Help top" data-tooltip title="@lang('admin::views.Application / SiteName')"></span>
                                            {!! Form::input('text', 'site_name', old('site_name', $site_name), ['id'=>'site_name', 'required'=>'required']) !!}
                                        </label>
                                        <div class="switch tiny">
                                            <label for="include_title">@lang('admin::views.Include site name in title field')
                                                <span class="icon icon-Help top" data-tooltip title="@lang('admin::views.site_name_tip')"></span>
                                            </label>
                                            {!! Form::checkbox('include_title', TRUE, $include_title, ['class' => 'switch-input', 'id' => 'include_title']) !!}
                                            <label class="switch-paddle" for="include_title">
                                            </label>
                                        </div>
                                        <label for="domain_name">@lang('admin::views.Domain Name')
                                            <span class="icon icon-Help top" data-tooltip title="@lang('admin::views.Domain name')"></span>
                                            {!! Form::input('text', 'domain_name', old('domain_name', $domain_name), ['id'=>'domain_name']) !!}
                                        </label>
                                        <label for="meta_keywords">@lang('admin::views.Default Keywords')
                                            <small id="meta_keywords_info">255</small>
                                            <small> @lang('admin::views.characters left')</small>                      
                                            {!! Form::input('text', 'default_meta_keywords', old('default_meta_keywords', $default_meta_keywords), ['id'=>'meta_keywords']) !!}
                                        </label>                    
                                        <label for="meta_description">@lang('admin::views.Default Description')
                                            <span class="form-error">@lang('admin::views.is required')</span>
                                            <small id="meta_description_info">255</small>
                                            <small> @lang('admin::views.characters left')</small>                     
                                            {!! Form::input('text', 'default_meta_description', old('default_meta_description', $default_meta_description), ['id'=>'meta_description', 'required'=>'required']) !!}
                                        </label>
                                        <label for="frontend_shell_view">@lang('admin::views.The Front end Shell View')
                                            <span class="icon icon-Help top" data-tooltip title="@lang('admin::views.Shell_tip')">
                                            </span>
                                            {!! Form::input('text', 'frontend_shell_view', old('frontend_shell_view', $frontend_shell_view), ['id'=>'frontend_shell_view']) !!}
                                        </label>
                                        <label for="admin_items_per_page">@lang('admin::views.Items per page on the admin screens')
                                            {!! Form::input('text', 'admin_items_per_page', old('admin_items_per_page', $admin_items_per_page), ['id'=>'admin_items_per_page']) !!}
                                        </label>
                                        <label for="default_language">
                                            <i class="fa fa-globe"></i>
                                            @lang('admin::views.Default Language')

                                            <span class="icon icon-Help top" data-tooltip title="@lang('admin::views.Front-end default language')"></span>

                                            <select name="default_language" id="default_language">
                                                @foreach ($aLang as $k => $l)
                                                <option {{ $default_language == $k ? 'selected' : ''  }} value="{{ $k }}">{{ trans('admin::languages-native.'.$l) }}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                        <div class="row">
                                            <div class="column large-3">
                                                <div class="switch tiny">
                                                    <label for="cache">@lang('admin::views.Enable cache')</label>
                                                    {!! Form::checkbox('cache', TRUE, $cache, ['class' => 'switch-input', 'id' => 'cache']) !!}
                                                    <label class="switch-paddle" for="cache">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="column large-8">
                                                <label for="cache_lifetime">@lang('admin::views.Cache lifetime (seconds)')
                                                    {!! Form::input('text', 'cache_lifetime', old('cache_lifetime', $cache_lifetime), ['id'=>'cache_lifetime']) !!}
                                                </label>
                                                
                                            </div>
                                        </div>
                                        <div class="switch tiny">
                                            <label for="show_shortcut_bar">@lang('admin::views.Show shortcut bar')</label>
                                            {!! Form::checkbox('show_shortcut_bar', TRUE, $show_shortcut_bar, ['class' => 'switch-input', 'id' => 'show_shortcut_bar']) !!}
                                            <label class="switch-paddle" for="show_shortcut_bar">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="columns medium-6">
                                        <label for="allowed_max_filesize">@lang('admin::views.Allowed max file size for upload in MB')
                                            {!! Form::input('text', 'allowed_max_filesize', old('allowed_max_filesize', $allowed_max_filesize), ['id'=>'allowed_max_filesize']) !!}
                                        </label>
                                        <label for="user_media_upload">@lang('admin::views.Media upload directory')
                                            {!! Form::input('text', 'user_media_upload', old('user_media_upload', $user_media_upload), ['id'=>'user_media_upload']) !!}
                                        </label>

                                        <label for="allowed_image_extensions">@lang('admin::views.Allowed image extensions')
                                            <span class="icon icon-Help top" data-tooltip title="For example: png, jpeg"></span>
                                            {!! Form::input('text', 'allowed_image_extensions', old('allowed_image_extensions', $allowed_image_extensions), ['id'=>'allowed_image_extensions']) !!}
                                        </label>
                                        <label for="allowed_others_extensions">@lang('admin::views.Allowed others extensions')
                                            <span class="icon icon-Help top" data-tooltip title="@lang('admin::views.For example: pdf, txt')"></span>
                                            {!! Form::input('text', 'allowed_others_extensions', old('allowed_others_extensions', $allowed_others_extensions), ['id'=>'allowed_others_extensions']) !!}
                                        </label>
                                        <label for="static_images">@lang('admin::views.Static Images')
                                            <span class="icon icon-Help top" data-tooltip title="@lang('admin::views.static_resize_tip')"></span>
                                            {!! Form::textarea('static_images', old('static_images', $static_images), ['rows' => 4, 'cols' => '30', 'id' => 'static_images']) !!}
                                        </label>
                                        <label for="responsive_images">@lang('admin::views.Responsive Images')
                                            <span class="icon icon-Help top" data-tooltip title="@lang('admin::views.responsove_resize_tip')"></span>
                                            {!! Form::textarea('responsive_images', old('responsive_images', $responsive_images), ['rows' => 4, 'cols' => '30', 'id' => 'responsive_images']) !!}
                                        </label>
                                        <div class="row">
                                            <div class="column medium-6">
                                                <div class="switch tiny">
                                                    <label>@lang('admin::views.use smart crop')</label>
                                                    {!! Form::checkbox('use_smart_crop', 1,  $use_smart_crop, ['class' => 'switch-input', 'id' => 'use_smart_crop']) !!}
                                                    <label data-toggle="facedetection" class="switch-paddle" for="use_smart_crop">
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <div id="facedetection" class="{{!$use_smart_crop ? 'hidden' : '' }} column medium-6" data-toggler=".hidden">
                                                <div class="switch tiny">
                                                    <label>Use face detection
                                                    <span class="icon icon-Help top" data-tooltip title="@lang('admin::views.Smart_crop_tip')"></span>
                                                    </label>
                                                    {!! Form::checkbox('use_facedetection', 1, $use_facedetection, ['class' => 'switch-input', 'id' => 'use_facedetection']) !!}
                                                    <label class="switch-paddle" for="use_facedetection">
                                                    </label>
                                                </div>    
                                            </div>

                                        </div>
                                        <label for="responsive_images">@lang('admin::views.Responsive Breakpoint sizes')
                                            <span class="icon icon-Help top" data-tooltip title="@lang('admin::views.Breakpoint_tip')"></span>
                                            @if ($responsive_breakpoints && strpos($responsive_breakpoints, '/') > -1 )
                                            <i class="fa fa-desktop" aria-hidden="true"></i> > 
                                            {{explode('/', $responsive_breakpoints)[0]}} > 
                                            <i class="fa fa-laptop " aria-hidden="true"></i> >
                                            {{explode('/', $responsive_breakpoints)[1]}} > 
                                            <i class="fa fa-tablet" aria-hidden="true"></i>
                                            @endif
                                            {!! Form::input('text', 'responsive_breakpoints', old('responsive_breakpoints', $responsive_breakpoints), ['rows' => 4, 'cols' => '30', 'id' => 'responsive_breakpoints']) !!}

                                        </label> 
                                    </div>
                                </div>
                            </div>
                            <div class="columns large-3">
                                <ul class="accordion" data-accordion>
                                    <li class="accordion-item is-active" data-accordion-item>
                                        <a href="#" class="accordion-title">@lang('admin::views.Default Styles')</a>
                                        <div class="accordion-content" data-tab-content>
                                            {!! Form::textarea('default_styles', old('default_styles', $default_styles), ['rows' => 4, 'cols' => '30', 'id' => 'default_styles']) !!}
                                        </div>
                                    </li>
                                    <li class="accordion-item" data-accordion-item>
                                        <a href="#" class="accordion-title">@lang('admin::views.Default Scripts')</a>
                                        <div class="accordion-content" data-tab-content>
                                            {!! Form::textarea('default_scripts', old('default_scripts', $default_scripts), ['rows' => 4, 'cols' => '30', 'id' => 'default_scripts']) !!}
                                        </div>
                                    </li>
                                    <li class="accordion-item" data-accordion-item>
                                        <a href="#" class="accordion-title">@lang('admin::views.Excluded Scripts')</a>
                                        <div class="accordion-content" data-tab-content>
                                            {!! Form::textarea('excluded_scripts', old('excluded_scripts', $excluded_scripts), ['rows' => 4, 'cols' => '30', 'id' => 'excluded_scripts']) !!}
                                        </div>
                                    </li>
                                    <li class="accordion-item" data-accordion-item>
                                        <a href="#" class="accordion-title">@lang('admin::views.Default 404 view')</a>
                                        <div class="accordion-content" data-tab-content>
                                            {!! Form::input('text', 'default_404_view', old('default_404_view', $default_404_view), ['id'=>'default_404_view']) !!}
                                        </div>
                                    </li>
                                    <li class="accordion-item" data-accordion-item>
                                        <a href="#" class="accordion-title">@lang('admin::views.Amazon S3 and CloudFront')</a>
                                        <div class="accordion-content" data-tab-content>
                                            <p><i>@lang('admin::views.CDN_tip')</i></p>

                                            <p class="help-text">@lang('admin::views.S3 bucket url')</p>
                                            {!! Form::input('text', 'amazon_s3_url', old('amazon_s3_url', $amazon_s3_url), ['id'=>'cdn']) !!}

                                            <p class="help-text">@lang('admin::views.CloudFront url')</p>
                                            {!! Form::input('text', 'amazon_cloudfront_url', old('amazon_cloudfront_url', $amazon_cloudfront_url), ['id'=>'cdn']) !!}
                                            <p>@lang('admin::views.Use S3 for file storage')</p>
                                            <div class="switch tiny">
                                                {!! Form::checkbox('use_amazon_s3', 1, $use_amazon_s3, ['class' => 'switch-input', 'id' => 's3Switch']) !!}
                                                <label class="switch-paddle" for="s3Switch">
                                                </label>
                                            </div>
                                            <p>@lang('admin::views.Use CloudFront as CDN')</p>
                                            <div class="switch tiny">
                                                {!! Form::checkbox('use_amazon_cdn', 1, $use_amazon_cdn, ['class' => 'switch-input', 'id' => 's3CDNSwitch']) !!}
                                                <label class="switch-paddle" for="s3CDNSwitch">
                                                </label>
                                            </div>
                                            <p>@lang('admin::views.Delete local file after upload')</p>
                                            <div class="switch tiny">
                                                {!! Form::checkbox('delete_local_file', 1, $delete_local_file, ['class' => 'switch-input', 'id' => 's3DeleteSwitch']) !!}
                                                <label class="switch-paddle" for="s3DeleteSwitch">
                                                </label>
                                            </div>
                                            <a data-open="syncFiles" class="alert button">@lang('admin::views.Sync Files')</a>
                                            <a data-open="invalidateFiles" class="alert button">@lang('admin::views.Invalidate Files')</a>
                                        </div>
                                    </li>
                                </ul>
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
    {!! \Atlantis\Helpers\Modal::syncFiles('syncFiles') !!}
    {!! \Atlantis\Helpers\Modal::invalidateFiles('invalidateFiles') !!}
</footer>
@stop