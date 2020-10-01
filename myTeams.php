<?php
require 'components/authenticate.php';

use Arrilot\DotEnv\DotEnv;

DotEnv::load('components/.env.php');

if (!$userInfo) {
	$invalidLoginAlert = 'Invalid login';
	echo "<script type='text/javascript'>alert('$invalidLoginAlert');</script>";
	header('Location: logout.php');
    exit();
} else {

//db credentials
$user = DotEnv::get('DB_USER'); //Enter the user name
$password = DotEnv::get('DB_PASSWORD'); //Enter the password
$host = DotEnv::get('DB_HOST'); //Enter the host
$dbase = DotEnv::get('DB_NAME'); //Enter the database

//Check connection for errors
$connection= mysqli_connect ($host, $user, $password, $dbase);
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  };

//pull query string parameters
$queryString = $_SERVER["QUERY_STRING"];

parse_str($queryString, $output);

foreach($output as $key => $value){
	switch ($key) {
		case "getList":
			//Define values to pass from HTML on to SQL
			//Pass username to identify associated Team
			$usernameView= preg_replace('/auth0\|/', '', $userInfo['sub']);
			
			//Run query and return errors
			$sqlLoad = "SELECT t.teamName,t.teamID
					FROM teamInfo as t
					INNER JOIN accessmap as am
					WHERE t.teamID = am.teamID
					AND am.userID	=  '$usernameView'
					ORDER BY t.teamID ";

			$result = mysqli_query($connection, $sqlLoad);

			if (!mysqli_query($connection,$sqlLoad))
			{
			echo("Error description: " . mysqli_error($connection));
			} else {
				for ($array=array(); $row = mysqli_fetch_assoc($result); $array[] = $row);
					echo json_encode($array);
			};
		break;
		case "saveTeam":
			//Define values to pass from HTML on to SQL
			$newTeamName= $output['teamName'];
			$createdBy= $userInfo['nickname'];
			$teamSQL = "INSERT INTO teamInfo (`teamName`, `createdBy`) VALUES ('$newTeamName','$createdBy')";
			
			if (!mysqli_query($connection,$teamSQL))
			{
				echo ("Error description: " . mysqli_error($connection));
			} else {
				//add record to accessMap table
				$teamIdNew = mysqli_insert_id($connection);
				$usernameView= preg_replace('/auth0\|/', '', $userInfo['sub']);

				$accessSQL = "INSERT INTO accessmap (`teamID`, `userID`)
				VALUES ('$teamIdNew','$usernameView')";

				if (!mysqli_query($connection,$accessSQL))
				{
					echo ("Error description: " . mysqli_error($connection));
				} else {
					//add record to accessMap table
					echo ('Team Added');
					return true;
				};
			};
		break;
		case "deleteTeam":
			$teamIDToDelete =$output['deleteTeam'];

			$deleteTeamSQL = "DELETE FROM `teaminfo` WHERE teamID='$teamIDToDelete';DELETE FROM `accessmap` WHERE teamID='$teamIDToDelete'";

			$resultDeleteTeam = mysqli_multi_query($connection, $deleteTeamSQL);
			
			if (!$resultDeleteTeam){
				echo("Error description: " . mysqli_error($connection));
			} else {
				return true;
			};

		break;
		case "teamViewData":
			//team ID details
			$teamID = $value;
			
			$teamInfoSQL = "SELECT `teamID`,`teamName`,`teamColor1`,`teamColor2`,`leagueID`,`createdBy`
							FROM teamInfo
							WHERE `teamID` =+ '$teamID'";
			
			$teamInfoSQLResult = mysqli_query($connection, $teamInfoSQL);
			
			//player roster
			$playerRosterSQL = "SELECT `jerseyNumber`,`firstName`,`lastName`,`dOB`,`phoneNumber`,`playerID`,
									`prefComm`,`emailAddress`,`prefLang`,`nickName`,`altPhone`,`uniformSize`,
									`primaryPosition`,`secondaryPosition`,`playerComments`,`recordId`,`teamID`
								FROM playerInfo
								WHERE `teamID` =+ '$teamID'";
			
			$playerRosterSQLResult = mysqli_query($connection, $playerRosterSQL);
			
			//player positions/gamesheet
			$gameSheetPositionsSQL = 	"SELECT p.recordIDfromRoster, p.htmlID, p.htmlLeft, p.htmlTop, pi.jerseyNumber 
										FROM positionInfo AS p 
										INNER JOIN playerInfo AS pi 
										ON p.recordIDfromRoster = pi.recordID 
										WHERE p.sheetID IN (
											SELECT g.gameSheetID 
											FROM gameSheets AS g 
											WHERE g.teamID = '$teamID' 
											AND g.isDefault = 1) 
										ORDER BY htmlID ASC";
									
						
			$gameSheetPositionsSQLResult = mysqli_query($connection, $gameSheetPositionsSQL);
			
			//gamesheetSettings
			$gamesheetSettingsSQL = 	"SELECT g.gameSheetID,g.isDefault, g.numOfPlyrsOnFld
											FROM gameSheets AS g 
											WHERE g.teamID = '$teamID' 
											AND g.isDefault = 1";
									
						
			$gamesheetSettingsSQLResult = mysqli_query($connection, $gamesheetSettingsSQL);
			
			//gamesheetList
			$gameSheetListSQL = 	"SELECT g.gameSheetID, g.gameSheetName
										FROM gameSheets AS g
										WHERE g.teamID = '$teamID'";
									
						
			$gameSheetListSQLResult = mysqli_query($connection, $gameSheetListSQL);
			
			//Create array for each SQL statement; join the arrays into 1 array and then return to tricks.js
			
			//Check errors of all SQL statement with multi_query
			$concatOfSQL = $teamInfoSQL . ";\n" . $playerRosterSQL . ";\n" . $gameSheetPositionsSQL . ";\n" . $gamesheetSettingsSQL . ";\n" . $gameSheetListSQL . ";";

			if (!mysqli_multi_query($connection,$concatOfSQL))
			{
				echo("Error description: " . mysqli_error($connection));
			} else {
					$dataPackage = array();
				
					for ($dataPackage['teamDetails']=array(); $rowtD = mysqli_fetch_assoc($teamInfoSQLResult); $dataPackage['teamDetails'][] = $rowtD);
					
					for ($dataPackage['playerRoster']=array(); $rowpR = mysqli_fetch_assoc($playerRosterSQLResult); $dataPackage['playerRoster'][] = $rowpR);
					
					for ($dataPackage['fieldPosition']=array(); $rowfP = mysqli_fetch_assoc($gameSheetPositionsSQLResult); $dataPackage['fieldPosition'][] = $rowfP);
					
					for ($dataPackage['gameSheetSettings']=array(); $rowgSS = mysqli_fetch_assoc($gamesheetSettingsSQLResult); $dataPackage['gameSheetSettings'][] = $rowgSS);
					
					for ($dataPackage['gameSheetList']=array(); $rowgSL = mysqli_fetch_assoc($gameSheetListSQLResult); $dataPackage['gameSheetList'][] = $rowgSL);
										
					echo json_encode($dataPackage);	
			};
		break;
		case "saveTeamDetails":
		
			$arrayForTeamDetails = $_POST['updatedTeamDetails'];
			$dataFromSaveTeamDetailsCall = json_decode($arrayForTeamDetails,true);
			
			$teamID=$dataFromSaveTeamDetailsCall['teamID'];
			$teamName=$dataFromSaveTeamDetailsCall['teamName'];
			$colorOne=$dataFromSaveTeamDetailsCall['colorOne'];
			$colorTwo=$dataFromSaveTeamDetailsCall['colorTwo'];
			
			$updateTeamDetailsSQL = "UPDATE teamInfo SET `teamName`='$teamName',`teamColor1`='$colorOne',`teamColor2`='$colorTwo' WHERE teamID='$teamID'";
			
			$resultUpdateTeamDetails = mysqli_query($connection, $updateTeamDetailsSQL);
			
			if (!$resultUpdateTeamDetails){
				echo("Error description: " . mysqli_error($connection));
			} else {
				return true;
			};
		break;
		case "playerRecord":
			
			//Define values to pass from HTML on to SQL
			$teamID = $output['teamID'];
			$playerRecordID = $output['playerRecord'];
			$firstName= $_POST['firstName'];
			$lastName= $_POST['lastName'];
			$dob= $_POST['dob'];
			$phoneNumber= $_POST['phoneNumber'];
			$playerID= $_POST['playerID'];
			$prefComm = $_POST['prefComm']?? '';
			$email= $_POST['inputEmail'];
			$prefLang = $_POST['prefLang'] ?? '';
			$nickName= $_POST['nickName'];
			$altPhNum= $_POST['altPhNum'];
			$jerseyNum= $_POST['jerseyNumber'];
			$uniformSize= $_POST['uniformSize'];
			$position1= $_POST['position1'];
			$position2= $_POST['position2'] ?? '';
			$commentsBox= $_POST['commentsBox'];
			
			//Reformat for SQL entry
			$phoneNumber = (int)preg_replace('/\D+/','',$phoneNumber);
			$altPhNum = (int)preg_replace('/\D+/','',$altPhNum);

			$date = str_replace('/', '-', $dob );
			$dobSQL = date("Y-m-d", strtotime($date));
			
			if($playerRecordID == 0){
				//new record
				$playerRecordRowSQL = "INSERT INTO playerInfo (teamID, firstName, lastName, dOB, phoneNumber, playerID, prefComm, emailAddress, prefLang, nickName, altPhone, jerseyNumber, uniformSize, primaryPosition, secondaryPosition, playerComments)
						VALUES ('$teamID','$firstName','$lastName','$dobSQL','$phoneNumber','$playerID','$prefComm','$email','$prefLang','$nickName','$altPhNum','$jerseyNum','$uniformSize','$position1','$position2','$commentsBox')";
			} else {
				//update record
				$playerRecordRowSQL = 	"UPDATE playerInfo 
						SET firstName='$firstName',lastName='$lastName',dOB='$dobSQL',phoneNumber='$phoneNumber', 
								playerID='$playerID',prefComm='$prefComm',emailAddress='$email', 
								prefLang='$prefLang',nickName='$nickName',altPhone='$altPhNum', 
								jerseyNumber='$jerseyNum',uniformSize='$uniformSize',primaryPosition='$position1', 
								secondaryPosition='$position2',playerComments='$commentsBox'
						WHERE recordId='$playerRecordID'";
			};

			$resultPlayerRecord = mysqli_query($connection, $playerRecordRowSQL);
			
			//return added/updated row to load in dataTable
			if (!$resultPlayerRecord) {
				echo("Error description: " . mysqli_error($connection));
			} else {
				if($playerRecordID == 0){
					$playerRecordAffected = mysqli_insert_id($connection);
				} else {
					$playerRecordAffected = $playerRecordID;
				};
				
				$playerRosterSQL = "SELECT `jerseyNumber`,`firstName`,`lastName`,`dOB`,`phoneNumber`,`playerID`,
								`prefComm`,`emailAddress`,`prefLang`,`nickName`,`altPhone`,`uniformSize`,
								`primaryPosition`,`secondaryPosition`,`playerComments`,`recordId`,`teamID`
							FROM playerInfo
							WHERE recordId='$playerRecordAffected'";
				
				$playerRosterSQLResult = mysqli_query($connection, $playerRosterSQL);
				
				if (!$playerRosterSQLResult) {
					echo("Error description: " . mysqli_error($connection));
				} else {					
					$rowpRR = mysqli_fetch_assoc($playerRosterSQLResult); 
					echo json_encode($rowpRR);
				};
			};
		break;
		case "deletePlayerRow":
			
			$deletePlayerRecord = $output['deletePlayerRow'];
			$teamID = $output['teamID'];
			
			$playerRecordToDeleteSQL = "DELETE FROM `playerInfo` WHERE `recordID`= '$deletePlayerRecord'";

			//player roster
			$playerRosterSQL = "SELECT `jerseyNumber`,`firstName`,`lastName`,`dOB`,`phoneNumber`,`playerID`,
									`prefComm`,`emailAddress`,`prefLang`,`nickName`,`altPhone`,`uniformSize`,
									`primaryPosition`,`secondaryPosition`,`playerComments`,`recordId`,`teamID`
								FROM
									playerInfo
								WHERE
									`teamID` =+ '$teamID'";
			
			$playerRosterSQLResult = mysqli_query($connection, $playerRosterSQL);
			
			//Check errors of all SQL statement with multi_query
			$concatDelAndLoadOfSQL = $playerRecordToDeleteSQL . ";\n" . $playerRosterSQL . ";";
			
			if (!mysqli_multi_query($connection,$concatDelAndLoadOfSQL)){
				echo("Error description: " . mysqli_error($connection));
			} else {
				for ($loadPlayerRoster=array(); $rowpR = mysqli_fetch_assoc($playerRosterSQLResult); $loadPlayerRoster[] = $rowpR);
					echo json_encode($loadPlayerRoster);
			};
		break;
		case "loadGameSheet":
			$gameSheetToLoad =$output['loadGameSheet'];

			//player positions/gamesheet
			$gameSheetPositionsSQL = 	"SELECT p.recordIDfromRoster, p.htmlID, p.htmlLeft, p.htmlTop, pi.jerseyNumber 
										FROM positionInfo AS p 
										INNER JOIN playerInfo AS pi 
										ON p.recordIDfromRoster = pi.recordID 
										WHERE p.sheetID = '$gameSheetToLoad'
										ORDER BY htmlID ASC";
										
			$gameSheetPositionsSQLResult = mysqli_query($connection, $gameSheetPositionsSQL);
			
			$gameSheetNumOfPlyrsOnFieldSQL = "SELECT `numOfPlyrsOnFld` FROM `gameSheets` WHERE `gameSheetID`= '$gameSheetToLoad'";
			
			$gameSheetNumOfPlyrsOnFieldSQLResult = mysqli_query($connection, $gameSheetNumOfPlyrsOnFieldSQL);
			
			if (!$gameSheetPositionsSQLResult)
			{
				echo ("Error description: " . mysqli_error($connection));
			} else {
				
				$gameSheetPackage = array();
				
				for ($gameSheetPackage['positions']=array(); $rowfP = mysqli_fetch_assoc($gameSheetPositionsSQLResult); $gameSheetPackage['positions'][] = $rowfP);
				
				$playersOnField = mysqli_fetch_assoc($gameSheetNumOfPlyrsOnFieldSQLResult);
						
				$gameSheetPackage['playersOnField'] = intval($playersOnField["numOfPlyrsOnFld"]);
				
				echo json_encode($gameSheetPackage);	
			};	
		break;
		case "saveGameSheet":

			$dataFromSaveCall = json_decode($_POST['rowsToAdd'],true);
			$rowsToAddToTable = $dataFromSaveCall['positionInfo'];
			$sheetID = $dataFromSaveCall["gameSheet"]["sheetID"];
			$teamID = $dataFromSaveCall['gameSheet']['teamID'];
			$setAsDefault = 0;
			$numPlyrsToSave= $dataFromSaveCall['gameSheet']['numPlyrsToSave'];
		
			if(isset($dataFromSaveCall['setAsDefault'])){		
				//remove current default 
				$removeAnyDefaultsSetForTeam = "UPDATE gameSheets SET `isDefault` = 0 WHERE `teamID` = '$teamID';";
		
				$resetDefaults = mysqli_query($connection, $removeAnyDefaultsSetForTeam);
			
				if (!$resetDefaults) {
					echo("Error description: " . mysqli_error($connection));
				} else {
					//update new default
					$setAsDefault=1;
				}
			};

			//New Game Sheet
			if($sheetID == 0){
				$sheetName = $dataFromSaveCall['gameSheet']['sheetName'];
				$sheetIDGameSheet = "";					
				$addRowsToGameSheetSQL = "INSERT INTO gameSheets (`gameSheetName`,`teamID`,`isDefault`,`numOfPlyrsOnFld`) VALUES ('$sheetName', '$teamID', '$setAsDefault', '$numPlyrsToSave');";
				
				$resultGameSheet = mysqli_query($connection, $addRowsToGameSheetSQL);
					if (!$resultGameSheet){
						echo("Error description: " . mysqli_error($connection));
					} else {
						$sheetIDGameSheet = mysqli_insert_id($connection);	
					};
		
				$addRowsToPositionInfoSQL = "INSERT INTO positionInfo (`sheetID`, `recordIDfromRoster`, `htmlID`, `htmlLeft`, `htmlTop` ) VALUES";
				$valuesInsert = [];
		
				foreach($rowsToAddToTable as $rowToAdd){
					
					$rosterIDGameSheet = $rowToAdd['rosterID'];
					$htmlIDGameSheet = $rowToAdd['htmlID'];
					$leftPosGameSheet = $rowToAdd['leftPos'];
					$topPosGameSheet = $rowToAdd['topPos'];
					
					$valuesInsert[] = "('$sheetIDGameSheet','$rosterIDGameSheet','$htmlIDGameSheet','$leftPosGameSheet','$topPosGameSheet')";
				};
		
				$addRowsToPositionInfoSQL .= join(',', $valuesInsert) . ';';
		
				$resultNewSheet = mysqli_query($connection, $addRowsToPositionInfoSQL);
		
				if (!$resultNewSheet)
				{
					echo("Error description: " . mysqli_error($connection));
				} else {
					while (mysqli_next_result($connection)) {;} // flush multi_queries
				}
				
			} else { //update existing gamesheet
					
				$updateRowsToPositionInfoSQL = "UPDATE gameSheets SET `numOfPlyrsOnFld`='$numPlyrsToSave', `isDefault`='$setAsDefault' WHERE `gameSheetID`='$sheetID';";
				$valuesInsert = [];
		
				foreach($rowsToAddToTable as $rowToAdd){
					
					$rosterIDGameSheet = $rowToAdd['rosterID'];
					$htmlIDGameSheet = $rowToAdd['htmlID'];
					$leftPosGameSheet = $rowToAdd['leftPos'];
					$topPosGameSheet = $rowToAdd['topPos'];
					
					$valuesInsert[] = "UPDATE positionInfo SET `recordIDfromRoster`='$rosterIDGameSheet', `htmlLeft`='$leftPosGameSheet', `htmlTop`='$topPosGameSheet' WHERE `htmlID`= '$htmlIDGameSheet' AND `sheetID`='$sheetID'";
				};
		
				$updateRowsToPositionInfoSQL .= join(';', $valuesInsert) . ';';
		
				$resultUpdateSheet = mysqli_multi_query($connection, $updateRowsToPositionInfoSQL);
		
				if (!$resultUpdateSheet) {
					echo("Error description: " . mysqli_error($connection));
				} else {
					while (mysqli_next_result($connection)) {;} // flush multi_queries
				}
			};
				
			//return save/load gamesheetlist
			$gameSheetRowSQL = "SELECT g.gameSheetID, g.gameSheetName FROM gameSheets AS g WHERE `teamID`='$teamID' ORDER BY `gameSheetID` DESC LIMIT 1;";
								
			$gameSheetRowSQLResult = mysqli_query($connection, $gameSheetRowSQL);
			
			if (!$gameSheetRowSQLResult)
			{
				echo ("Error description: " . mysqli_error($connection));
			} else {
			
			for ($gameSheetList=array(); 
				$rowgSL = mysqli_fetch_assoc($gameSheetRowSQLResult); 
				$gameSheetList[] = $rowgSL);
			
			echo json_encode($gameSheetList);	
			};

		break;
			
	}
};


mysqli_close($connection);
};
?>