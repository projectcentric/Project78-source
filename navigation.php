<?php
$zuster_pass = $_POST["zuster_pass"];
$zuster_id = $_POST["zuster_id"];
$logged_in=true;
if($_COOKIE['zusterid']!="" && empty($zuster_id)){		#check voor cookie en vul als POST leeg is.
	$zuster_id = $_COOKIE['zusterid'];		
}
if($_COOKIE['zusterid']=="" && empty($zuster_id)){	#redirect als POST en Cookie leeg is
	header("Location: http://145.24.222.216?error=nologin");
	$logged_in=false;
}

if($logged_in!=false){
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
	header("Location: http://145.24.222.216?error=nozuster");
} 

$row = mysql_fetch_array($res);

$pass_hashcheck= md5($row[1]. "546464554"); 

if($_COOKIE['zusterpass'] !=""){ #check of zusterpass gevuld is in cookie
	if($_COOKIE['zusterpass']!=$pass_hashcheck){	#vergelijkt zusterpass hash met hash uit database
	header("Location: http://145.24.222.216?error=wrongcookie");
}
}else if($zuster_pass==$row[1]){      #anders post vergelijken met pass
			$pass_hash= md5($zuster_pass. "546464554");
			setcookie('zusterpass', $pass_hash);
			setcookie('zusterid', $zuster_id);
		}else{
	header("Location: http://145.24.222.216?error=wrongpass");
}

$sql = "select * from users where zuster_id= $zuster_id order by naam_eigenaar"; 

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
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
	<meta http-equiv="refresh" content="30">
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
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Navigatie uitbreiden</span>
            <span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/navigation">Eldertracker Dashboard</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/documentatie">Documentatie</a></li>
	<li><a href="alggeofence">Algemeen geofence</a></li>
        <li><a href="/index.php?logout=true">Uitloggen</a></li>  
	</ul>
        </div>
      </div>
    </div>
    
	
	<div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li><a href="/navigation">Alle bewoners</a></li>
            <li><a href="/escalaties">Escalaties<span 
			<?php 
			for($i=0;$i<$number_users;$i++){
			if($lost[$i]=='true'){		
				$a++;
			}
			}
			if($a>0){
			echo 'class="badge pull-right">';
			echo $a;
			}
			?>
			</span></a></li>
          </ul>
        </div>
  
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-4 col-sm-offset-3 col-md-5 col-md-offset-2 main">
          <h2 class="sub-header">Alle Bewoners</h2>
		  <div class="input-group"> <span class="input-group-addon">Zoek</span>

    <input id="filter" type="text" class="form-control" placeholder="Type uw zoekterm...">
</div>
 <table class="table">
    <thead>
        <tr>
            <th>Device ID</th>
			<th>Naam</th>
			<th>Status</th>
			
        </tr>
    </thead>
    <tbody class="searchable">
     
		
		<?php
		for($i=0;$i<$number_users;$i++){
		echo '<tr>';
		if($lost[$i]=='true'){	
		echo '<td class="danger">';
		}
		else{
		echo '<td class="success">';
		}
		echo $device[$i];
		echo '</td>';
		if($lost[$i]=='true'){	
		echo '<td class="danger">';
		}
		else{
		echo '<td class="success">';
		}
		echo '<a href="';
		echo "instellingen?get_device=$device[$i]";
		echo '">';
		echo $naam[$i];
		echo '</a>';
		echo '</td>';
		if($lost[$i]=='true'){	
		echo '<td class="danger">';
		echo 'Verdwaald';
		}
		else{
		echo '<td class="success">';
		echo 'Prima';
		}
		echo '</tr>';
		
		}?>
		
    </tbody>
</table>
		  

      </div>
    </div>
	
	</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/docs.min.js"></script>
	<script src="js/search.js"></script>
	
  </body>
</html>
