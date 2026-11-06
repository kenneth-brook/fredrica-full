
<div id="map" style="width: 100%; height: 650px;"></div>

<?php
$z = 1;
foreach ($listings as $l):

  $tmb = '';

  $imgs = '';
  if ($l->images != '' ) {
    $imgs = explode(',' , $l->images);
  }

  if ($imgs != ''){
    if (!is_null(MediaTools::getImage($imgs[0]))){

      $tmb = MediaTools::getImage($imgs[0]);
      $tmb = $tmb->thumbnail;
    }
    else{
      $tmb = "/resources/media/a2users/".$imgs[0];
    }
  }
  ?>
  <div class="infobox-wrapper" style="display: none;">
    <div class="restateinfo" id="infobox-homes-<?= $z; ?>">
      <div class="restateinfo-header">Home Site #<?= $l->home_site_number; ?></div>
      <div class="restateinfo-text">
        <div class="restateinfo-desc">
          <span  class="label">Address</span>
          <span  class="address"><?= $l->title; ?></span>
          <table>
            <tr><td>Price:</td><td><?= str_replace('$', '&#36;', $l->price); ?></td></tr>
            <tr><td>View:</td><td><?= $l->lot_type; ?></td></tr>
            <tr><td>Footage:</td><td><?= $l->heated_sq_ft; ?></td></tr>
            <tr><td>Bedrooms:</td><td><?= $l->bedrooms; ?></td></tr>
            <tr><td>Bath:</td><td><?= $l->full_baths; ?> Full, <?= $l->half_baths; ?> Half</td></tr>
            <tr><td>Furnished:</td><td><?php
            if ($l->furnished == 1) {
              echo 'Yes';
            } else {
              echo 'No';
            }
            ?></td></tr>
            <tr><td>Acreage:</td><td><?= $l->acres; ?></td></tr>
            <tr><td>Architect:</td><td><?= $l->architect; ?></td></tr>
            <tr><td>Builder:</td><td><?= $l->builder; ?></td></tr>
          </table>
        </div>
        <div class="restateinfo-more">
          <img src="<?= $tmb; ?>">
          <a class="restateinfo-btn" href="/st-simons-homes-for-sale/<?= $l->url; ?>" target="_blank">More info</a>
        </div>
        <div style="clear:both;"></div>
      </div>
      <div class="restateinfo-footer"></div>
    </div>
  </div>
  <?php
  $z++;
endforeach;
?>

<?php
//$z = 1;

