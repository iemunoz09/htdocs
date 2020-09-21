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

if (!$userInfo) {

	echo 'Invalid login'; ?>
	<html>
	<meta http-equiv="Refresh" content="0; url='logout.php'" />
	</html>
<?php
} else {
?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="0" />

		<title>&#161;JUEGA! - Profile</title>

	</head>

	<body>

		<?php
			include 'loadCSS.php'; 
			include 'juegaNavBar.php';	  
		?>

        <div class="profileDiv">

            <h1>Hi <?php echo $userInfo['nickname'] ?>!</h1>
            <p><img width="100" src=<?php echo $userInfo['nickname'] ?>></p>
            <p><strong>Last update:</strong> <?php echo $userInfo['updated_at'] ?></p>
            <p><strong>Contact:</strong> <?php echo $userInfo['email']."\t"; echo ! empty($user['email_verified']) ? '✓' : '✗' ?></p>
            <p><a href="logout.php">Logout</a></p>
             
            </div>
<br>
                <?php
                    include 'footer.php'; 
                    include 'loadJS.php'; 
                ?>
	</body>


</html>
<?php }; ?>