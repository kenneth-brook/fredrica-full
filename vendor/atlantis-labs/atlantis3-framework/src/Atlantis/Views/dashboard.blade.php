@extends('atlantis-admin::admin-shell')
@section('title')
@lang('admin::views.Dashboard') | A3 Administration | {{ config('atlantis.site_name') }}
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
        <h1 class="huge greeting">@lang('admin::views.Welcome', ['name'=> auth()->user()->name ])</h1>
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
  <section class="box">
    <div class="row">
        <div class="columns large-12">
          @lang('admin::views.Framework Version', ['version' => $currentVersion])
          @if ($currentVersion < $lastPublishedVersion['version'] )
          <br><a class="button small alert" href="{{ $lastPublishedVersion['source']['url'] }}" target="_blank">
            @lang('admin::views.new version available') {{ $lastPublishedVersion['version']}}
          </a>
          @endif
        </div>
        <div class="columns large-12">
        <div style="height: 43px;"></div>
        <div class="search-container">
          <ul class="tabs" data-tabs id="example-tabs">
            <li class="tabs-title is-active"><a href="#panel-a" aria-selected="true">@lang('admin::views.Search')</a></li>
            <li class="tabs-title pages"><a href="#panel-b">@lang('admin::views.Pages') {{ count($search_pages) > 0 ? '('.count($search_pages).')' : '' }}</a></li>
            <li class="tabs-title patterns"><a href="#panel-c">@lang('admin::views.Patterns') {{ count($search_patterns) > 0 ? '('.count($search_patterns).')' : '' }}</a></li>
            <li class="tabs-title media"><a href="#panel-d">@lang('admin::views.Media') {{ count($search_media) > 0 ? '('.count($search_media).')' : '' }}</a></li>
          </ul>
          {!! Form::open(['url' => URL::to('/').'/admin/dashboard', 'method' => 'GET']) !!}
          <div class="search-bar" id="DataTables_Table_1_filter">
            {!! Form::input('search', 'search', old('search', $search), ['placeholder' => trans('admin::views.fresh search'), 'aria-controlls' => 'DataTables_Table_1']) !!}
            <button type="submit" class="icon icon-Search"></button>
          </div>
          {!! Form::close() !!}
          <div class="tabs-content" data-tabs-content="example-tabs">
            <div class="tabs-panel is-active" id="panel-a">
              <div class="listing pages">
                <div class="row">
                  <?php
                  if (count($search_media) > 0) :
                    $lastKey = array_search(end($search_media), $search_media);
                  elseif (count($search_patterns) > 0) :
                    $lastKey = array_search(end($search_patterns), $search_patterns);
                  elseif (count($search_pages) > 0) :
                    $lastKey = array_search(end($search_pages), $search_pages);
                  endif;
                  ?>
                  @foreach ($search_pages as $k => $search_page)
                  <div class="columns large-6 li{{ $k == $lastKey ? ' end' : ''}}">                 
                    <span class="id">{{ $search_page->id }}</span>
                    <a class="item" href="{{URL::to('/')}}/admin/pages/edit/{!! $search_page->id !!}">{{ $search_page->name }}</a>
                    <span class="actions">
                      <a data-open="delPageSearch{!! $search_page->id !!}" data-tooltip aria-haspopup="true" data-disable-hover='false' tabindex="1" title="@lang('admin::views.Delete Page')" class="icon icon-Delete top "></a>
                      <a data-open="clonePageSearch{!! $search_page->id !!}" data-tooltip title="@lang('admin::views.Clone Page')" class="icon icon-Files top"></a>                      
                      <a data-tooltip title="@lang('admin::views.Preview Page')" target="blank" href="{{URL::to('/')}}/{!! $search_page->url == '/' ? '' : $search_page->url !!}" class="icon icon-Export top"></a>
                    </span>
                  </div>
                  @endforeach
                  @foreach ($search_patterns as $k => $search_patt)
                  <div class="columns large-6 li{{ $k == $lastKey ? ' end' : ''}}">                
                    <span class="id">{{ $search_patt->id }}</span>
                    <a class="item" href="{{URL::to('/')}}/admin/patterns/edit/{!! $search_patt->id !!}">{{ $search_patt->name }}</a>
                    <span class="actions">
                      <a data-open="clonePattSearch{!! $search_patt->id !!}" data-tooltip title="@lang('admin::views.Clone Pattern')" class="icon icon-Files top"></a>
                      <a data-open="delPattSearch{!! $search_patt->id !!}" data-tooltip aria-haspopup="true" data-disable-hover='false' tabindex="1" title="@lang('admin::views.Delete Pattern')" class="icon icon-Delete top "></a>
                    </span>
                  </div>
                  @endforeach
                  @foreach ($search_media as $k => $search_med)
                  <div class="columns large-6 li{{ $k == $lastKey ? ' end' : ''}}">                
                    <span class="id">{{ $search_med['id'] }}</span>
                    <a class="item" href="{{URL::to('/')}}/admin/media/media-edit/{!! $search_med['id'] !!}">{{ $search_med['original_filename'] }}</a>
                    <span class="actions">
                      <a data-open="delMediaSearch{!! $search_med['id'] !!}" data-tooltip aria-haspopup="true" data-disable-hover='false' tabindex="1" title="@lang('admin::views.Delete Media')" class="icon icon-Delete top "></a>
                    </span>
                  </div>
                  @endforeach
                  <!--<div class="columns text-center pages-nav">
                     <a class="prev fa fa-chevron-left" href=""></a><span class="current-page">1</span>/<span class="total-pages">12</span><a class="next fa fa-chevron-right" href=""></a>
                   </div> -->
                </div>
              </div>
            </div>
            <div class="tabs-panel" id="panel-b">
              <div class="listing pages">
                <div class="row">
                  <?php $lastKey = array_search(end($search_pages), $search_pages); ?>
                  @foreach ($search_pages as $k => $search_page)
                  <div class="columns large-6 li{{ $k == $lastKey ? ' end' : ''}}">                 
                    <span class="id">{{ $search_page->id }}</span>
                    <a class="item" href="{{URL::to('/')}}/admin/pages/edit/{!! $search_page->id !!}">{{ $search_page->name }}</a>
                    <span class="actions">
                      <a data-open="delPageSearch{!! $search_page->id !!}" data-tooltip aria-haspopup="true" data-disable-hover='false' tabindex="1" title="@lang('admin::views.Delete Page')" class="icon icon-Delete top "></a>
                      <a data-open="clonePageSearch{!! $search_page->id !!}" data-tooltip title="@lang('admin::views.Clone Page')" class="icon icon-Files top"></a>                      
                      <a data-tooltip title="@lang('admin::views.Preview Page')" target="blank" href="{{URL::to('/')}}/{!! $search_page->url == '/' ? '' : $search_page->url !!}" class="icon icon-Export top"></a>
                    </span>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
            <div class="tabs-panel" id="panel-c">
              <div class="listing patterns">
                <div class="row">
                  <?php $lastKey = array_search(end($search_patterns), $search_patterns); ?>
                  @foreach ($search_patterns as $k => $search_patt)
                  <div class="columns large-6 li{{ $k == $lastKey ? ' end' : ''}}">                
                    <span class="id">{{ $search_patt->id }}</span>
                    <a class="item" href="{{URL::to('/')}}/admin/patterns/edit/{!! $search_patt->id !!}">{{ $search_patt->name }}</a>
                    <span class="actions">
                      <a data-open="clonePattSearch{!! $search_patt->id !!}" data-tooltip title="@lang('admin::views.Clone Pattern')" class="icon icon-Files top"></a>
                      <a data-open="delPattSearch{!! $search_patt->id !!}" data-tooltip aria-haspopup="true" data-disable-hover='false' tabindex="1" title="@lang('admin::views.Delete Pattern')" class="icon icon-Delete top "></a>
                    </span>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
            <div class="tabs-panel" id="panel-d">
              <div class="listing media">
                <div class="row">
                  <?php $lastKey = array_search(end($search_media), $search_media); ?>
                  @foreach ($search_media as $k => $search_med)
                  <div class="columns large-6 li{{ $k == $lastKey ? ' end' : ''}}">                
                    <span class="id">{{ $search_med['id'] }}</span>
                    <a class="item" href="{{URL::to('/')}}/admin/media/media-edit/{!! $search_med['id'] !!}">{{ $search_med['original_filename'] }}</a>
                    <span class="actions">
                      <a data-open="delMediaSearch{!! $search_med['id'] !!}" data-tooltip aria-haspopup="true" data-disable-hover='false' tabindex="1" title="@lang('admin::views.Delete Media')" class="icon icon-Delete top "></a>
                    </span>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  @if (!empty($widgets))




  <?php $size = 0; ?>

  @foreach ($widgets as $k => $widget)

  @if ($size == 0)
  <section class="box">
    <div class="row">
      @endif

      {!! $widget['dashboardView'] !!}
      <?php
      $size = $size + $widget['size'];
      if (isset($widgets[$k + 1])) {
        $next_size = $size + $widgets[$k + 1]['size'];
      } else {
        $next_size = 0;
      }
      ?>

      @if ($size == 12 || $next_size > 12 || $widget['isLast'])
    </div>
  </section>
  <?php $size = 0; ?>
  @endif

  @endforeach




  @endif

  <section class="editscreen box">
    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="latest-activity-tabs">
          <li class="tabs-title is-active">
            <!-- data-status: active, disabled or dev -->
            <a href="#panel1" aria-selected="true">
              @lang('admin::views.Latest Activity')
            </a>
            <!--<span class="actions">
              <a data-tooltip title="New" href="" class="icon icon-Files create top"></a>
            </span>-->
          </li>
          <li class="tabs-title pages">
            <a href="#panel2">
              @lang('admin::views.Pages')
            </a>
          </li>
          <li class="tabs-title patterns">
            <a href="#panel3">
              @lang('admin::views.Patterns')
            </a>
          </li>
          <li class="tabs-title media">
            <a href="#panel4">
              @lang('admin::views.Media')
            </a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="latest-activity-tabs">
          <div class="tabs-panel is-active" id="panel1">
            <div class="listing">
              <div class="row">
                <?php $lastKey = array_search(end($latest), $latest); ?>
                @foreach ($latest as $k => $lat)
                <div class="columns large-6 li{{ $k == $lastKey ? ' end' : ''}}">
                  <span class="id">{{ $lat['id'] }}</span>
                  <a class="item" data-type="{{ $lat['type'] }}" href="{{URL::to('/')}}/{{ $lat['edit_url'] }}">{{ $lat['name'] }}</a>
                  <span class="actions">
                    @if ($lat['type'] == 'page')
                    <a data-open="delPageLatest{{ $lat['id'] }}" data-tooltip aria-haspopup="true" data-disable-hover='false' tabindex="1" title="@lang('admin::views.Delete Page')" class="icon icon-Delete top "></a>
                    <a data-open="clonePageLatest{{ $lat['id'] }}" data-tooltip title="@lang('admin::views.Clone Page')" class="icon icon-Files top"></a>
                    <a data-tooltip title="@lang('admin::views.Preview Page')" target="blank" href="{{URL::to('/')}}/{!! $lat['url'] !!}" class="icon icon-Export top"></a>                    
                    @elseif ($lat['type'] == 'pattern')
                    <a data-open="clonePattLatest{{ $lat['id'] }}" data-tooltip title="@lang('admin::views.Clone Pattern')" class="icon icon-Files top"></a>
                    <a data-open="delPattLatest{{ $lat['id'] }}" data-tooltip aria-haspopup="true" data-disable-hover='false' tabindex="1" title="@lang('admin::views.Delete Pattern')" class="icon icon-Delete top "></a>                  
                    @elseif ($lat['type'] == 'media')
                   <a data-open="delMediaLatest{{ $lat['id'] }}" data-tooltip aria-haspopup="true" data-disable-hover='false' tabindex="1" title="@lang('admin::views.Delete Media')" class="icon icon-Delete top "></a>
                    @endif
                  </span>
                </div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="tabs-panel" id="panel2">
            <div class="listing">
              <div class="row">
                <?php $lastKey = array_search(end($latestPages), $latestPages); ?>
                @foreach ($latestPages as $k => $latest_page)
                <div class="columns large-6 li{{ $k == $lastKey ? ' end' : ''}}">
                  <span class="id">{{ $latest_page['id'] }}</span>
                  <a class="item" data-type="page" href="{{URL::to('/')}}/admin/pages/edit/{!! $latest_page['id'] !!}">{{ $latest_page['name'] }}</a>
                  <span class="actions">
                    <a data-open="delPageLatest{!! $latest_page['id'] !!}" data-tooltip aria-haspopup="true" data-disable-hover='false' tabindex="1" title="@lang('admin::views.Delete Page')" class="icon icon-Delete top "></a>                                      
                    <a data-open="clonePageLatest{!! $latest_page['id'] !!}" data-tooltip title="@lang('admin::views.Clone Page')" class="icon icon-Files top"></a>
                    <a data-tooltip title="@lang('admin::views.Preview Page')" target="blank" href="{{URL::to('/')}}/{!! $latest_page['url'] == '/' ? '' : $latest_page['url'] !!}" class="icon icon-Export top"></a>
                  </span>
                </div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="tabs-panel" id="panel3">
            <div class="listing">
              <div class="row">
                <?php $lastKey = array_search(end($latestPatterns), $latestPatterns); ?>
                @foreach ($latestPatterns as $k => $latest_patt)            
                <div class="columns large-6 li{{ $k == $lastKey ? ' end' : ''}}">
                  <span class="id">{{ $latest_patt['id'] }}</span>
                  <a class="item" data-type="pattern" href="{{URL::to('/')}}/admin/patterns/edit/{!! $latest_patt['id'] !!}">{{ $latest_patt['name'] }}</a>
                  <span class="actions">
                    <a data-open="clonePattLatest{!! $latest_patt['id'] !!}" data-tooltip title="@lang('admin::views.Clone Pattern')" class="icon icon-Files top"></a>
                    <a data-open="delPattLatest{!! $latest_patt['id'] !!}" data-tooltip aria-haspopup="true" data-disable-hover='false' tabindex="1" title="@lang('admin::views.Delete Pattern')" class="icon icon-Delete top "></a>
                  </span>
                </div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="tabs-panel" id="panel4">
            <div class="listing">
              <div class="row">
                <?php $lastKey = array_search(end($latestMedia), $latestMedia); ?>
                @foreach ($latestMedia as $k => $latest_media)            
                <div class="columns large-6 li{{ $k == $lastKey ? ' end' : ''}}">
                  <span class="id">{{ $latest_media['id'] }}</span>
                  <a class="item" data-type="media" href="{{URL::to('/')}}/admin/media/media-edit/{!! $latest_media['id'] !!}">{{ $latest_media['original_filename'] }}</a>
                  <span class="actions">
                    <a data-open="delMediaLatest{!! $latest_media['id'] !!}" data-tooltip aria-haspopup="true" data-disable-hover='false' tabindex="1" title="@lang('admin::views.Delete Media')" class="icon icon-Delete top "></a>
                  </span>
                </div>
                @endforeach
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
    </div>
  </div>
