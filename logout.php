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

$auth0->logout();
$return_to = 'http://' . $_SERVER['HTTP_HOST'];
$logout_url = sprintf('http://%s/v2/logout?client_id=%s&returnTo=%s', 'juegagt.us.auth0.com', 'CALb7Sqt2G7wO1UaO0mszAR0oVw9nFFT', $return_to);
header('Location: ' . $logout_url);
die();

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="0" />

		<title>&#161;JUEGA! - Home</title>

		<link rel="stylesheet" type="text/css" href="/css/bootstrap 4.5/bootstrap.min.css" >
		<link rel="stylesheet" type="text/css" href="/css/appSite.css">

	</head>
	
	<body>
	
		<div class="containerStyle">
			<h1>&#161;JUEGA!</h1>
			<h2>GameTime</h2>
			<h3>Sign Out Successful</h3>
		</div>

		</body>	
</html>