@extends('atlantis::' . config('atlantis.frontend_shell_view'))


@section('content')

    @if (isset($header))
        {!! $header or '' !!}
    @endif
		
<div class="content-404 bg-light">
	<div class = "container">
		<div class = "pd-5 text-center">	
					
		@if (Session::has('data') && count(Session::get('data.results')) > 0)
		
			<h1>Did you mean:</h1> 
		@foreach (Session::get('data.results') as $res)
		
			<p>
				<a target="blank" href="{!! $res['url'] !!}">{{ $res['name'] }}</a>
			</p>
		@endforeach
		@else
		
			<h1>Sorry, can't find this page</h1>
		@endif
		{!! $content !!}
	
		</div>
	</div>
</div>
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

