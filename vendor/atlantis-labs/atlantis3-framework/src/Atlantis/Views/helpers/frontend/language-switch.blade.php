<select name="" id=""  onchange="window.location=this.value;">
	@foreach ($languages as $k => $l)
		<option {{ \Lang::getLocale() == $k ? 'selected disabled' : ''}} value="{{ url('/') }}/{{ $l }} ">{{ trans('admin::languages-native.'.$k) }}</option>
	@endforeach
</select>