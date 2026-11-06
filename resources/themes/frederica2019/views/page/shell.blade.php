<!doctype html>
<html class="js" lang="en">
  <head>

    @section('headTags')    
        <title>@yield('title'){!! $_page->seo_title !!}</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="robots" content="index, follow">
        @if (isset($head))
	        {!! $head or '' !!}
	    @endif
	    <meta name="msapplication-TileColor" content="#ffffff">
		<meta name="theme-color" content="#ffffff">
        <link rel="shortcut icon" href="https://fredericaliving.com/resources/themes/frederica2019/favicon.ico">
        <link rel="apple-touch-icon" sizes="180x180" href="https://fredericaliving.com/resources/themes/frederica2019/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="https://fredericaliving.com/resources/themes/frederica2019/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="https://fredericaliving.com/resources/themes/frederica2019/favicon-16x16.png">		
		<link rel="mask-icon" href="https://fredericaliving.com/resources/themes/frederica2019/safari-pinned-tab.svg" color="#ffffff">		
        @foreach($_headTags as $tag)
        {!! $tag !!}
        @endforeach
        
    @show
    
    @section('styles')
    
        <link href="https://fredericaliving.com/resources/themes/frederica2019/assets/css/vendor.css" rel="stylesheet" type="text/css">
        <link href="https://fredericaliving.com/resources/themes/frederica2019/assets/css/main.css" rel="stylesheet" type="text/css"> 


    @foreach($_styles as $style)
    {!! $style !!}
    @endforeach
    @show

  </head>
  <body class="{!! $body_class !!}">    

    {!! MenuNavigation::setShortcutBar() !!}

    @section('tracking_header')
    {!! $tracking_header !!}
    @show

    @section('content')
    @show

    @section('scripts')
    <link href = "https://fonts.googleapis.com/css?family=Lato:300,400,400i,700,700i&display=swap"  rel="stylesheet" type="text/css">
    <link href = "https://fonts.googleapis.com/css?family=Petit+Formal+Script&display=swap"  rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://fredericaliving.com/resources/themes/frederica2019/assets/js/vendor.js" defer></script>
    <script src="https://fredericaliving.com/resources/themes/frederica2019/assets/js/main.js" defer></script>
    {{ $pageScripts or '' }}
    @foreach($_scripts as $script)
    {!! $script !!}
    @endforeach
    @show

    @section('js')
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
