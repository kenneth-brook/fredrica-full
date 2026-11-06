@extends('atlantis-admin::admin-shell')

@section('title')
@lang('admin::views.Patterns') | @lang('admin::views.A3 Administration') | {{ config('atlantis.site_name') }}
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
        <h1 class="huge page-title">@lang('admin::views.Patterns')</h1>
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
      </div>
    </div>
  </section>
  <section class="pages-list editscreen">
    <div class="row">
      <div class="columns">
        <div class="float-right">
          <div class="buttons">
               <a id="save-close-btn" class="alert button" href="admin/patterns/add">@lang('admin::views.New Pattern')</a>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <a href="#panel1" aria-selected="true">
              @lang('admin::views.All Patterns')
            </a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">
            {!! DataTable::set(\Atlantis\Controllers\Admin\PatternsDataTable::class) !!}
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<footer>
  {{-- @include('atlantis-admin::help-sections/patterns') --}}
  <div class="row">
    <div class="columns">
    </div>
  </div>
</footer>
@stop