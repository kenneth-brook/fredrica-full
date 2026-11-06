@extends('atlantis::' . config('atlantis.frontend_shell_view'))


@section('scripts')
@parent
<script src="/{{ config('atlantis.theme_path') }}/media/js/map.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyAoRNz5enqHeRufpal3jIo3CmfMRFsrFNY"></script>
<script type="text/javascript" src="/{{ config('atlantis.theme_path') }}/media/js/infobox.js"></script>

@stop

@section('content')
<?php if (isset($pageScripts)) : echo $pageScripts; endif; ?>
    @if (isset($header))
        {!! $header or '' !!}
    @endif 

        {!! $content or  '' !!} 

		@if (isset($contentBottom ))
	        {!! $contentBottom  or '' !!}
	    @endif
	    @if (isset($newsletter))
	        {!! $newsletter or '' !!}
	    @endif
	    <footer id="footer">
	    
	    @if (isset($footer_before))
	        {!! $footer_before or '' !!}
	    @endif    

	    @if (isset($footer))
	        {!! $footer or '' !!}
	    @endif
        </footer>
@stop