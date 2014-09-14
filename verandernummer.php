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
$sql = "select * from users where device_id=$device_id and zuster_id=$zuster_id";
if(!$res = mysql_query($sql)) 
{ 
    trigger_error(mysql_error().'<br />In query: '.$sql); 
header("Location: http://145.24.222.216?error=devicezusternotknown");
} 
$row = mysql_fetch_array($res);
$latit = $row["device_lng"];
$lng = $row["device_latit"];
$nameowner = $row["naam_eigenaar"];

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
	<ul class="list-group">
       		<a href=verandergeofence?get_device=<?php echo $_GET["get_device"] ?>><li class="list-group-item list-group-item-success">Verander Geofence</li></a>
		<a href=#><li class="list-group-item list-group-item-success">Verander nummer</li></a>
		<div class="row">
		<div class="col-xs-12 col-md-5>
			<div class="input-group">
			<input type="text" class="form-control">
		</div>
		
  </div
		
		<a href=#><li class="list-group-item list-group-item-success">Volg Persoon</li></a>
	</ul>
	<div class="col-sm-12 col-md-5">
            <img src="http://1.gravatar.com/avatar/7bc150bd19711e6dcf4f9ddb28cf56cb?s=1024&d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D1024&r=G"
            alt="" class="img-rounded img-responsive" />
        </div>
        <div class="col-sm-12 col-md-5">
            <blockquote>
                <p><?php echo $nameowner ?></p>
            </blockquote>
            <p> <i class="glyphicon glyphicon-envelope"></i> <?php echo $nameowner ?>@eldertracker.com
                <br
                />
                <br /> <i class="glyphicon glyphicon-gift"></i> January 30, 1974</p>
        </div>
	</div>
	
	
	
	
	<div class="col-xs-12 col-md-7">
	
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=drawing&libraries=geometry"></script>
	<script> 
	
function initialize() {

var myLatlng = new google.maps.LatLng(<?php echo $latit ?>,<?php echo $lng ?>);	
	// Create the map.
  	var mapOptions = {
    		zoom: 13,
		maxZoom: 16,
		minZoom: 12,
    		center: myLatlng,
    		mapTypeId: google.maps.MapTypeId.ROADMAP
  		}
 		var map = new google.maps.Map(document.getElementById('map-canvas'),
      		mapOptions);
 		var marker = new google.maps.Marker({
		position: myLatlng,
		map: map,
		title: "<?php echo $nameowner ?>"
		});

		var orgcircle = new google.maps.Circle({
		center: new google.maps.LatLng(<?php echo $latitgeo ?>,<?php echo $lnggeo ?>),
		radius: <?php echo $radiusgeo ?>,
		fillColor: '#FF0000',
		fillOpacity: 0.35,
		map: map,
		strokeWeight: 0
		});
		if(google.maps.geometry.spherical.computeDistanceBetween(orgcircle.getCenter(), myLatlng) < orgcircle.getRadius()){
		orgcircle.setOptions({fillColor: '#00FF00', fillOpacity: 0.4});
		}
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

