<?php
require 'authenticate.php';

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

        <div class="aboutJuegaDiv">


             
        </div>
<br>
                <?php
                    include 'footer.php'; 
                    include 'loadJS.php'; 
                ?>
	</body>


</html>
<?php }; ?>