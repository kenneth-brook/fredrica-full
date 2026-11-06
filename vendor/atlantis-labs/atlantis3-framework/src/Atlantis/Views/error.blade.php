@extends('atlantis-admin::admin-shell')

@section('title')
@lang('admin::views.Error') | @lang('admin::views.A3 Administration') | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
@parent
{{-- Add scripts per template --}}
@stop

@section('styles')
@parent
{{-- Add styles per template --}}
@stop

@section('content')
<div class="row">
	<div class="columns small-12">
		<div class="callout alert">
			<h5>{{ $error }}</h5>
		</div>
	</div>
</div>
@stop