
function initialize() {
var myLatlng = new google.maps.LatLng(latit, lng);
  // Create the map.
  var mapOptions = {
    zoom: 13,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
 var map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);
 		var marker = new google.maps.Marker({
		position: myLatlng,
		map: map,
		title: nameOwner
		});
  }
 

google.maps.event.addDomListener(window, 'load', initialize);


