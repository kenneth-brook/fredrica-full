<div class="row">
    <div class="columns">
        <label for="{!! $field->field_slur !!}">{!! $field->field_label !!}<kbd>{!! $field->field_slur !!}</kbd>
            {!! \Atlantis\Helpers\Media\MediaTools::createGallerySelector( !empty($value->field_value) ? $value->field_value : '' , 'custom_hidden['.$field->field_slur.']')!!}
             {!! Form::input('hidden', 'custom_hidden_id['.$field->field_slur.']', $field->id) !!}
        </label>
    </div>
</div>
