@extends('atlantis::' . config('atlantis.frontend_shell_view'))

@section('content')

   @if (isset($header))
        {!! $header or '' !!}
    @endif
     @if (isset($Hero))
        {!! $Hero or '' !!}
    @endif
   
    @if (isset($contentTop ))
        {!! $contentTop  or '' !!}
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