</footer>

{{-- Latest edited Modals  --}}
@foreach ($latestPages as $latest_page)
{!! \Atlantis\Helpers\Modal::set(
  'delPageLatest' . $latest_page['id'],
  trans('admin::views.Delete Page'),
  trans('admin::views.Are you sure you want to delete', ['object' => $latest_page['id']]),
  trans('admin::views.Delete'),
  URL::to('/').'/admin/pages/delete-page/' . $latest_page['id']) !!}

{!! \Atlantis\Helpers\Modal::setClonePage('clonePageLatest' . $latest_page['id'], URL::to('/').'/admin/pages/clone-page/' . $latest_page['id'], $latest_page['name'] . '-clone', $latest_page['url']) !!}
@endforeach

@foreach ($latestPatterns as $latest_patt)
{!! \Atlantis\Helpers\Modal::set(
  'delPattLatest' . $latest_patt['id'],
  trans('admin::views.Delete Pattern'),
  trans('admin::views.Are you sure you want to delete',['object' => $latest_patt['name']]),
  trans('admin::views.Delete'),
  URL::to('/').'/admin/patterns/delete-pattern/' . $latest_patt['id']) !!}

{!! \Atlantis\Helpers\Modal::setClonePattern('clonePattLatest' . $latest_patt['id'], URL::to('/').'/admin/patterns/clone-pattern/' . $latest_patt['id'], $latest_patt['id'] . '-clone') !!}
@endforeach
@foreach ($latestMedia as $latest_media)
{!! \Atlantis\Helpers\Modal::set(
  'delMediaLatest' . $latest_media['id'],
  trans('admin::views.Delete Media'),
  trans('admin::views.Are you sure you want to delete',['object' => $latest_media['original_filename']]),
  trans('admin::views.Delete'),
  'admin/media/media-deletee/' . $latest_media['id']) !!}
