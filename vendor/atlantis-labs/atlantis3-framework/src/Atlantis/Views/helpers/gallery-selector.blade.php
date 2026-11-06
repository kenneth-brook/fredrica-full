<div class="row gal-selector">
	<div class="columns medium-4">
		<label for="gallery_id">@lang('admin::views.Gallery')
			{!! Form::select($name, $aGalleriesSelect, $selected_gallery, ['id' => 'gallery_id'.$name]) !!}
		</label>
	</div>
	<div class="column medium-4 end gal-selector-actions">
		<label for="">
			@lang('admin::views.actions')
		</label>
		<a href="#" target="_blank" class="edit-gal alert button disabled">@lang('admin::views.Edit This Gallery')</a>
		<a href="admin/media/gallery-add" target="_blank" class="button">@lang('admin::views.Create New Gallery')</a>
		<a target="_blank" class="button refresh-gal">@lang('admin::views.Refresh Gallery List')</a>
	</div>
</div>