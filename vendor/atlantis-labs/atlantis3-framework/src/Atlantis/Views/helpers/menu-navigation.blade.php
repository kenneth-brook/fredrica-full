<header>
  <div class="row">
    <div class="columns">
    
    <div class="hide-for-large clearfix mobile-header">
    <span class="float-left ham-menu" data-toggle="main-header">
      <span class="menu-icon dark" data-toggle></span>
    </span>
    <h3 class="float-left menu-text left"><a href="{{URL::to('/')}}/" target="_blank">{{ config('atlantis.site_name') }}</a></h3>
    <div class="float-right user-menu hide-for-large">
          <div class="account">
            <span class="icon icon-User left"></span>
            <div class="username">{{ auth()->user()->name }}</div>
            <div class="actions">
              <a href="{{URL::to('/')}}/admin/users/edit/{{ auth()->user()->id }}">@lang('admin::views.Settings')</a> / <a href="{{URL::to('/')}}/admin/logout">@lang('admin::views.Logout')</a>
            </div>
          </div>
          <h3 class="menu-text left show-for-large"><a href="{{URL::to('/')}}/" target="_blank">{{ config('atlantis.site_name') }}</a></h3>
        </div>
    </div>

      <div id="main-header" data-toggler=".expanded" class="_show-for-large">
      <div class="top-bar" id="user-menu">
        <div class="top-bar-left user-menu show-for-large">
          <div class="account">
            <span class="icon icon-User left"></span>
            <div class="username">{{ auth()->user()->name }}</div>
            <div class="actions">
              <a href="{{URL::to('/')}}/admin/users/edit/{{ auth()->user()->id }}">@lang('admin::views.Settings')</a> / <a href="{{URL::to('/')}}/admin/logout">@lang('admin::views.Logout')</a>
            </div>
          </div>
          <h3 class="menu-text left show-for-large"><a href="{{URL::to('/')}}/" target="_blank">{{ config('atlantis.site_name') }}</a></h3>
        </div>
        <div id="main-nav" class="top-bar-right">
          <ul class="dropdown menu" data-dropdown-menu>
            @foreach($aMenuItems as $id => $item)
            @if (!$item['is_parent'])

            <li{!! $item['active'] !!}><a{!! !empty($item['tooltip']) ? ' data-tooltip title="' . $item['tooltip'] . '"' : '' !!}{!! !empty($item['class']) ? ' class="' . $item['class'] . '"' : '' !!} href="{{URL::to('/')}}{{ $item['url'] }}">{{ $item['name'] }}</a>

              @if ($id == \Atlantis\Controllers\Admin\AdminController::$_ID_PAGES)
              <ul class="menu vertical">                
                <li><a class="" href="{{URL::to('/')}}/admin/pages/add">@lang('admin::views.Add Page')</a></li>
              </ul>
              @elseif ($id == \Atlantis\Controllers\Admin\AdminController::$_ID_PATTERNS)
              <ul class="menu vertical">                
                <li><a class="" href="{{URL::to('/')}}/admin/patterns/add">@lang('admin::views.Add Pattern')</a></li>
              </ul>
              @elseif ($id == \Atlantis\Controllers\Admin\AdminController::$_ID_MEDIA)
              <ul class="menu vertical">                
                <li><a class="" href="{{URL::to('/')}}/admin/media/media-add">@lang('admin::views.Add Media')</a></li>
              </ul>
              @endif

            </li>
            @else
            <li{!! $item['active'] !!}><a{!! !empty($item['tooltip']) ? ' data-tooltip title="' . $item['tooltip'] . '"' : '' !!}{!! !empty($item['parent-class']) ? ' class="' . $item['parent-class'] . '"' : '' !!} href="{{ $item['url'] }}">{{ $item['name'] }}</a>
              <ul class="menu vertical">
                @foreach($item['child_items'] as $child_item)
                <li{!! $child_item['active'] !!}><a{!! !empty($child_item['tooltip']) ? ' data-tooltip title="' . $child_item['tooltip'] . '"' : '' !!}{!! !empty($child_item['class']) ? ' class="' . $child_item['class'] . '"' : '' !!} href="{{URL::to('/')}}{{ $child_item['url'] }}">{{ $child_item['name'] }}</a></li>
                @endforeach
              </ul>
            </li>
            @endif
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>
  </div>
</header>