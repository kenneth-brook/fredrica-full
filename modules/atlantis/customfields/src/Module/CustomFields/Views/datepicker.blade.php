<div class="row">
    <div class="columns">
        <label for="{!! $field->field_slur !!}">{!! $field->field_label !!}<kbd>{!! $field->field_slur !!}</kbd>
                    <span class="fa fa-calendar dtp-wrapper">
                {!! Form::input('text', 'custom_hidden['.$field->field_slur.']', !empty($value->field_value) ? $value->field_value: '', ['class' => 'dtp', 'id'=>''. $field->field_slur .'']) !!}
                {!! Form::input('hidden', 'custom_hidden_id['.$field->field_slur.']', $field->id ) !!}
            </span>
        </label>
    </div>
</div>