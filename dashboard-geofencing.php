<?php
$db = array ( 
    'host' => 'localhost', 
    'user' => 'root', 
    'pass' => '', 
    'dbname' => 'eldertracker_db' 
); 
$zuster_pass = $_POST["zuster_pass"];
$zuster_id = $_POST["zuster_id"];
if(!mysql_connect($db['host'], $db['user'], $db['pass'])) 
{ 
    #echo 'Fout bij verbinden: ' .mysql_error(); 
} 
elseif(!mysql_select_db($db['dbname'])) 
{ 
   #echo 'Fout bij selecteren database:' .mysql_error(); 
} 

$sql = "select * from zuster where zuster_id= $zuster_id";  

if(!$res = mysql_query($sql)) 
{ 
    trigger_error(mysql_error().'<br />In query: '.$sql); 
    header("Location: http://145.24.222.216?error=true");
} 
$row = mysql_fetch_array($res);

if($zuster_pass!=$row[1] or empty($zuster_id)){
	header("Location: http://145.24.222.216?error=true");
}
$sql = "select * from users where zuster_id= $zuster_id"; 

if(!$res = mysql_query($sql)) 
{  
    trigger_error(mysql_error().'<br />In query: '.$sql); 
} 
while ($row = mysql_fetch_assoc($res))
{
$naam[]=$row['naam_eigenaar'];
$lost[]=$row['verdwaald'];
$device[]=$row['device_id'];
}
$number_users = count($naam);
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="ico/favicon.ico">

    <title>Elderly Tracker</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="layout/dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

	   <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Elder Tracker Dashboard</a>
        </div>
      </div>
    </div>
    
	
	
  
    <div class="container-fluid">
      
	
	 <div class="row">
		  <div class="col-sm-4 col-sm-offset-3 col-md-5 col-md-offset-2 main">
           <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script>
var elderly = {};
elderly['Jan'] = {
  center: new google.maps.LatLng(51,52),
  area: 200
};
var elderlyCircle;

function initialize() {
  // Create the map.
  var mapOptions = {
    zoom: 13,
    center: new google.maps.LatLng(51, 52),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  var map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);


  // Construct the circle for each value in elderly.
  // Note: We scale the area of the circle based on the area
  for (var elder in elderly) {
    var areaOptions = {
      strokeColor: '#2219B2',
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: '#00d4ff',
      fillOpacity: 0.35,
      map: map,
      center: elderly[elder].center,
      radius:  200,
	  draggable: true,
	  editable: true
    };
    // Add the circle for the elderly to the map.
    elderlyCircle = new google.maps.Circle(areaOptions);
	map.fitBounds(elderlyCircle.getBounds());
  }
  
}

google.maps.event.addDomListener(window, 'load', initialize);

</script>
		
		

      </div>


      </div>
	
	</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/docs.min.js"></script>
	
  </body>
</html>
