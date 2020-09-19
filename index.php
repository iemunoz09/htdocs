<?php
require 'vendor/autoload.php';
use Auth0\SDK\Auth0;

$auth0 = new Auth0([
  'domain' => 'juegagt.us.auth0.com',
  'client_id' => 'CALb7Sqt2G7wO1UaO0mszAR0oVw9nFFT',
  'client_secret' => '1UXD4lhbhyPGgm72kpxM5WNeskMaScW06XnTRDDrmpOW4Oddk6f6tFMQnwl_joDP',
  'redirect_uri' => 'http://localhost/',
  'scope' => 'openid profile email',
]);

$userInfo = $auth0->getUser();

?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="0" />

		<title>&#161;JUEGA! - Login</title>

		<link rel="stylesheet" type="text/css" href="/css/bootstrap 4.5/bootstrap.min.css" >
		<link rel="stylesheet" type="text/css" href="/css/appSite.css">

	</head>
	
	<body>
	
		<div class="containerStyle">
			<h1>&#161;JUEGA!</h1>
			<h2>GameTime</h2>
		</div>

<?php if (!$userInfo) { ?>
	<meta http-equiv="Refresh" content="0; url='login.php'" />
    User is not logged in <br>
    
	<a href="login.php">Log In</a>		
    
	<?php	} else { ?>
    // User is authenticated
    // See below for how to display user information
	// redirect to welcome.php 
	<meta http-equiv="Refresh" content="0; url='welcome.php'" />
	<a id="qsLogoutBtn" class="btn btn-warning btn-logout" href="logout.php">Logout</a>
	<?php };	?>


		</body>	
</html>