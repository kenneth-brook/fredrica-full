<div class ="form-field {{ str_replace(':', '-', $item->field_type) }}{{ $required }} {{$errors->first((string)$item->field_name) ? 'has-error' : '' }}">
  <div class="form-title">
    <label for="{!! $id !!}">
	    {{ $item->label }}
		<span class="error-msg a3-error-wrap">{{$errors->first((string)$item->field_name)}}</span>
    </label>
  </div>
  {!! $field !!}
</div>