@endforeach

{{-- Search Modals  --}}
@foreach ($search_pages as $search_page)
{!! \Atlantis\Helpers\Modal::set(
  'delPageSearch' . $search_page->id,
  trans('admin::views.Delete Page'),
  trans('admin::views.Are you sure you want to delete',['object' => $search_page->id]),
  trans('admin::views.Delete'),
  URL::to('/').'/admin/pages/delete-page/' . $search_page->id) !!}

{!! \Atlantis\Helpers\Modal::setClonePage('clonePageSearch' . $search_page->id, URL::to('/').'/admin/pages/clone-page/' . $search_page->id, $search_page->name . '-clone', $search_page->url) !!}
@endforeach
@foreach ($search_patterns as $search_patt)
{!! \Atlantis\Helpers\Modal::set(
  'delPattSearch' . $search_patt->id,
  trans('admin::views.Delete Pattern'),
  trans('admin::views.Are you sure you want to delete',['object' => $search_patt->id]),
  trans('admin::views.Delete'),
  URL::to('/').'/admin/patterns/delete-pattern/' . $search_patt->id) !!}

{!! \Atlantis\Helpers\Modal::setClonePattern('clonePattSearch' . $search_patt->id, URL::to('/').'/admin/patterns/clone-pattern/' . $search_patt->id, $search_patt->name . '-clone') !!}
@endforeach
@foreach ($search_media as $search_med)
{!! \Atlantis\Helpers\Modal::set(
  'delMediaSearch' . $search_med['id'],
  trans('admin::views.Delete Media'),
  trans('admin::views.Are you sure you want to delete',['object' => $search_med['original_filename']]),
  trans('admin::views.Delete'),
  URL::to('/').'/admin/media/media-delete/' . $search_med['id']) !!}
@endforeach
@stop