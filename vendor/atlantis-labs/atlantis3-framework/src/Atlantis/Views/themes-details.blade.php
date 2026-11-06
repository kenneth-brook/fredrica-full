@extends('atlantis-admin::admin-shell')

@section('title')
@lang('admin::views.Themes') | @lang('admin::views.A3 Administration') | {{ config('atlantis.site_name') }}
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
@if ($themeConfig == NULL)
<div class="callout alert">
  <h5>@lang('admin::views.This theme is not valid')</h5>
</div>
@else
<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">
        <h1 class="huge page-title">{{ $themeConfig['name'] }}</h1>
      </div>
    </div>
  </section>
  <section class="modules-list">
    <div class="row">
      <div class="columns small-12">        
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

        
        {{-- CREATE PATTERNS AND PAGES ERRORS --}}
        @if (isset($error_page))
        
        <div class="callout warning">
          @foreach($error_page as $e)
          @foreach($e as $k=>$message)
          <h6>{{ $message }}</h6>
          @endforeach
          @endforeach
        </div>
        @endif
        @if (isset($error_pattern))
        
        <div class="callout warning">
          @foreach($error_pattern as $e)
          @foreach($e as $k=>$message)
          <h6>{{ $message }} </h6>
          @endforeach
          @endforeach
        </div>
        @endif
        @if (isset($resource_created))

        <div class="callout success">
          <h3>@lang('admin::messages.Resources Created Succesfully')</h3>
          @foreach($resource_created as $k=>$e)
          <h6>{{ $e }}</h6>
          @endforeach
          
        </div>
        @endif
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <a href="#panel1" aria-selected="true">
              @lang('admin::views.Details')
            </a>
          </li>
          <li class="float-right list-filter">
            <a href="admin/themes" class="button hollow">@lang('admin::views.Themes list')</a>
          </li>
          @if(isset($theme['active']) && $theme['active'] && isset($themeConfig['create_patterns']) && isset($themeConfig['create_pages']))
          <li class="float-right list-filter">
            <a class="button alert" data-open="createThemeResource" >@lang('admin::views.Create related pages and patterns')</a>
          </li>
          @endif
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">
            <div class="row">
              <div class="columns large-12">  

                @if (isset($themeConfig['name']))
                <h3>{{ $themeConfig['name'] }}</h3>
                @else
                <h5>'name'  @lang('admin::views.not found in your theme\'s config file')</h5>
                @endif

              </div>
              <div class="columns large-4 ">
                @if (isset($themeConfig['screenshot']) && !empty($themeConfig['screenshot']))
                <img src="{!! $themeConfig['_theme_path'] . '/' . $themeConfig['screenshot'] !!}">                
                @endif
                


              </div>


              <div class="columns large-6 end">
                @if (isset($themeConfig['version']))
                <label for="">@lang('admin::views.Version')
                  <p>{{ $themeConfig['version'] }}</p>
                </label> 
                @endif
                
                @if (isset($themeConfig['author']))
                <label for="">@lang('admin::views.Author')
                  @if (isset($themeConfig['author_url']))
                  <p><a href="{{ $themeConfig['author_url'] }}" target="_blank">{{ $themeConfig['author'] }}</a></p>  
                  @else
                  <p>{{ $themeConfig['author'] }}</p>
                  @endif    
                </label>
                @endif
                @if (isset($themeConfig['description']))

                <label for="">@lang('admin::views.Description')
                  <p>{{ $themeConfig['description'] }}</p>
                </label>

                @endif

                @if (isset($themeConfig['pattern_outputs']))                  
                <label for="">@lang('admin::views.Patterns variables')
                  @foreach ($themeConfig['pattern_outputs'] as $var => $desc)
                  <p><span class="label secondary">{{ $var }}</span> : {{ $desc }}</p>
                  @endforeach                  
                </label>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<footer>
  <div class="row">
    <div class="columns">

      @if(isset($theme['active']) && $theme['active'] && isset($themeConfig['create_patterns']) && isset($themeConfig['create_pages']))
      {!! \Atlantis\Helpers\Modal::createThemeResource('createThemeResource', $themeConfig) !!}
    </li>
    @endif
  </div>
</div> 
</footer>
@endif
@stop