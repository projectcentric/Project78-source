<?php 

$db = array ( 
    'host' => 'localhost', 
    'user' => 'root', 
    'pass' => '', 
    'dbname' => 'eldertracker_db' 
); 


$device_id = $_GET["get_device_id"];

if(!mysql_connect($db['host'], $db['user'], $db['pass'])) 
{ 
    echo 'Fout bij verbinden: ' .mysql_error(); 
} 
elseif(!mysql_select_db($db['dbname'])) 
{ 
   echo 'Fout bij selecteren database:' .mysql_error(); 
} 


$sql = "SELECT geofence_center_latit, geofence_center_lng, geofence_radius, zuster_telnummer FROM users WHERE device_id= $device_id ";  

if(!$res = mysql_query($sql)) 
{  
    trigger_error(mysql_error().'<br />In query: '.$sql); 

}
$row = mysql_fetch_array($res);

echo $row["geofence_center_latit"];
echo "\n";
echo $row["geofence_center_lng"];
echo "\n";
echo $row["geofence_radius"];
echo "\n";
echo $row["zuster_telnummer"];


?>


