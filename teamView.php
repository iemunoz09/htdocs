<?php

require 'authenticate.php';

// ini_set('display_errors', 1); 
// ini_set('display_startup_errors', 1); 
// error_reporting(E_ALL);

if (!$userInfo) {

	echo 'Invalid login';
	header('Location: logout.php');
    exit();

} else { ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="0" />

		<title>&#161;JUEGA! - Team View</title>

	</head>
	<body>

	<?php
			include 'loadCSS.php'; 
			include 'juegaNavBar.php';	  
		?>

		<div class="accordion" id="accordionView">
		
		  <div class="card z-depth-0 bordered" id="teamNameAccordian">
			<div class="card-header" id="headingOne">
			  <h2 class="mb-0">
				<button id="teamNameHeader" class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
				  aria-expanded="true" aria-controls="collapseOne">
				  #TeamName
				</button>
			  </h2>
			</div>
			<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionView">
			  <div class="card-body">
				 <!-- Team Properties available here;  allow update/delete(deactivate) teams -->
				<form class="form-playerInfo" id="teamInfoForm" name="teamInfoForm" method="post" >

					<div class="editTeamDetailsToggle">		
						<label class="editTeamDetailsLabel" for="editTeamDetails">Edit?
							<input id="editTeamDetails" type="checkbox" class="editTeamDetailsInput" value="editTD" title="Edit Team Details">
						</label><br>
					</div>
			
					<h5 id="teamInfoFormTitle">Team Details</h5>
					 
					<div id="teamInfoFormContainer" >	
						<div class="form-label-group">
							<label for="leagueID">League</label>
							<input type="name" id="leagueID" name="leagueID" class="form-control" disabled>
						</div>
						<div class="form-label-group">
							<label for="createdBy">Coach</label>
							<input type="name" id="createdBy" name="createdBy" class="form-control" disabled>
						</div>	
						<div class="form-label-group">
							<label for="teamName">Team Name</label>
							<input type="name" id="teamName" name="teamName" class="form-control" placeholder="Team Name" disabled>
						</div>
						
						<div class="form-label-group">
							<label for="colorOne">Primary Color</label>
							<input type="color" id="colorOne" name="colorOne" disabled >
						</div>	
						<div class="form-label-group">
							<label for="colorTwo">Secondary Color</label>
							<input type="color" id="colorTwo" name="colorTwo" disabled >
						</div>						
					</div>
					
					<div id="saveTeamDetailsButtonDiv" class="justify-content-center" style="display:none">
						<hr>
						<button type="button" id="saveTeamDetailsButton" class="btn btn-default">Save</button>
					</div>
				
				</form>
			  </div>
			</div>
		  </div>

		  	<div class="card z-depth-0 bordered" id="appFormAccordian">
				<div class="card-header" id="headingTwo">
					<h5 class="mb-0">
					<button class="btn btn-link collapsed" type="button" data-toggle="collapse"
						data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
						Roster
					</button>
					</h5>
				</div>

				<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionView">
					<div class="card-body">
						<!-- Include appForm here -->
						<div id="rosterButtons" class="row">
							<button id="addPlayerButton" type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#appFormModal"><b>Add Player</b></button>
							<button id="sendMessageModalButton" type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#sendMessageModal"><b>Message</b></button>
						</div>

						<div id="appFormModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">		
									<form class="form-playerInfo" id="pForm" name="pForm" method="post" data-formType data-recordID="0" >			
										<div id="appFormHeader" class="modal-header text-center">
											<button id="deleteUserFromRoster" type="button" class="btn btn-danger" style="display:none">Delete</button>
											<h4 id="playerFormTitle" class="modal-title w-100 font-weight-bold">Add New Player</h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>	
										<div id="newPlayerBox" class="newPlayerMenu modal-body mx-3"> <!--Consider renaming newPlayerBox to playerBox since update Form -->
											<span class="helper"></span>
											<div id="formContainer" class="align-content-center formStyle md-form mb-5">
												<div id="requiredFormInput" class="reqDiv tab">				
													<div class="form-label-group">
														<div style="margin: auto">
															<label for="firstName">First Name</label>
															<input type="name" id="firstName" name="firstName" class="form-control" placeholder="First Name" required autofocus>
														</div>
														<div style="margin: auto">
															<label for="lastName">Last Name</label>
															<input type="name" id="lastName" name="lastName" class="form-control" placeholder="Last Name" required >
														</div>
													</div>

													<div class="form-label-group">
														<label for="dob">DOB</label>
														<input type="date" class="form-control"  width="80%" id="dob" name="dob" required>
													</div>
													
													<div class="form-label-group">
														<label for="phoneNumber">Ph. #</label>
														<input type="tel" id="phNum" name="phoneNumber" class="form-control" placeholder="Phone Number" required >
													</div>

													<div class="form-label-group">
														<label for="playerID">Player ID</label>
														<input type="number" id="playerID" name="playerID" class="form-control" placeholder="Player ID">
													</div>

													<div class="form-label-group">
														<label for="prefComm">Preferred Communication</label>
														<select class="form-control" id="prefComm" name="prefComm" required>
															<option value=" " selected disabled>Select Preference</option>
															<option value="call">Call</option>
															<option value="text">Text</option>
															<option value="email">Email</option>
														</select>
													</div>
													
													<div class="form-label-group">
														<label for="inputEmail">Email address</label>
														<input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Email Address" required >
													</div>
														
													<div class="form-label-group">
														<label for="prefLang">Preferred Language</label>
														<select class="form-control" id="prefLang" name="prefLang">
															<option value=" " selected disabled>Preferred Language</option>
															<option value="eng">English</option>
															<option value="spa">Spanish</option>
														</select>
													</div>
												</div>
												<div id="optionalFormInput" class="my-3 tab">				
													<div class="" id="optDiv">
															<div class="form-label-group mt-4">
																<label for="nickName">Nickname</label>
																<input type="name" id="nickName" name="nickName" class="form-control" placeholder="Nickname">
															</div>

															<div class="form-label-group">
																<label for="altPhNum">Alt Ph. #</label>
																<input type="tel" id="altPhoneNum" name="altPhNum" class="form-control" placeholder="Alternate Phone Number">
															</div>	
													
															<div class="form-label-group">
																<div>
																	<label for="jerseyNumber">Jersey Number</label>
																	<input type="number" id="jerseyNumber" name="jerseyNumber" class="form-control form-sm" placeholder="##" maxlength="2">
																</div>
																<div>
																	<label for="uniformSize">Uniform Size</label>
																	<input type="text" id="uniformSize" name="uniformSize" class="form-control form-sm" placeholder="Size" maxlength="3" >
																</div>
															</div>
														
															<div class="form-group">
																<label for="position1">Primary Position</label>
																<select class="form-control" id="primaryPosition" name="position1">
																	<option value="">U - Unsure</option>
																	<option value="G">G - Goalie</option>
																	<option value="D">D - Defense(Any)</option>
																	<option value="M">M - Midfield(Any)</option>
																	<option value="F">F - Forward(Any)</option>
																	<option value="LB">LB - Left Back</option>
																	<option value="CB">CB - Center Back</option>
																	<option value="RB">RB - Right Back</option>
																	<option value="LM">LM - Left Middle</option>
																	<option value="CM">CM - Center Middle</option>
																	<option value="RM">RM - Right Middle</option>
																	<option value="LF">LF - Left Forward</option>
																	<option value="CF">CF - Center Forward</option>
																	<option value="RF">RF - Right Forward</option>
																</select>
															</div>

															<div class="form-group" >
																<label for="position2">Secondary Position</label>
																<select class="form-control" id="secondaryPosition" name="position2" disabled>
																	<option value="" selected>U - Unsure</option>
																	<option value="G">G - Goalie</option>
																	<option value="D">D - Defense(Any)</option>
																	<option value="M">M - Midfield(Any)</option>
																	<option value="F">F - Forward(Any)</option>
																	<option value="LB">LB - Left Back</option>
																	<option value="CB">CB - Center Back</option>
																	<option value="RB">RB - Right Back</option>
																	<option value="LM">LM - Left Middle</option>
																	<option value="CM">CM - Center Middle</option>
																	<option value="RM">RM - Right Middle</option>
																	<option value="LF">LF - Left Forward</option>
																	<option value="CF">CF - Center Forward</option>
																	<option value="RF">RF - Right Forward</option>
																</select>
															</div>
															
															<div class="form-group">
																<label for="commentsBox">Comments</label>
																<textarea class="form-control" id="commentsBox" name="commentsBox" rows="2"></textarea>
															</div>
														</div>
													</div>
													
													<div style="overflow:auto;">
														<div>
															<button type="button" class="btn btn-lg btn-secondary" id="prevBtn">Previous</button>
															<button type="button" class="btn btn-lg btn-primary" id="nextBtn">Next</button>
															<button type="reset" class="btn btn-lg btn-danger" id="clearBtn">Clear</button>
														
														</div>
													</div>
													<!-- Circles which indicates the steps of the form: -->
													<div class="stepContainer">
														<span class="step"></span>
														<span class="step"></span>
													</div>
												</div>
											</div>
										</div>				
									</form>
								</div>
							</div>
						</div>	

						<div id="sendMessageModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">		
									<form class="form-playerInfo" id="sendMessageForm" name="sendMessageForm" method="post" data-formType data-recordID="0" >			
										<div id="sendMessageHeader" class="modal-header text-center">
											<h4 id="messageModalTitle" class="modal-title w-100 font-weight-bold">Message Team</h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>	
										<div id="messageTeamBox" class="modal-body mx-3"> 
											<span class="helper"></span>
											<div id="messageFormContainer" class="align-content-center formStyle md-form mb-5">	
												<p>Send a message to the team based on the preferred communication and language.</p>
												<h4 class="sendToLabel">Send To:</h4>
												<div class="form-label-group messageCriteria">
													<select class="form-control" id="prefCommMessage" name="prefCommMessage">
														<option value=" " selected disabled>Select Preference</option>
														<option value="text">Text</option>
														<option value="email">Email</option>
														<option value="both">Both</option>
													</select>

													<select class="form-control" id="prefLangMessage" name="prefLangMessage">
														<option value=" " selected disabled>Preferred Language</option>
														<option value="eng">English</option>
														<option value="spa">Spanish</option>
														<option value="all">All</option>
													</select>
												</div>
												<div class="sendToLabel">
													<label for="messageToSend" ><h4>Message To Send:</h4></label>
													<textarea id="messageToSend" class="form-control" rows="3">Enter text here...</textarea>
												</div>
												<br>
												<button id="sendMessageButton" type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#sendMessage"><b>Send</b></button>
											</div>								
										</div>						
									</form>
								</div>	
							</div>
						</div>

						<div id="tableContainer" class="align-content-center" >
							<div class="loadPlayersDiv" label="Roster">	
								<table id="loadPlayers" class="display nowrap">
									<thead>
										<tr>
											<th>#</th>
											<th>First Name</th>
											<th>Last Name</th>
											<th>DOB</th>
											<th>Phone<br>Number</th>
											<th>PlayerID</th>
											<th>Preferred<br>Communication</th>
											<th>Email</th>
											<th>Preferred<br>Language</th>
											<th>Nickname</th>
											<th>Alternate<br>Phone Number</th>
											<th>Uniform<br>Size</th>
											<th>Primary<br>Position</th>
											<th>Secondary<br>Position</th>
											<th>Comments</th>
										</tr>
									</thead>
									<tfoot>

									</tfoot>
								</table>
							</div>					
						</div>
					</div>
				</div>

			</div>

		  <div class="card z-depth-0 bordered" id="appInterfaceAccordian">
			<div class="card-header" id="headingThree">
			  <h5 class="mb-0">
				<button class="btn btn-link collapsed" type="button" data-toggle="collapse" 
				  data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
				  Line-Up
				</button>
			  </h5>
			</div>
			
			<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionView">
				<div id="appInterfaceContainer" class="card-body">
					<!-- Include appInterface here -->

					<div id="numOfPlayersContainer" class="">
						<label for="numOfPlayers"><h5>Players on field?</h5></label>
						<select class="form-control form-sm" id="numOfPlayers" name="numOfPlayers" required>
							<option value=11 selected>11</option>
							<option value=10>10</option>
							<option value=9>9</option>
						</select>
					</div>
					
					<div id="gameSheetControls" class="btn-toolbar justify-content-between">
						<button id="saveGameSheetButton" type="button" class="btn btn-success" title="Save Sheet" data-toggle="modal" data-target="#saveGameSheetModal" >Save</button>
						<button id="loadGameSheetListButton" type="button" class="btn btn-info" title="Load Sheet" data-toggle="modal" data-target="#loadGameSheetModal" >Load</button>
					</div>
					
					<div id="field" class="field col-xs-12"> 		
						<div class="grid-container">
						<div id="fieldPlayer1" class="fieldPlayer fpCSS rounded-circle" data-idJersey="">#</div>
						<div id="fieldPlayer2" class="fieldPlayer fpCSS rounded-circle" data-idJersey="">#</div>  
						<div id="fieldPlayer3" class="fieldPlayer fpCSS rounded-circle" data-idJersey="">#</div>
						<div id="fieldPlayer4" class="fieldPlayer fpCSS rounded-circle" data-idJersey="">#</div>
						<div id="fieldPlayer5" class="fieldPlayer fpCSS rounded-circle" data-idJersey="">#</div>
						<div id="fieldPlayer6" class="fieldPlayer fpCSS rounded-circle" data-idJersey="">#</div>
						<div id="fieldPlayer7" class="fieldPlayer fpCSS rounded-circle" data-idJersey="">#</div>  
						<div id="fieldPlayer8" class="fieldPlayer fpCSS rounded-circle" data-idJersey="">#</div>
						<div id="fieldPlayer9" class="fieldPlayer fpCSS rounded-circle" data-idJersey="">#</div>
						<div id="fieldPlayer10" class="fieldPlayer fpCSS rounded-circle" data-idJersey="">#</div>
						<div id="goalie" class="fieldPlayer goalie rounded-circle" data-idJersey="">#</div>
						</div>
					</div>
					
					<div id="availablePlayersModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">						
							<div id="availablePlayersInnerBox" data-idposition="" class="modal-content" label="Available Players" >		
								<br><h5>Player To Replace:</h5>
								<div id="playerToReplaceInfo" class="row" >
									<div id="pReplaceElementJ" class="infoBox rounded-circle" style="width: 40px; height: 40px">X</div>
									<div id="pReplaceElementN" class="infoBox">Y</div>
								</div>
								<br>
								<div>
									<h5>Available Players</h5>
									<table id="loadOnFieldPlayers" class="selectable nowrap" >
										<thead>
											<tr>
												<th>#</th>
												<th>First Name</th>
												<th>Last Name</th>
												<th>Nickname</th>
												<th>Primary<br>Position</th>
												<th>Secondary<br>Position</th>
												<th>Comments</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
										<tfoot>
										</tfoot>
									</table>								
								</div>
							</div>
						</div>
					</div>	<!--availablePlayersModal -->	
					
				<div id="saveGameSheetModal" class="modal left fade" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div id="gameSheetInnerBox" data-idposition="" class="modal-content" label="Game Sheet" >	
							<div class="modal-header text-center">
								<h4 class="modal-title w-100 font-weight-bold">Save Line Up</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							
							<div class="modal-body mx-3">
								<form class="" id="saveNewSheetForm" name="saveNewTeamForm" method="">				
									<div class="md-form">
									<i class="fas fa-envelope prefix grey-text"></i>
									<label data-error="wrong" data-success="right" for="gameSheetName">Save New</label>
									<input type="name" id="gameSheetName" name="gameSheetName" class="form-control validate">
									</div>
								</form>
							<br><hr>
							
							<div>
								<label for="gameSheet">Update/Replace Saved Sheet:</label>
								<select id="gameSheet" size="4">
									<option value="" id="loadingGameSheetList" disabled>Loading...</option>
								</select>
							</div><hr>

							<div class="form-check">
								
									<label class="form-check-label" for="setDefaultSheet">Set As Default<br>
										<input id="setDefaultSheet" type="checkbox" class="form-check-input" value="D" title="Set Saved Sheet As Default">
									</label><br>
							</div>
							<br>
						</div>

						<div class="modal-footer d-flex justify-content-center">
							<button type="button" id="saveNewGameSheetButton" class="btn btn-default">Save</button>
						</div>
											
						</div>
					</div>
				</div>

					<div id="loadGameSheetModal" class="modal right fade" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div id="loadGameSheetInnerBox" data-idposition="" class="modal-content" label="Load Game Sheet" >	
								<div class="modal-header text-center">
									<h4 class="modal-title w-100 font-weight-bold">Load Line Up</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								
								<div class="modal-body mx-3">	
									<form class="" id="loadSheetForm" name="loadSheetForm" method="">
										<label for="loadingLoadGameSheetList">Load Sheet:</label>
										<div>
											<select id="loadingLoadGameSheetList" size="6">
												<option value="" id="gameSheetLoading" disabled>Loading...</option>
											</select>
										</div>
									</form>
								</div>

								<div class="modal-footer d-flex justify-content-center">
									<button type="button" id="loadGameSheetButton" class="btn btn-default">Load</button>
								</div>
							</div>
						</div>
					</div>
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