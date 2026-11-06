<div class="row">
    <div class="columns">

        <label for="{!! $field->field_slur !!}">{!! $field->field_label !!}<kbd>{!! $field->field_slur !!}</kbd>
            @if(!is_null($img_array))
                {!! \Atlantis\Helpers\Media\MediaTools::createImageSelector(null, false, $img_array , true, 'custom_hidden['.$field->field_slur.']')!!}
            @else
                {!! \Atlantis\Helpers\Media\MediaTools::createImageSelector(null, false, '' , true, 'custom_hidden['.$field->field_slur.']')!!}
            @endif
            {!! Form::input('hidden', 'custom_hidden_id['.$field->field_slur.']', $field->id ) !!}
        </label>
    </div>
</div>
