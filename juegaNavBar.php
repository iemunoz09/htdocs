<?php
echo '
<!-- Vertical bar with logo on left and hamburger menu on right-->
	<nav class="navbar  .navbar-center">
		<div id="logoContainer" class="navbar-brand">
			<img src="img/soccerBall.jpg" class="navbrand_img">
			<div class="logoCenter"><a id="logoText" href="index.php">&#161;JUEGA!</a></div>
		</div>

		<button class="navbar-light navbar-toggler" type="button" data-toggle="collapse" data-target="#juegaNav" aria-controls="juegaNav" aria-expanded="true" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
	</nav>  

	<div class="collapse navbar-collapse" id="juegaNav">
		<div class="navbar-nav" style="text-responsive">
			<a class="nav_sections active" href="welcome.php">Home</a>
			<a class="nav_sections" data-toggle="modal" href="#modalSelectTeam">Team</a>
			<a class="nav_sections" href="profile.php">Profile</a>							
			<a class="nav_sections" href="aboutUs.php">About &#161;JUEGA!</a>	
			<a id="qsLogoutBtn" href="logout.php">Logout</a>									
		</div>
	</div>
	
	<div class="modal fade" id="modalSelectTeam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	  
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div>
					<h3>Select Team</h3>
					<br>

					<select id="navTeam" class="getTeam" size="8">
						<option value="" id="loadingNav" class="getTeamLoading" disabled>Loading...</option>
					</select>

					<br><br>
				</div>
			</div>
		</div>
	</div>'
	 
?>