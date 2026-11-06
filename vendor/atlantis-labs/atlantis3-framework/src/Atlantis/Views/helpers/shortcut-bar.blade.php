@if (config('atlantis.show_shortcut_bar') && auth()->user() != NULL && auth()->user()->hasRole('admin-login'))
<div class="a3admin_admin-shortcut">

  <div class="a3admin_row">
    <div class="a3admin_columns">
      <div class="a3admin_top-bar" id="user-menu">
        <div class="a3admin_top-bar-left a3admin_user-menu">
          <div class="a3admin_account a3admin_left">
            <div class="a3admin_username">@lang('admin::views.Welcome', ['name' => auth()->user()->name ])</div>
            <div class="a3admin_actions">
              <a href="{{URL::to('/')}}/admin/users/edit/{{ auth()->user()->id }}">@lang('admin::views.Settings')</a> / <a href="{{URL::to('/')}}/admin/logout">@lang('admin::views.Logout')</a>
            </div>
          </div>
          <h3 class="a3admin_menu-text a3admin_left">{{ config('atlantis.site_name') }}</h3>
        </div>
        <div id="main-nav" class="a3admin_top-bar-right">
          <button id="a3admin_nav-toggle" type="button" data-toggle="" class="">
            <span class="a3admin_toggle-pin"></span>
            <span class="a3admin_toggle-pin"></span>
            <span class="a3admin_toggle-pin"></span>
          </button>
                
          <ul class="a3admin_dropdown a3admin_menu" data-dropdown-menu>
            <li><a target="_blank" href="{{URL::to('/')}}/admin/pages/edit/{!! $page->id !!}">@lang('admin::views.Edit this Page')</a></li>
            <li><a target="_blank" href="{{URL::to('/')}}/admin/pages">@lang('admin::views.Pages')</a>
              
            </li>
            <li>
              <a target="_blank" href="{{URL::to('/')}}/admin/patterns">@lang('admin::views.Patterns')</a>
              <ul class="a3admin_submenu">
                <li>
                 <label>@lang('admin::views.Page specific')</label>
                 <ul class="page-patterns-list specific">

                  @if (isset($aPatterns['specific']))
                  @foreach ($aPatterns['specific'] as $specific)
                  <li>
                    <a target="_blank" data-status="{{ $specific['status'] != 1 ? 'disabled' : 'active'}}"
                    href="admin/patterns/edit/{{ $specific['id'] }}">{{ $specific['name'] }}</a>
                  </li>
                  @endforeach
                  @endif
                </ul>
              </li>

              <li>
                <label>@lang('admin::views.Common')</label>
                <ul class="page-patterns-list common">
                  @if (isset($aPatterns['common']))
                  @foreach ($aPatterns['common'] as $common)
                  <li>
                    <a target="_blank" data-status="{{ $common['status'] != 1 ? 'disabled' : 'active'}}"
                    href="admin/patterns/edit/{{ $common['id'] }}">{{ $common['name'] }}</a>
                  </li>
                  @endforeach
                  @endif
                </ul>
              </li>
              <li>
                <label>@lang('admin::views.Excluded')</label>
                <ul class="page-patterns-list excluded ">
                  @if (isset($aPatterns['excluded']))
                  @foreach ($aPatterns['excluded'] as $excluded)
                  <li>
                    <a target="_blank" href="admin/patterns/edit/{{ $excluded['id'] }}">{{ $excluded['name'] }}</a>
                  </li>
                  @endforeach
                  @endif
                </ul>
              </li>
              </ul>

            </li>
            <li>
              <a target="_blank" href="{{URL::to('/')}}/admin/modules">@lang('admin::views.Modules')</a>
              <ul class="a3admin_submenu">
                @foreach ($aModules as $module)

                 @if( ($module['adminURL'] != NULL || !empty($module['adminURL'])) && $module['active'] == 1)
                  <li>
                    <a target="_blank" href="{!! $module['adminURL'] !!}">{{ $module['name'] }}</a>
                  </li>
                 @endif  
                @endforeach
              </ul>
            </li>
            <li><a target="_blank" href="{{URL::to('/')}}/admin/media">@lang('admin::views.Media')</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>       
</div>
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function(event) {
    (function() {
      document.getElementById('a3admin_nav-toggle').onclick = function (ev) {
        if (this.classList.contains('active')) {

        this.classList.remove('active');
        document.getElementsByClassName('a3admin_dropdown')[0].classList.remove('active');
      }
      else {
          this.classList.add('active');
        document.getElementsByClassName('a3admin_dropdown')[0].classList.add('active');       
      }
    }
  })()
})
</script>

@endif