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

		<title>&#161;JUEGA! - Welcome</title>

	</head>

	<body>

		<?php
			include 'loadCSS.php'; 
			include 'juegaNavBar.php';	  
		?>

<div class="">
		<br><h2>&#161;Bienvenidos <span class="nickname"><?php echo $userInfo['nickname'] ?></span>!</h2>
		<br>
		<p>Juega provides tools to maximize your coaching experience <br>
		to faciliate the development and performance of your team.
		</p>
		<br>
		<h3>Select Team</h3>
		<br>
		
		<select id="team" class="getTeam" size="8">
			<option value="" id="loading" class="getTeamLoading" disabled>Loading...</option>
		</select>
		
		<br><br>
	</div>
		
		<div class="modal fade" id="modalNewTeam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <form class="" id="saveNewTeamForm" name="saveNewTeamForm" method="">				
					  <div class="modal-header text-center">
						<h4 class="modal-title w-100 font-weight-bold">Add New Team</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
					  </div>
					  <!-- Team Name; Color 1; Color 2; CreatedBy (saved in the background) -->
					  <div class="modal-body mx-3">
						<div class="md-form mb-5">
						  <i class="fas fa-envelope prefix grey-text"></i>
						  <input type="name" id="teamName" name="teamName" class="form-control validate">
						  <label data-error="wrong" data-success="right" for="teamName">Team Name</label>
						</div>

					  </div>
					  <div class="modal-footer d-flex justify-content-center">
						<button type+"button" id="saveTeamButton" class="btn btn-default">Save</button>
					  </div>
				  </form>
				</div>
			  </div>

		</div>

		<?php
			include 'footer.php'; 
			include 'loadJS.php'; 
		?>
	</body>


</html>
<?php }; ?>