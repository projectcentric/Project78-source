<?php
if($_GET["logout"]==true){
		setcookie('zusterpass','',time() - 3600);
		setcookie('zusterid','',time() - 3600);
}
if($_COOKIE['zusterid']!="" or $_COOKIE['zusterpass'] !=""){	#redirect als POST en Cookie leeg is

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
		setcookie('zusterpass','',time() - 3600);
		setcookie('zusterid','',time() - 3600);
		header("Location: http://145.24.222.216?error=badcookie"); 
	} 
	$row = mysql_fetch_array($res);
	$pass_hashcheck= md5($row[1]. "546464554");
	if($_COOKIE['zusterpass']!=$pass_hashcheck){	#vergelijkt zusterpass hash met hash uit databas
		setcookie('zusterpass','',time() - 3600);
		setcookie('zusterid','',time() - 3600);
		header("Location: http://145.24.222.216?error=badcookiepass");
	} else{
		header("Location: http://145.24.222.216/navigation");
	}
}

?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Dashboard Login</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="layout/login.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <form class="form-signin" role="form" action="navigation.php" method="post">
        <h2 class="form-signin-heading">ElderTracker Dashboard Login</h2>
        <input type="text" name="zuster_id" class="form-control" placeholder="Inlog ID" required autofocus>
        <input type="password" name="zuster_pass" class="form-control" placeholder="Wachtwoord" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Inloggen</button>
      </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>
