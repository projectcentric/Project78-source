<?php
if($_COOKIE['zusterid']=="" or $_COOKIE['zusterpass'] ==""){	#redirect als POST en Cookie leeg is
	header("Location: http://145.24.222.216?error=nologin");
}
$zuster_id= $_COOKIE['zusterid'];

$db = array ( 
    'host' => 'localhost', 
    'user' => 'root', 
    'pass' => '', 
    'dbname' => 'eldertracker_db' 
); 

if(!mysql_connect($db['host'], $db['user'], $db['pass'])) 
{ 
    echo 'Fout bij verbinden: ' .mysql_error(); 
} 
elseif(!mysql_select_db($db['dbname'])) 
{ 
   echo 'Fout bij selecteren database:' .mysql_error(); 
} 
$sql = "select * from zuster where zuster_id= $zuster_id"; 
if(!$res = mysql_query($sql)) 
{  
    	trigger_error(mysql_error().'<br />In query: '.$sql);
	header("Location: http://145.24.222.216?error=badcookie"); 
} 
$row = mysql_fetch_array($res);
$pass_hashcheck= md5($row[1]. "546464554");
if($_COOKIE['zusterpass']!=$pass_hashcheck){	#vergelijkt zusterpass hash met hash uit database
	header("Location: http://145.24.222.216?error=badcookiepass");
}

$device_id = $_GET["get_device"];

$newlatitgeo = $_GET["new_geo_latit"];
$newlnggeo = $_GET["new_geo_lng"];
$newradiusgeo = $_GET["new_geo_radius"];

if(isset($newlnggeo)){

$sql = "update users set geofence_center_lng='$newlnggeo', geofence_center_latit='$newlatitgeo', geofence_radius='$newradiusgeo' where zuster_id=$zuster_id";
if(!$res = mysql_query($sql)) 
{ 
    	trigger_error(mysql_error().'<br />In query: '.$sql); 
	header("Location: http://145.24.222.216?error=devicezusternotknown");
} 
}

$sql = "select * from users where device_id='1' and zuster_id=$zuster_id";
if(!$res = mysql_query($sql)) 
{ 
    	trigger_error(mysql_error().'<br />In query: '.$sql); 
	header("Location: http://145.24.222.216?error=devicezusternotknown");
} 
$row = mysql_fetch_array($res);

$latitgeo = $row["geofence_center_latit"];
$lnggeo = $row["geofence_center_lng"];
$radiusgeo = $row["geofence_radius"];
?> 

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initialscale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="ico/favicon.ico">

    <title>Elderly Tracker</title>

    <!-- Bootstrap core CSS -->
    `<link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
	<link href="layout/dashboard.css" rel="stylesheet">
	<link href="layout/instellingen.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>'
    <![endif]-->
  </head>
  <body>
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container-fluid">
        <div class="navbar-header">
	<a class="navbar-brand" href="/navigation">Elder Tracker Dashboard</a>
        </div>
      </div>
    </div>
	
	<div class="container">
    
     <div class="container-fluid">
	<div class="row">
	<div class="col-xs-12 col-md-5">
	Verander hier de geonfence voor al uw clienten
	</div>
	<div class="col-xs-12 col-md-7">
	
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=drawing"></script>
	<script> 



var map;
var orgCircle;

function HomeControl(controlDiv, map) {

  // Set CSS styles for the DIV containing the control
  // Setting padding to 5 px will offset the control
  // from the edge of the map
  controlDiv.style.padding = '5px';

  // Set CSS for the control border
  var controlUI = document.createElement('div');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '2px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Klik om te bevestigen';
  controlDiv.appendChild(controlUI);

  // Set CSS for the control interior
  var controlText = document.createElement('div');
  controlText.style.fontFamily = 'Arial,sans-serif';
  controlText.style.fontSize = '12px';
  controlText.style.paddingLeft = '4px';
  controlText.style.paddingRight = '4px';
  controlText.innerHTML = '<b>Bevestigen</b>';
  controlUI.appendChild(controlText);

  // Setup the click event listeners: simply set the map to
  // Chicago
  google.maps.event.addDomListener(controlUI, 'click', function() {
	window.location = "alggeofence?new_geo_latit="+orgCircle.getCenter().lat()+"&new_geo_lng="+orgCircle.getCenter().lng()+"&new_geo_radius="+orgCircle.getRadius();
  });
}
function initialize() {
var myLatlng = new google.maps.LatLng(<?php echo $latitgeo ?>,<?php echo $lnggeo ?>);	
	// Create the map.
  	var mapOptions = {
    		zoom: 13,
		//maxZoom: 16,
		//minZoom: 12,
    		center: myLatlng,
    		mapTypeId: google.maps.MapTypeId.ROADMAP
  		}
 		map = new google.maps.Map(document.getElementById('map-canvas'),
      		mapOptions);
 	

		orgCircle = new google.maps.Circle({
		center: new google.maps.LatLng(<?php echo $latitgeo ?>,<?php echo $lnggeo ?>),
		radius: <?php echo $radiusgeo ?>,
		fillColor: '#0000FF',
		fillOpacity: 0.35,
		map: map,
		strokeWeight: 0
		});

var drawingManager = new google.maps.drawing.DrawingManager({

    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [google.maps.drawing.OverlayType.CIRCLE,]},
    circleOptions: {
      fillColor: '#0000FF',
      fillOpacity: 0.3,
      strokeWeight: 2,
      clickable: false,
      editable: true,
      zIndex: 1
    }
  });

drawingManager.setMap(map);

google.maps.event.addListener(drawingManager, 'circlecomplete', function(circle){
circle.setEditable(false);
orgCircle.setCenter(circle.getCenter());
orgCircle.setRadius(circle.getRadius());
orgCircle.setEditable(true);
circle.setMap(null);
});

  // Create the DIV to hold the control and
  // call the HomeControl() constructor passing
  // in this DIV.
  var homeControlDiv = document.createElement('div');
  var homeControl = new HomeControl(homeControlDiv, map);

  homeControlDiv.index = 1;
  map.controls[google.maps.ControlPosition.TOP_RIGHT].push(homeControlDiv);
}

google.maps.event.addDomListener(window, 'load', initialize);

</script>
    	<div id="map-canvas" role="map-canvas">
	</div>
	</div>
	</div>
	</div>
	</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    	<script src="js/bootstrap.min.js"></script>
    	<script src="js/docs.min.js"></script>
	<script src="js/responsivecanvas"></script>
</body>
</html>

