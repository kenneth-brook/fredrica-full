@extends('atlantis-admin::admin-shell')

@section('title')
@lang('admin::views.Modules') | @lang('admin::views.A3 Administration') | {{ config('atlantis.site_name') }}
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
        <h1 class="huge page-title">@lang('admin::views.Modules')</h1>
      </div>
    </div>
  </section>
  <section class="modules-list editscreen clearfix">
    <div class="row">
      <div class="columns">
        <div class="float-right">
          <div class="buttons">
             <a id="save-close-btn" class="alert button" href="admin/modules/repository">@lang('admin::views.Repository')</a>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="columns small-12">
        @if ($needUpdate)
        <div class="warning callout">
          <h5>@lang('admin::views.needUpdate')</h5>
          <a href="admin/modules/update-modules-setup">@lang('admin::views.Update')</a>
        </div>
        @endif
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
        @if (isset($pageError))
          <div class="callout alert">
            <h5>{!! $pageError !!}</h5>
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
            <!-- data-status: active, disabled or dev -->
            <a href="#panel1" aria-selected="true">
              @lang('admin::views.Installed Modules') ({{ $count_installed }})
            </a>
          </li>
          
          @if ($canEditModules)
          <li class="tabs-title"><a href="#panel2">@lang('admin::views.Available Modules') ({{ $count_notinstalled }})</a></li>
          @endif
          
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">
            <div class="row">
              <div class="columns large-4">
                @foreach ($aModules[1] as $module)
                 <?php 

                if (substr($module['icon'], 0, 1) === '/')
                {
                  $icon = '<img class="module-icon" src="modules/'.$module['path'].$module['icon'].'">';
                }
                else
                {
                  $icon = '<span class="'.$module['icon'].'"></span>';
                }?>
                <div id="module-{!! $module['id'] !!}" class="module columns has-submenu" data-toggler=".expanded">

                  <div class="title {{ $module['active'] == 1 ? 'active' : 'disabled'}}">
                    <h3>

                      @if (($module['adminURL'] != NULL || !empty($module['adminURL'])) && $module['active'] == 1)
                      <a href="{!! $module['adminURL'] !!}">{!!$icon!!}{{ $module['name'] }}</a>
                      @else
                      <a>{!!$icon!!}{{ $module['name'] }}</a>
                      @endif  

                      @if ($module['active'] == 0)
                        <div class="label alert">@lang('admin::views.not active')</div>
                      @endif
                      <div class="label">@lang('admin::views.version') {{ $module['version'] }}</div>
                      @if (isset($module['api']) && isset($module['api']['new_version']))
                      <div class="label warning">@lang('admin::views.new version') {{ $module['api']['new_version'] }}</div>
                      @endif
                      @if (isset($module['protected']) && $module['protected'])
                      <div class="label success">@lang('admin::views.protected')</div>
                      @endif
                    </h3>
                    <div class="actions">
                      @if ($canEditModules)

                      @if (isset($module['api']))
                      <a data-open="updatelModule{!! $module['id'] !!}" class="top" data-tooltip title="@lang('admin::views.Update') {{ $module['name'] }}"><span class="icon icon-DownloadCloud"></span></a>
                      @endif

                      @if ($module['active'] == 1)
                      <a href="admin/modules/deactivate-module/{!! $module['id'] !!}" class="top" data-tooltip title="@lang('admin::views.Deactivate') {{ $module['name'] }}"><span class="icon icon-Pause"></span></a>
                      @else
                      <a href="admin/modules/activate-module/{!! $module['id'] !!}" class="top" data-tooltip title="@lang('admin::views.Activate') {{ $module['name'] }}"><span class="icon icon-Play"></span></a>
                      @endif
                      <a data-open="unistallModule{!! $module['id'] !!}" class="top" data-tooltip title="@lang('admin::views.Uninstall') {{ $module['name'] }}"><span class="icon icon-Delete"></span></a>
                        @if(isset($module['create_resources']) && $module['create_resources'] == TRUE)
                          <a data-open="createModulePage{!! $module['id'] !!}" class="top" data-tooltip
                             title="@lang('admin::views.Create related pages and patterns')"><span
                                    class="icon icon-Web" aria-hidden="true"></span></a>
                        @endif
                      @endif
                      <a class="expander" data-toggle="module-{!! $module['id'] !!}"></a>
                    </div>
                  </div>
                  <div class="submenu">{!! $module['description'] !!}</div>
                </div>
                @endforeach
              </div>
              <div class="columns large-4">
                @foreach ($aModules[2] as $module)
                <?php 
                if (substr($module['icon'], 0, 1) === '/')
                {
                  $icon = '<img class="module-icon" src="modules/'.$module['path'].$module['icon'].'">';
                }
                else
                {
                  $icon = '<span class="'.$module['icon'].'"></span>';
                }?>
                <div id="module-{!! $module['id'] !!}" class="module columns has-submenu" data-toggler=".expanded">
                  <div class="title {{ $module['active'] == 1 ? 'active' : 'disabled'}}">
                    <h3>
                      @if (($module['adminURL'] != NULL || !empty($module['adminURL'])) && $module['active'] == 1)
                      <a href="{!! $module['adminURL'] !!}">{!!$icon!!}{{ $module['name'] }}</a>
                      @else
                      <a href="javascript:void(0)">{!!$icon!!}{{ $module['name'] }}</a>
                      @endif  
                      @if ($module['active'] == 0)
                        <div class="label alert">@lang('admin::views.not active')</div>
                      @endif
                      <div class="label">@lang('admin::views.version') {{ $module['version'] }}</div>
                      @if (isset($module['api']) && isset($module['api']['new_version']))
                      <div class="label warning">@lang('admin::views.new version') {{ $module['api']['new_version'] }}</div>
                      @endif
                      @if (isset($module['protected']) && $module['protected'])
                      <div class="label success">@lang('admin::views.protected')</div>
                      @endif
                      
                    </h3>
                    <div class="actions">
                      @if ($canEditModules)

                      @if (isset($module['api']))
                      <a data-open="updatelModule{!! $module['id'] !!}" class="top" data-tooltip title="@lang('admin::views.Update') {{ $module['name'] }}"><span class="icon icon-DownloadCloud"></span></a>
                      @endif

                      @if ($module['active'] == 1)
                      <a href="admin/modules/deactivate-module/{!! $module['id'] !!}" class="top" data-tooltip title="@lang('admin::views.Deactivate') {{ $module['name'] }}"><span class="icon icon-Pause"></span></a>
                      @else
                      <a href="admin/modules/activate-module/{!! $module['id'] !!}" class="top" data-tooltip title="@lang('admin::views.Activate') {{ $module['name'] }}"><span class="icon icon-Play"></span></a>
                      @endif
                      <a data-open="unistallModule{!! $module['id'] !!}" class="top" data-tooltip title="@lang('admin::views.Uninstall') {{ $module['name'] }}"><span class="icon icon-Delete"></span></a>
                        @if(isset($module['create_resources']) && $module['create_resources'] == TRUE)
                          <a data-open="createModulePage{!! $module['id'] !!}" class="top" data-tooltip
                             title="@lang('admin::views.Create related pages and patterns')"><span
                                    class="icon icon-Web" aria-hidden="true"></span></a>
                        @endif
                      @endif
                      <a class="expander" data-toggle="module-{!! $module['id'] !!}"></a>
                    </div>
                  </div>
                  <div class="submenu">{!! $module['description'] !!}</div>
                </div>
                @endforeach
              </div>
              <div class="columns large-4">
                @foreach ($aModules[3] as $module)
                 <?php 

                if (substr($module['icon'], 0, 1) === '/')
                {
                  $icon = '<img class="module-icon" src="modules/'.$module['path'].$module['icon'].'">';
                }
                else
                {
                  $icon = '<span class="'.$module['icon'].'"></span>';
                }?>
                <div id="module-{!! $module['id'] !!}" class="module columns has-submenu" data-toggler=".expanded">
                  <div class="title {{ $module['active'] == 1 ? 'active' : 'disabled'}}">
                    <h3>
                      @if ( ($module['adminURL'] != NULL || !empty($module['adminURL'])) && $module['active'] == 1)
                      <a href="{!! $module['adminURL'] !!}">{!!$icon!!}{{ $module['name'] }}</a>
                      @else
                      <a href="javascript:void(0)">{!!$icon!!}{{ $module['name'] }}</a>
                      @endif
                      
                      @if ($module['active'] == 0)
                        <div class="label alert">@lang('admin::views.not active')</div>
                      @endif

                      <div class="label">@lang('admin::views.version') {{ $module['version'] }}</div>
                      @if (isset($module['api']) && isset($module['api']['new_version']))
                      <div class="label warning">@lang('admin::views.new version') {{ $module['api']['new_version'] }}</div>
                      @endif
                      @if (isset($module['protected']) && $module['protected'])
                      <div class="label success">@lang('admin::views.protected')</div>
                      @endif
                     
                    </h3>
                    <div class="actions">
                      @if ($canEditModules)

                      @if (isset($module['api']))
                      <a data-open="updatelModule{!! $module['id'] !!}" class="top" data-tooltip title="@lang('admin::views.Update') {{ $module['name'] }}"><span class="icon icon-DownloadCloud"></span></a>
                      @endif

                      @if ($module['active'] == 1)
                      <a href="admin/modules/deactivate-module/{!! $module['id'] !!}" class="top" data-tooltip title="@lang('admin::views.Deactivate') {{ $module['name'] }}"><span class="icon icon-Pause"></span></a>
                      @else
                      <a href="admin/modules/activate-module/{!! $module['id'] !!}" class="top" data-tooltip title="@lang('admin::views.Activate') {{ $module['name'] }}"><span class="icon icon-Play"></span></a>
                      @endif
                      <a data-open="unistallModule{!! $module['id'] !!}" class="top" data-tooltip title="@lang('admin::views.Uninstall') {{ $module['name'] }}"><span class="icon icon-Delete"></span></a>
                        @if(isset($module['create_resources']) && $module['create_resources'] == TRUE)
                          <a data-open="createModulePage{!! $module['id'] !!}" class="top" data-tooltip
                             title="@lang('admin::views.Create related pages and patterns')"><span
                                    class="icon icon-Web" aria-hidden="true"></span></a>
                        @endif
                      @endif
                      <a class="expander" data-toggle="module-{!! $module['id'] !!}"></a>
                    </div>
                  </div>
                  <div class="submenu">{!! $module['description'] !!}</div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="tabs-panel" id="panel2">
            <div class="row">
              @if (count($aNotInstalledModules[1]) == 0 && count($aNotInstalledModules[2]) == 0 && count($aNotInstalledModules[3]) == 0)
              <h5 class="text-center">@lang('admin::views.no_mod_to_install')</h5>
              @endif              
              <div class="columns large-4">
                @foreach ($aNotInstalledModules[1] as $module)
                 <?php 
                if (substr($module['icon'], 0, 1) === '/')
                {
                  $icon = '<img class="module-icon" src="modules/'.$module['path'].$module['icon'].'">';
                }
                else
                {
                  $icon = '<span class="'.$module['icon'].'"></span>';
                }?>
                <div id="module-{!! str_replace('\\', '', $module['moduleNamespace']) !!}" class="module columns has-submenu" data-toggler=".expanded">
                  <div class="title">
                    <h3>
                      <a href="javascript:void(0)">{!!$icon!!}{{ $module['name'] }}</a>
                    </h3>
                    <div class="actions">
                      @if ($canEditModules)
                      <a data-open="installModule{!! str_replace('\\', '', $module['moduleNamespace']) !!}" data-tooltip title="@lang('admin::views.Install') {{ $module['name'] }}" class="top"><span class="icon icon-CD"></span></a>
                      @endif
                      <a class="expander" data-toggle="module-{!! str_replace('\\', '', $module['moduleNamespace']) !!}"></a>
                    </div>
                  </div>                 
                  <div class="submenu">{!! $module['description'] !!}</div>
                </div>
                @endforeach
              </div>
              <div class="columns large-4">
                @foreach ($aNotInstalledModules[2] as $module)
                 <?php 
                if (substr($module['icon'], 0, 1) === '/')
                {
                  $icon = '<img class="module-icon" src="modules/'.$module['path'].$module['icon'].'">';
                }
                else
                {
                  $icon = '<span class="'.$module['icon'].'"></span>';
                }?>
                <div id="module-{!! str_replace('\\', '', $module['moduleNamespace']) !!}" class="module columns has-submenu" data-toggler=".expanded">
                  <div class="title">
                    <h3>
                      <a href="javascript:void(0)">{!!$icon!!}{{ $module['name'] }}</a>
                    </h3>
                    <div class="actions">
                      @if ($canEditModules)
                      <a data-open="installModule{!! str_replace('\\', '', $module['moduleNamespace']) !!}" data-tooltip title="@lang('admin::views.Install') {{ $module['name'] }}" class="top"><span class="icon icon-CD"></span></a>
                      @endif
                      <a class="expander" data-toggle="module-{!! str_replace('\\', '', $module['moduleNamespace']) !!}"></a>
                    </div>
                  </div>                 
                  <div class="submenu">{!! $module['description'] !!}</div>
                </div>
                @endforeach
              </div>
              <div class="columns large-4">
                @foreach ($aNotInstalledModules[3] as $module)
                 <?php 
                if (substr($module['icon'], 0, 1) === '/')
                {
                  $icon = '<img class="module-icon" src="modules/'.$module['path'].$module['icon'].'">';
                }
                else
                {
                  $icon = '<span class="'.$module['icon'].'"></span>';
                }?>
                <div id="module-{!! str_replace('\\', '', $module['moduleNamespace']) !!}" class="module columns has-submenu" data-toggler=".expanded">
                  <div class="title">
                    <h3>
                      <a href="javascript:void(0)">{!!$icon!!}{{ $module['name'] }}</a>
                    </h3>
                    <div class="actions"> @if ($canEditModules)
                      <a data-open="installModule{!! str_replace('\\', '', $module['moduleNamespace']) !!}" data-tooltip title="@lang('admin::views.Install') {{ $module['name'] }}" class="top"><span class="icon icon-CD"></span></a>
                      @endif
                      <a class="expander" data-toggle="module-{!! str_replace('\\', '', $module['moduleNamespace']) !!}"></a>
                    </div>
                  </div>                 
                  <div class="submenu">{!! $module['description'] !!}</div>
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
 {{-- @include('atlantis-admin::help-sections/modules') --}}
  <div class="row">
    <div class="columns">
    </div>
  </div>
  @foreach ($aModules[1] as $module)
  {!! \Atlantis\Helpers\Modal::uninstallModule('unistallModule' . $module['id'], $module['id'], $module['name']) !!}
    @if(isset($module['create_resources']) && $module['create_resources'] == TRUE)
      {!! \Atlantis\Helpers\Modal::createModulePage('createModulePage' . $module['id'], $module['id'], $module['name']) !!}
    @endif
  @if (isset($module['api']))
  {!! \Atlantis\Helpers\Modal::updateModuleModal('updatelModule' . $module['id'], $module) !!}
  @endif
  @endforeach

  @foreach ($aModules[2] as $module)
  {!! \Atlantis\Helpers\Modal::uninstallModule('unistallModule' . $module['id'], $module['id'], $module['name']) !!}
    @if(isset($module['create_resources']) && $module['create_resources'] == TRUE)
      {!! \Atlantis\Helpers\Modal::createModulePage('createModulePage' . $module['id'], $module['id'], $module['name']) !!}
    @endif
  @if (isset($module['api']))
  {!! \Atlantis\Helpers\Modal::updateModuleModal('updatelModule' . $module['id'], $module) !!}
  @endif
  @endforeach

  @foreach ($aModules[3] as $module)
  {!! \Atlantis\Helpers\Modal::uninstallModule('unistallModule' . $module['id'], $module['id'], $module['name']) !!}
    @if(isset($module['create_resources']) && $module['create_resources'] == TRUE)
      {!! \Atlantis\Helpers\Modal::createModulePage('createModulePage' . $module['id'], $module['id'], $module['name']) !!}
    @endif
  @if (isset($module['api']))
  {!! \Atlantis\Helpers\Modal::updateModuleModal('updatelModule' . $module['id'], $module) !!}
  @endif
  @endforeach

  @foreach ($aNotInstalledModules[1] as $module)
  {!! \Atlantis\Helpers\Modal::installModule('installModule' . str_replace('\\', '', $module['moduleNamespace']), $module) !!}
  @endforeach
  @foreach ($aNotInstalledModules[2] as $module)
  {!! \Atlantis\Helpers\Modal::installModule('installModule' . str_replace('\\', '', $module['moduleNamespace']), $module) !!}
  @endforeach
  @foreach ($aNotInstalledModules[3] as $module)
  {!! \Atlantis\Helpers\Modal::installModule('installModule' . str_replace('\\', '', $module['moduleNamespace']), $module) !!}
  @endforeach
</footer>
@stop