<?php
require 'components/authenticate.php';

if (!$userInfo) {
	echo 'Invalid login';
	header('Location: logout.php');
    exit();
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
			include 'components/loadCSS.php'; 
			include 'components/juegaNavBar.php';	  
		?>

        <div class="profileDiv">

            <h1>Hi <?php echo $userInfo['name'] ?>!</h1>
            <p><img width="100" src=<?php echo $userInfo['picture'] ?>></p>
			<p><strong>Username:</strong> <?php echo $userInfo['nickname'] ?></p>
			<p><strong>UserID:</strong> <?php echo $userInfo['sub'] ?></p>
            <p><strong>Last update:</strong> <?php echo $userInfo['updated_at'] ?></p>
			<p><strong>Contact:</strong> <?php echo $userInfo['email']."\t"; echo (!empty($userInfo["email_verified"]) ? '✓' : '✗') ?></p>
            <p><a href="logout.php">Logout</a></p>
             
        </div><br>
		<?php
			include 'components/footer.php'; 
			include 'components/loadJS.php'; 
		?>
	</body>
</html>
<?php }; ?>