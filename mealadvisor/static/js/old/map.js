ymap = {
  div: 'map',
  init: function(div) {
    // Create a lat/lon object

    var myPoint = new YGeoPoint(37.063020,-95.677013);
    // Create a map object 

    var map = new YMap($(ymap.div));
    // Display the map centered on a latitude and longitude 
    map.drawZoomAndCenter(myPoint, 15);

    // Add map type control
    //map.addTypeControl();
    map.addZoomLong();  //zoom
    // Set map type to either of: YAHOO_MAP_SAT YAHOO_MAP_HYB YAHOO_MAP_REG
    map.setMapType(YAHOO_MAP_REG);
    ymap.map = map;

    // investigate queues
    if (ymap.rss) {
      ymap.overlayRSS(ymap.rss);
    }
      // fqdn!
      //    ymap.map.addOverlay(new YGeoRSS('http://s.mymen.us/feed/latest.georss'))
      //    ymap.map.addOverlay(new YGeoRSS('/frontend_dev.php/feed/latest.georss'));
  },
  overlayRSS: function(rss) {
      // if ymap is not initialized we need to queue this...
      if (ymap.map) {
        ymap.map.addOverlay(new YGeoRSS(rss));
      } 
      else {
        ymap.rss = rss;
      }
    }
}