foreach ($lots as $s):


  $patterns = array(" ", ";", "@", "?", ":", "=", "&", "<", ">", '"', "#", "[", "]", "%", "{", "}", "|", "^", "~", "/", "\\", "`", "$", "'");

  $url = '/golf-course-home-sites' . str_replace($patterns, "-", $s->location) . "-" . $s->lot_number . "-" . $s->id;

  $lot_tmb = '';

  $imgs = '';
  if ($s->imgs != '' ) {
    $imgs = explode(',' , $s->imgs);
  }

  if ($imgs != ''){
    if (!is_null(MediaTools::getImage($imgs[0]))){

      $lot_tmb = MediaTools::getImage($imgs[0]);
      $lot_tmb = $lot_tmb->thumbnail;
    }
    else{
      $lot_tmb = "/resources/media/a2users/".$imgs[0];
    }
  }
  ?>
  <div class="infobox-wrapper" style="display: none;">
    <div class="restateinfo" id="infobox-lots-<?= $z; ?>">
      <div class="restateinfo-header">Lot #<?= $s->lot_number; ?><?=$s->lot_number_ext; ?></div>
      <?php if ($lot_tmb != "") : ?>
        <div class="restateinfo-text">
        <?php else: ?>
          <div class="restateinfo-text restateinfo-text-noimage">
          <?php endif; ?>
          <div class="restateinfo-desc">
            <span  class="label">View:</span>
            <span  class="address"><?= $s->location; ?></span>
            <table>
              <tr><td class="price-label">Price:</td><td class="price"><?= str_replace('$', '&#36;', $s->price); ?></td></tr>
              <tr><td class="acreage-label">Acreage:</td><td class="acreage"><?= $s->acreage; ?></td></tr>
              <tr><td colspan="2"><?= $s->description; ?></td></tr>
            </table>
          </div>
          <?php if ($lot_tmb != ""): ?>
            <div class="restateinfo-more">
              <img src="<?= $lot_tmb; ?>">
              <a class="restateinfo-btn" href="/golf-course-home-sites/{{ $url }}" target="_blank">More info</a>
            </div> 
          <?php endif; ?>
          <div style="clear:both;"></div>
        </div>
        <div class="restateinfo-footer"></div>
      </div>
    </div>
    <?php
    $z++;
    endforeach;?>

    <script type="text/javascript">

  //homes
  var home_markers = new Array();
  home_markers['golf'] = new Array();
  home_markers['lake'] = new Array();
  home_markers['marsh'] = new Array();
  home_markers['river'] = new Array();
  home_markers['rookery'] = new Array();
  home_markers['wooded'] = new Array();

  //lots
  var lot_markers = new Array();
  lot_markers['golf'] = new Array();
  lot_markers['lake'] = new Array();
  lot_markers['marsh'] = new Array();
  lot_markers['river'] = new Array();
  lot_markers['rookery'] = new Array();
  lot_markers['wooded'] = new Array();


  
  document.addEventListener("DOMContentLoaded", function(){

    var locations = [];
    var lots = [];
    var ibs = [];
    //var ib = new InfoBox();
    <?php
    foreach ($listings as $l):
      ?>
      locations.push(['<?= addslashes($l->title); ?> ', '<?= $l->lat; ?>', '<?= $l->lng; ?>', '<?= !empty($l->map_category) ? $l->map_category : "0"; ?>']);
      console.log(locations);
      <?php
    endforeach;

    foreach ($lots as $s):
      ?>
      lots.push(['<?= $s->lot_number ?>', '<?= $s->lat; ?>', '<?= $s->lng; ?>', '<?= !empty($s->map_category) ? $s->map_category : "0"; ?>']);
        <?php
      endforeach;
      ?>

 //init the map
 mapinit();

 var k = 1;
 for (i = 0; i < locations.length; i++) {


  var icon_image = {
    url: '/resources/media/images/icons/map-homes-' + locations[i][3] + '.png',
    shadow: '/resources/media/images/icons/map-shadow.png',
    size: new google.maps.Size(36, 36),
    origin: new google.maps.Point(0, 0),
    anchor: new google.maps.Point(0, 36)
  };
  thisMarker = new google.maps.Marker({
    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
    icon: icon_image,
    map: map
  });

  var ib = new InfoBox();

  ibs.push(ib);

  makeInfoBox(thisMarker, k, locations[i][1], locations[i][2], 'homes', ib);

  if (locations[i][3] !== "0") {
    home_markers[locations[i][3]].push(thisMarker);
  }

  k++;

}

    //k = 1;
    for (i = 0; i < lots.length; i++) {


      var icon_image = {
        url: '/resources/media/images/icons/map-lots-' + lots[i][3] + '.png',
        shadow: '/resources/media/images/icons/map-shadow.png',
        size: new google.maps.Size(36, 36),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(0, 36)
      };
      thisMarker = new google.maps.Marker({
        position: new google.maps.LatLng(lots[i][1], lots[i][2]),
        icon: icon_image,
        map: map
      });

      var ib = new InfoBox();

      ibs.push(ib);

      makeInfoBox(thisMarker, k, lots[i][1], lots[i][2], 'lots', ib);


      if (lots[i][3] !== "0") {
        lot_markers[lots[i][3]].push(thisMarker);
      }

      k++;
    }


    var iconCat = ["river", "marsh", "lake", "golf", "rookery"];
    var buttonCat = ["maps-river", "maps-marsh", "maps-lake", "maps-golf", "maps-rookery"];

    var activeCat = '';
    var active = 0;

    $("a[class^='maps-']").click(function() {

      var curCat = $(this).attr("class").split(' ')[0].split("maps-")[1];

      if( curCat == "rookery" ) {
        curCat = "wooded";
      }

     //console.log( curCat );

     if (activeCat == '') {
      activeCat = curCat;
    }


    if (curCat === activeCat) {

      if ($(this).hasClass("active") && active === 1) {

        showAllMarkers();
        active = 0;

        $(this).removeClass("active");
      }
      else {
        showMarkers(curCat);
        active = 1;
        $(this).addClass("active");
      }

    }
    else {


      $.each(buttonCat, function(i, el) {
        var thisBtn = el.split("maps-")[1];
        if (thisBtn !== curCat) {
          $("." + el).removeClass("active");
        }
        else {
          $("." + el).addClass("active");
        }

      });

      showMarkers(curCat);

      activeCat = curCat;

      active = 1;

    }

  });


    

    function showMarkers(group) {

      for (gr in home_markers) {

        if (gr !== group) {

          for (i = 0; i < ibs.length; i++) {
            ibs[i].close();
          }

          for (i = 0; i < home_markers[gr].length; i++) {
            home_markers[gr][i].setMap(null);
          }

        }

        if (gr === group) {

          for (i = 0; i < home_markers[gr].length; i++) {
            home_markers[gr][i].setMap(map);
          }
        }

      }

      for (lt in lot_markers) {

        if (lt !== group) {

          for (i = 0; i < ibs.length; i++) {

            ibs[i].close();
          }

          for (i = 0; i < lot_markers[lt].length; i++) {

            lot_markers[lt][i].setMap(null);
          }
        }

        if (lt === group) {

          for (i = 0; i < lot_markers[lt].length; i++) {
            lot_markers[lt][i].setMap(map);
          }

        }
      }

    }

    function showMarkerGroup(group) {

      for (gr in home_markers) {

        if (gr !== group) {

          for (i = 0; i < ibs.length; i++) {

            ibs[i].close();
          }

          for (i = 0; i < home_markers[gr].length; i++) {

            home_markers[gr][i].setMap(null);

          }
        }
      }

      for (lt in lot_markers) {

        if (lt !== group) {

          for (i = 0; i < ibs.length; i++) {

            ibs[i].close();
          }

          for (i = 0; i < lot_markers[lt].length; i++) {

            lot_markers[lt][i].setMap(null);
          }

        }
      }

    }


    function showAllMarkers() {

      for (gr in home_markers) {

        for (i = 0; i < home_markers[gr].length; i++) {

          home_markers[gr][i].setMap(map);
        }
      }

      for (lt in lot_markers) {

        for (i = 0; i < lot_markers[lt].length; i++) {

          lot_markers[lt][i].setMap(map);
        }
      }

    }


    function makeInfoBox(marker, id, lat, lng, viewGroup, ib) {

      var options = {
        disableAutoPan: false,
        maxWidth: 400,
        pixelOffset: new google.maps.Size(-181, -36),
        zIndex: null,
        alignBottom: true,
        boxStyle: {
          //background: "url('http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/examples/tipbox.gif') no-repeat",
          //background: "#fff",
          opacity: 1,
          width: "400px"
        },
        closeBoxMargin: "6px 4px 2px 2px",
        closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
        infoBoxClearance: new google.maps.Size(1, 1)
      };


      google.maps.event.addListener(marker, 'click', (function() {

        for (i = 0; i < ibs.length; i++) {

          ibs[i].close();
        }

        ib.setOptions(options);

        //we have to clone here because its destroying the original element
        var cloned = $("#infobox-" + viewGroup + "-" + id).html();

        ib.setContent(cloned);

        ib.open(map, marker, this);

        map.panTo(marker.getPosition());

      }));

    }

  });
</script>