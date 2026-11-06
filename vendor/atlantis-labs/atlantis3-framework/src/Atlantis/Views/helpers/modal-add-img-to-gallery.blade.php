<div class="reveal" id="{!! $modal_id !!}" data-reveal>
  {!! Form::open(['url' => 'admin/media/add-img-to-gallery/' . $img_id]) !!}    
  <h1>@lang('admin::views.Add to Gallery')</h1>
  <p class="lead">{!! Form::select('gallery', $galleries, NULL, []) !!} </p>
  <button class="close-button" data-close aria-label="@lang('admin::views.Close modal')" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
  <input type="submit" name="_add_to_gallery" value="@lang('admin::views.Add')" id="update-btn" class="alert button">
  {!! Form::close() !!}
</div>