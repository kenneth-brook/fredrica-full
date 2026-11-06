
var map;

function mapinit( ) {

    var fredericaOptions = {
      getTileUrl: function(coord, zoom) {
        return "/resources/media/map/tiles/"+zoom+"_" + coord.x + "_" + coord.y + ".png";
        //return  "/media/tile.php?f=" + zoom + "_" + coord.x + "_" + coord.y + ".png";
      },
      tileSize: new google.maps.Size(256, 256),
      isPNG: true,
      minZoom: 2,
      maxZoom: 4,
      name: 'frederica'
    };

    var fredericaMapType = new google.maps.ImageMapType(fredericaOptions);
    
    //var mapCenter = new google.maps.LatLng( 0, 0 );
    var mapCenter = new google.maps.LatLng( 14.944784875088372, 0 );


    var options = {
      //center: new google.maps.LatLng(84.75849837945889, -176.5008544921875),
      center: mapCenter,
      mapTypeControlOptions: {
        mapTypeIds: ['frederica', google.maps.MapTypeId.ROADMAP]
                //mapTypeId: google.maps.MapTypeId.ROADMAP,
      },
      backgroundColor: '#FFFFFF',
      mapTypeControl: false,
      panControl: false,
      scrollwheel: false,
      streetViewControl: false,
      zoomControl: true,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL,
        position: google.maps.ControlPosition.TOP_LEFT
      },
      zoom: 2
    };

    map = new google.maps.Map(document.getElementById("map"), options);

    map.setMapTypeId('frederica');

    map.mapTypes.set('frederica', fredericaMapType);

    map.overlayMapTypes.insertAt(0, fredericaMapType);

    google.maps.event.addListener(map, 'zoom_changed', function()
    {
      if (map.getZoom() < 2 || map.getZoom() > 4) {
        //alert("You cannot zoom out any further");
        map.setZoom(4);
      }
    });



    /*
    var marker = new google.maps.Marker({
      position: mapCenter,
      map: map,
      draggable: true,
      animation: google.maps.Animation.DROP,
      title: "Drag me"
    });

    google.maps.event.addListener(marker, 'dragend', function()
    {
    
      console.log( marker.getPosition().lat() ); 
      console.log( marker.getPosition().lng() );
       $("#" + lat ).text( marker.getPosition().lat() ); 
       $("#" + lng).text( marker.getPosition().lng() );
        
        //for edit pages
        
        $("#lat").val( marker.getPosition().lat() ); 
        $("#lng").val( marker.getPosition().lng());
       
        
    });

    marker.setMap(map);
*/

  }
 