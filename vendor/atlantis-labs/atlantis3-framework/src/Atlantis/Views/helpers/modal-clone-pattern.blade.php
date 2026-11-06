<div class="reveal" id="{!! $modal_id !!}" data-reveal>
  {!! Form::open(['url' => $formUrl]) !!}    
  <h1>@lang('admin::views.Clone Pattern')</h1>
  <label for="clone_name">@lang('admin::views.Clone Name')
  {!! Form::input('text', 'clone_name', old('clone_name', $clone_name), ['id'=>'clone_name']) !!}
  </label>
  <button class="close-button" data-close aria-label="@lang('admin::views.Close modal')" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <input type="submit" name="_clone" value="@lang('admin::views.Clone')" id="update-btn" class="alert button">
  {!! Form::close() !!}
</div>