<div class="reveal" id="{!! $modal_id !!}" data-reveal>
  {!! Form::open(['url' => 'admin/modules/install']) !!}    
  <h1>@lang('admin::views.Install Module')</h1>
  <p class="lead">@lang('admin::views.Are you sure you want to install') {{ $aModuleConfig['name'] }}</p>
  {!! Form::input('hidden', 'module_path', $aModuleConfig['path'], []) !!}
  <button class="close-button" data-close aria-label="@lang('admin::views.Close modal')" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <input type="submit" name="_install_module" value="@lang('admin::views.Install')" id="update-btn" class="success button">
  {!! Form::close() !!}
</div>