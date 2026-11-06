<!doctype html>
<html class="js">
  <head>

    @section('headTags')    
        <title>@yield('title'){!! $_page->seo_title !!}</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="robots" content="index, follow">
        <link rel="shortcut icon" href="/{{ config('atlantis.theme_path') }}/favicon.ico">
        @foreach($_headTags as $tag)
        {!! $tag !!}
        @endforeach
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    @show

    @section('styles')

        <link href="/{{ config('atlantis.theme_path') }}/media/css/style.css" rel="stylesheet" type="text/css"> 

    @foreach($_styles as $style)
    {!! $style !!}
    @endforeach
    @show

  </head>
  <body class="{!! $body_class !!}">
    
<!-- Google Tag Manager --> 
<noscript><﻿iframe src="//www.googletagmanager.com/ns.html?id=GTM-M7T7V6" 
height="0" width="0" style="display:none;visibility:hidden"><﻿/iframe></noscript> 
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': 
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], 
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src= 
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f); 
})(window,document,'script','dataLayer','GTM-M7T7V6');</script> 
<!-- End Google Tag Manager -->

    {!! MenuNavigation::setShortcutBar() !!}

    @section('tracking_header')
    {!! $tracking_header !!}
    @show

    @section('content')
    @show

    @section('scripts')
    <script type="text/javascript" src="/{{ config('atlantis.theme_path') }}/media/js/jquery.cycle2.min.js"></script>
    <script type="text/javascript" src="/{{ config('atlantis.theme_path') }}/media/js/tinynav.min.js"></script>
    <script type="text/javascript" src="/{{ config('atlantis.theme_path') }}/media/js/jquery.flexnav.min.js"></script>
    {{ $pageScripts or '' }}
    @foreach($_scripts as $script)
    {!! $script !!}
    @endforeach
    @show

    @section('js')
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $(".flexnav").flexNav();
            if ($("span.success")) {
                var successMessage = $("span.success").html();
                $("span.success").html('');
               /* $("span.success").colorbox({
                    html: '<h4 style="padding: 30px;">'+successMessage+'</h4>',
                    open:true
                });*/
            }
        });
    </script>
    <script language="javascript" type="text/javascript">
        $(document).ready(function() {    
            $("#navigation .mainNav").tinyNav({
                active: 'active'
            });
        });
    </script>
   @stack('inline-js')
    <script type="application/ld+json">
        {
          "@context": "http://schema.org",
          "@type": "GatedResidenceCommunity",
          "address": {
            "@type": "PostalAddress",
            "addressLocality": "Saint Simons Island",
            "addressRegion": "GA",
            "postalCode": "31522",
            "streetAddress": "150 Frederica Rd"
        },
        "geo" : {
          "@type" : "GeoCoordinates",
          "latitude" : "31.217398",
          "longitude" : "-81.374777"
      },
      "name" : "Frederica Realty",
      "description" : "The epitome of luxury, privacy and security, Frederica is a 3,000 acre private community that blends St. Simons real estate with top-of-the-line luxury amenities, all surrounded by the natural splendor of coastal Georgia's unique landscape. Here, Frederica residents escape to a stress-free lifestyle.",
      "telephone": "(912) 634-1500",
      "url": "http://www.fredericaliving.com/",
      "image" : "http://www.fredericaliving.com/resources/media/images/homepage/img-2.jpg",
      "sameAs" : [
      "http://www.seaislandproperties.com/communities/frederica",
      "http://tratonhomes.com/community/frederica/"
      ]
  }
</script>
    @foreach( $_js as $js )
    {!! $js !!}
    @endforeach
    @show

     

  </body>
</html>
