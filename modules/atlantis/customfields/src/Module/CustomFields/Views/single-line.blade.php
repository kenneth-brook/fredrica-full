<div class="row">
    <div class="columns">
        <label for="{!! $field->field_slur !!}">{!! $field->field_label !!}<kbd>{!! $field->field_slur !!}</kbd>
            @if(is_null($value))
                {!! Form::input('text', 'custom_hidden['.$field->field_slur.']' , '') !!}
            @else
                {!! Form::input('text', 'custom_hidden['.$field->field_slur.']' , old($field->field_slur, isset($value->field_value) ? $value->field_value : '')) !!}
            @endif
            {!! Form::input('hidden', 'custom_hidden_id['.$field->field_slur.']', $field->id) !!}
        </label>
    </div>
</div>
