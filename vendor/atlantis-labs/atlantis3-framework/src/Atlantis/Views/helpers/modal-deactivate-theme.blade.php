<div class="reveal" id="{!! $modal_id !!}" data-reveal>
  {!! Form::open(['url' => 'admin/themes/deactivate-theme']) !!}    
  <h1>@lang('admin::views.Deactivate Theme')</h1>
  <p class="lead">@lang('admin::views.Are you sure you want to deactivate') {{ $theme_name }}</p>
  {!! Form::input('hidden', 'theme_path', $path, []) !!}
  <button class="close-button" data-close aria-label="@lang('admin::views.Close modal')" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <input type="submit" name="_deactivate_theme" value="@lang('admin::views.Deactivate')" id="update-btn" class="alert button">
  {!! Form::close() !!}
</div>