<?php 

$db = array ( 
    'host' => 'localhost', 
    'user' => 'root', 
    'pass' => '', 
    'dbname' => 'eldertracker_db' 
); 

$latng = $_GET["get_latng"];
$latit = $_GET["get_latit"];
$verdwaald = $_GET["verdwaald"];
$device_id = $_GET["get_device_id"];
echo "$latng";
echo "$latit";
echo "$device_id";
echo "$verdwaald";

if(!mysql_connect($db['host'], $db['user'], $db['pass'])) 
{ 
    echo 'Fout bij verbinden: ' .mysql_error(); 
} 
elseif(!mysql_select_db($db['dbname'])) 
{ 
   echo 'Fout bij selecteren database:' .mysql_error(); 
} 

$sql = "update users set device_lng=$latng, device_latit=$latit, verdwaald='$verdwaald' where device_id=$device_id";  

if(!$res = mysql_query($sql)) 
{  
    trigger_error(mysql_error().'<br />In query: '.$sql); 
	echo "poep";
}

?>


