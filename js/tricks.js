$(document).ready(function() {

var teamID='';


/*----------------- SECTION 1 - TEAM DETAILS AND LOAD PAGE -----------------*/
$(function getTeamID(){
$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null) {
       return null;
    }
    return decodeURI(results[1]) || 0;
}

teamID = $.urlParam('teamID');
pullTeamData(teamID);
});

//Use one ajax/php call to pull ALL NECESSARY DATA for teamView load
//teamInfo section;roster section; field position section

function pullTeamData (teamIdNumber){
	//teamName; teamColor1; teamColor2; leagueID
	
	var urlTeamID = '../myTeams.php?teamViewData='+teamIdNumber;
	
		$.ajax({
            url: urlTeamID,
			dataType: 'JSON',
			error: function(xhr, textStatus, error) {
				console.log(xhr.responseText);
				console.log(xhr.statusText);
				console.log(textStatus);
				console.log(error);
				},
            success: function(dataPackage){
			if(dataPackage == null){
				alert("Invalid Selection")
			} else {		
				teamInfoDetailsLoad(dataPackage['teamDetails']);
				rosterSectionLoad(dataPackage['playerRoster']);
				fieldPositionLoad(dataPackage['fieldPosition']) //include ['gameSheetSettings'] to send and run a function to load settings	
				loadGameSheetList(dataPackage['gameSheetList']);
			};
			}});
};

function teamInfoDetailsLoad(teamInfoArray){
	
	document.getElementById('teamNameHeader').innerHTML = teamInfoArray[0].teamName;
	document.getElementById("teamName").value = teamInfoArray[0].teamName;
	document.getElementById("leagueID").value = teamInfoArray[0].leagueID;
	document.getElementById("createdBy").value = teamInfoArray[0].createdBy;
	document.getElementById("colorOne").value = teamInfoArray[0].teamColor1;
	document.getElementById("colorTwo").value = teamInfoArray[0].teamColor2;

};



/*----------------- SECTION 2 - ROSTER -----------------*/

function rosterSectionLoad(playersTableData){
//load the table as a dataTable
	playersRoster.rows.add(playersTableData).draw();

};


var playersRoster = $('#loadPlayers').DataTable({
		"searching"		: false,
        "processing"	: true,
        "data"			: "",
		"dataType"		: 'JSON',
		"scrollX"		: true,
		"scrollY"		: '50vh',
        "scrollCollapse": true,
		"pageLength"	: 30,
        "paging"		: true,
		"autoWidth"		: false,
		"language"		: {
							"lengthMenu": ""
							},
		"columns" 		: [
							{ "data" : "jerseyNumber" },
							{ "data" : "firstName" },
							{ "data" : "lastName" },
							{ "data" : "dOB" },
							{ "data" : "phoneNumber" },
							{ "data" : "playerID" },
							{ "data" : "prefComm" },
							{ "data" : "emailAddress" },
							{ "data" : "prefLang" },
							{ "data" : "nickName" },
							{ "data" : "altPhone" },
							{ "data" : "uniformSize" },
							{ "data" : "primaryPosition" },
							{ "data" : "secondaryPosition" },
							{ "data" : "playerComments" }
							
							],
		"columnDefs"    : [ { "targets": "dOB", "render": function(data){
                                return moment(data).format( 'MM/DD/YYYY' );
						    }},
                            { "targets": "phoneNumber", render: function ( toFormat ) {
								var tPhone;
								
								tPhone=toFormat.toString();            
								tPhone='(' + tPhone.substring(0,3) + ') ' + tPhone.substring(3,6) + '-' + tPhone.substring(6,10);   
								
								return tPhone;
							}},
                            { "targets": "altPhone", render: function ( toFormat ) {
								var tPhone;
								
								tPhone=toFormat.toString();            
								tPhone='(' + tPhone.substring(0,3) + ') ' + tPhone.substring(3,6) + '-' + tPhone.substring(6,10);   
								
								return tPhone;
							}},
							{ "targets": 'recordId', 
							  "visible": false,
							  "searchable": false
						    },
							{ "targets": 'teamID', 
							  "visible": false,
							  "searchable": false
						    }
							],
		"fixedColumns"	:   {
							leftColumns: 2
							},
		"select"		:  'single',
		"error"			:	function (obj, textstatus) {
								alert(obj.msg);
							}
							});	


$('#collapseTwo').on('shown.bs.collapse', function () {
  playersRoster.columns.adjust().draw();
})


	/* FORM VALIDATION AND NAVIGATION */

//Phone Masks
/* $("#phNum").inputmask({"mask": "(999) 999-9999"});

$("#altPhoneNum").inputmask({"mask": "(999) 999-9999"}); */

//disable second position dropdown
document.getElementById('primaryPosition').onchange = function () {
     //alert("selected value = "+this.value);
     if(this.value == "")
     {
		 	//clear secondaryPosition entry (set value to null)
			document.getElementById('secondaryPosition').selectedIndex = 0;
		 
			//Keep secondaryPosition disabled
			document.getElementById('secondaryPosition').setAttribute('disabled', true);		
     }
     else
     {
			// document.getElementById("secondaryPosition").disabled=false;
			document.getElementById('secondaryPosition').removeAttribute('disabled');
     }
}; 

// process the form
function submitForm(event) {
	// get the form data
	// there are many ways to get this data using jQuery (you can use the class or id also)
	var formData = $('#pForm').serializeArray(),
		recordID = document.getElementById("pForm").getAttribute('data-recordID');
	
	if (document.getElementById("pForm").getAttribute('data-formType')=='u'){
		var submitURL = '../myTeams.php?teamID='+parseInt(teamID)+'&playerRecord='+parseInt(recordID);
	} else {
		var submitURL = '../myTeams.php?teamID='+parseInt(teamID)+'&playerRecord='+ 0 ;
	}
	
	// process the form
	$.ajax({
		type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
		url         : submitURL, // the url where we want to POST
		data        : formData, // our data object
		dataType    : 'json', // what type of data do we expect back from the server    
		encode		: true,
		error		:		
					function(data, textStatus, jqXHR) {
							console.log('jqXHR:');
							console.log(jqXHR);
							console.log('textStatus:');
							console.log(textStatus);
							console.log('data:');
							console.log(data);
						},
		success		:
					function(rowInfo){
						// console.log(rowInfo);
						if (document.getElementById("pForm").getAttribute('data-formType')=='u'){
							playersRoster.rows('.selected').row().data(rowInfo).draw();
						} else {
							playersRoster.row.add(rowInfo).draw();
						};
						
						$('#appFormModal').modal('hide');
						showTab(0);
						currentTab = 0;
						removeFinishFromStep();
						restoreFormValidation();
						rosterSectionLoad(rowInfo);
						alert("Record Saved");						
					}	
	});
	
			
	// stop the form from submitting the normal way and refreshing the page
	event.preventDefault();	

};

// Current tab is set to be the first tab (0)
var currentTab = 0; 
// showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form ...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  // ... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
	  if ( document.getElementById("pForm").getAttribute('data-formType') == 'u')
		{
			document.getElementById("nextBtn").innerHTML = "Update"; 
		} else {
			document.getElementById("nextBtn").innerHTML = "Submit";
		}
	} else {
		
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  // ... and run a function that displays the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n, event) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm(event)) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form... :
  if (currentTab >= x.length) {
    //...the form gets submitted:
	
	$('form').submit(submitForm(event));
	return false;

  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm(event) {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
	if (y[i].checkValidity() === false) {
			event.preventDefault();
			event.stopPropagation();
			  
			y[i].parentNode.classList.add("was-validated");
			valid = false;
		}
	}

  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
};

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class to the current step:
  x[n].className += " active";
}

$('#nextBtn').click(function (event) {
	nextPrev(1, event)
});

$('#prevBtn').click(function () {
	nextPrev(-1)
});

function removeFinishFromStep() {
	
  // This function removes the "finish" class of all steps...
  var i, x = document.getElementsByClassName("step");
  
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" finish", "");
  }
}

function restoreFormValidation(){ //consider renaming to restoreForm
	
	var y, i ;
		
	y = document.getElementsByClassName("was-validated");
	
	const z = y.length;
	
	
    // A loop that checks every input field:
	for (i = 0; i < z; i++) {
		
		y[i].className = y[i].className.replace("was-validated", "");

	}
	
	document.getElementById("playerFormTitle").innerHTML = "Add New Player";
	document.getElementById("pForm").setAttribute('data-formType','');
	document.getElementById("pForm").setAttribute('data-recordID','');
	document.getElementById("clearBtn").style.display = "inline-block";
	document.getElementById("optionalFormInput").style.display = "none";
	document.getElementById("deleteUserFromRoster").style.display = "none";
	//hide delete player button
	showTab(0);
	$("#pForm").trigger('reset');
	showTab(0);
	currentTab = 0;
	removeFinishFromStep();
	
}

$('#deleteUserFromRoster').click(function(){
	
	recordID = document.getElementById("pForm").getAttribute('data-recordID');
	firstName = document.getElementById("firstName").value;
	lastName = document.getElementById("lastName").value;
	
	var confirmDelete = confirm("Remove player's profile:  "+firstName+" "+lastName);
	
	if (confirmDelete == true) {
			
		//delete Row
		$.ajax({
			type: 'POST',
			url: '../myTeams.php?teamID='+parseInt(teamID)+'&deletePlayerRow='+parseInt(recordID),
			error: function(xhr, textStatus, error) {
					console.log(xhr.responseText);
					console.log(xhr.statusText);
					console.log(textStatus);
					console.log(error);
					}
			}).done( function (data){
				// console.log(data);
				$('#appFormModal').modal('hide');
				showTab(0);
				currentTab = 0;
				removeFinishFromStep();
				restoreFormValidation();
				//playerRoster remove row from dataTable
				playersRoster.rows('.selected').remove().draw( false );			
		});
	alert("Sheet Deleted");
	};
});

	/* END FORM VALIDATION AND NAVIGATION */

//On modal show; show tab 1
$('#appFormModal').on('show.bs.modal', function (e) {
  showTab(0);
})

//On modal close; restoreFormValidation
$('#appFormModal').on('hide.bs.modal', function (e) {
  restoreFormValidation();
})



playersRoster.on('select', function(e, dt, type, indexes) {   //add event handler for selecting row and returning data
	
	var rawRowData = playersRoster.rows( indexes ).data(),
		rowData = rawRowData.toArray();
 
	updateMenuChanges(rowData[0].recordId);
	
	//run function that loads update form
	populateForm(rowData);
	showTab(0);
	$('#appFormModal').modal('show');
	
});

//open up add players box and populate with returned data
//include Update button to update existing user record
/*
when selecting a row use link to
 1) update title from "New Player" to "Update Player"; 
 2) disable Clear button; 
 3) update Submit button to Update
 4) clicking Update will submitForm (or use a new php)
 */
function updateMenuChanges(recordID){
	document.getElementById("playerFormTitle").innerHTML = "Update Player";
	document.getElementById("clearBtn").style.display = "none";
	document.getElementById("pForm").setAttribute('data-formType','u');
	document.getElementById("pForm").setAttribute('data-recordID',recordID);
	//show delete button, use confirm prompt when clicked
	document.getElementById("deleteUserFromRoster").style.display = "inline-block";
};


function populateForm(formValues) {
	document.getElementById("firstName").value = formValues[0].firstName;
	document.getElementById("lastName").value = formValues[0].lastName;
	document.getElementById("dob").value = formValues[0].dOB;
	document.getElementById("phNum").value = formValues[0].phoneNumber;
	document.getElementById("playerID").value = formValues[0].playerID;
	document.getElementById("prefComm").value = formValues[0].prefComm;
	document.getElementById("inputEmail").value = formValues[0].emailAddress;
	document.getElementById("prefLang").value = formValues[0].prefLang;
	document.getElementById("nickName").value = formValues[0].nickName;
	document.getElementById("altPhoneNum").value = formValues[0].altPhone;
	document.getElementById("jerseyNumber").value = formValues[0].jerseyNumber;
	document.getElementById("uniformSize").value = formValues[0].uniformSize;
	document.getElementById("primaryPosition").value = formValues[0].primaryPosition;
	document.getElementById("secondaryPosition").value = formValues[0].secondaryPosition;
	document.getElementById("commentsBox").value = formValues[0].playerComments;
};


/*----------------- SECTION 3 - LINE UP  -----------------*/

//Code to display/hide users on field 
			
 document.getElementById("numOfPlayers").onchange=function() {
    var playersOnField = this.value;
	
      if ( playersOnField == '10')
      {
		document.getElementById("fieldPlayer10").style.visibility = "hidden";
		document.getElementById("fieldPlayer9").style.visibility = "visible";
      } else if ( playersOnField == '9')
      {
		document.getElementById("fieldPlayer10").style.visibility = "hidden";
		document.getElementById("fieldPlayer9").style.visibility = "hidden";
      } else  {
		document.getElementById("fieldPlayer10").style.visibility = "visible";
		document.getElementById("fieldPlayer9").style.visibility = "visible";
	  }};	


	/* Load Position on Field
	// 1. have this element load values from table and select default gamesheet
	// 2. identify element htmlId
	// 3. look in array for same element 
	// 4. assign top and left position to element */
function fieldPositionLoad(playerPositions){
	
	if(playerPositions.length==0){
		
	}else{
		var selectElements = document.getElementsByClassName('fieldPlayer');

		// for each array value grab the ID and position and pull values.	
		for(var i=0; i<selectElements.length; i++)
		{	var elementId = selectElements[i].id;
								
			var valueIndexInArray = playerPositions.findIndex(playerPositions => playerPositions.htmlID === elementId);
															
			if(elementId == playerPositions[valueIndexInArray].htmlID){
				
				elem = document.getElementById(elementId),
				elem.style.left = playerPositions[valueIndexInArray].htmlLeft+ 'px',
				elem.style.top = playerPositions[valueIndexInArray].htmlTop+ 'px',
				elem.setAttribute('data-idjersey','['+playerPositions[valueIndexInArray].recordIDfromRoster+','+playerPositions[valueIndexInArray].jerseyNumber+']');
				elem.innerHTML = playerPositions[valueIndexInArray].jerseyNumber;
			} else {
				//handle empty fieldPlayer assignment
				document.getElementById(elementId).innerHTML = '#';
				
				// for testing
/* 				updateFieldPlayers = document.getElementById(elementId);
				updateFieldPlayers.style.left = 100 + 'px';
				updateFieldPlayers.style.top = 100 + 'px';
				updateFieldPlayers.innerHTML = "31";
				updateFieldPlayers.setAttribute('data-idjersey','['+2+','+2+']');  */
				

			};
		};
	
	};	
};

	//Declares fieldPlayer class as dragabble elements

function dragMoveListener (event) {
  var target = event.target,
  // keep the dragged position in the data-x/data-y attributes
  x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx,
  y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

  // translate the element
  target.style.webkitTransform = target.style.transform
							   = 'translate(' + x + 'px, ' + y + 'px)';
  // update the posiion attributes
  target.setAttribute('data-x', x);
  target.setAttribute('data-y', y);
};

	// create a restrict modifier to prevent dragging an element out of its parent
const restrictToParent = [
							interact.modifiers.restrictRect({
								restriction: 'parent'
							})
						];

var playerOnField = interact('.fieldPlayer');

	//drag fieldPlayer class
playerOnField.draggable({
			onmove: dragMoveListener,
			modifiers: restrictToParent
	});

	/*Double click on field player
	//On double click for class = fieldPlayer display availablePlayersBox in a modal/pop up window*/
playerOnField.on('doubletap',doubleTapAction);

function doubleTapAction(event) {
	
 	var	elem = 	jQuery(event.target),
		elementID = $(elem).attr('id')
		
		loadAvailablePlayersTable();
		
		//Display Available Players modal and unbind doubletap event
		$('#availablePlayersModal').modal('show');
		
		availablePlayersTable.on( 'select', function ( e, dt, type, indexes ) 
		{		
			var selectedRow = availablePlayersTable.row( {selected:true} ),
				selectedRowData = selectedRow.data(),			
					rosterID = selectedRowData.recordId,
					jerseyNumber = selectedRowData.jerseyNumber,
					
			dataAttributeParent = document.getElementById(elementID);
			dataAttributeParent.setAttribute('data-idjersey','['+rosterID+','+jerseyNumber+']'); 

			document.getElementById(elementID).innerHTML = jerseyNumber;
					
			$('#availablePlayersModal').modal('hide');
		});
};


		
$("#availablePlayersModal").on("hide.bs.modal", function () {
	// put your default event here
	availablePlayersTable.off( 'select');
	playerOnField.off('doubletap');	
});



	//Available Players Table
//load the pop-up availablePlayersBox as a DataTable and declare as variablel to use again
var availablePlayersTable = $('#loadOnFieldPlayers').DataTable({
		"dom"			: 'frtip',
		"searching"		: false,
		"processing"	: true,
		"data"			: "", 
		"scrollX"		: true,
		"scrollY"		: '50vh',
		"scrollCollapse": true,
		"pageLength"	: 30,
		"paging"		: true,
		"columns"		: [
							{ "data" : "jerseyNumber" },
							{ "data" : "firstName" },
							{ "data" : "lastName" },
							{ "data" : "nickName" },
							{ "data" : "primaryPosition" },
							{ "data" : "secondaryPosition" },
							{ "data" : "playerComments" }
						],
		"select"		:  {
								"style"	: 'single',
								"info"	: false
							}
	});	

function loadAvailablePlayersTable(){
	//instead of calling variables, collect data here and reload table
	availablePlayersArray = playersRoster.rows().data();
	
	//get every element class fieldPlayer
	fieldPlayerCollection = document.getElementsByClassName('fieldPlayer'),
	playersOnFieldArray = Array.from(fieldPlayerCollection);
	
	//if recordIDFromRoster is in playersOnFieldArray
	//then remove element from availablePlayersArray
	//where recordID = recordIDFromRoster
	if(playersOnFieldArray.length == 0 ){
		//load table with all records
		availablePlayersTable.clear();
		availablePlayersTable.rows.add(availablePlayersArray).draw();
		} else {
			
		//if recordIDFromRoster is in fieldPositionArry
		//then remove element from availablePlayersArray
		

			
		for(var i = availablePlayersArray.length - 1; i>=0; i--){
			
			fieldRecordIDInt = parseInt(availablePlayersArray[i].recordId);
			
			for(var j=0; j < playersOnFieldArray.length; j++){

				elementArrayInHTML = playersOnFieldArray[j].getAttribute('data-idjersey');
				
				if (elementArrayInHTML == ""){
					; //skip element
				} else {
				
				elementArrayInJSON = JSON.parse(elementArrayInHTML);
				rosterIDJSON = elementArrayInJSON[0];
					
				if(availablePlayersArray[i] && fieldRecordIDInt === rosterIDJSON){
					availablePlayersArray.splice(i,1);
				};
				
				}	
			};
			//load table with spliced array
			availablePlayersTable.clear();
			availablePlayersTable.rows.add(availablePlayersArray).draw();
		};	
}};

function loadGameSheetList(gameSheetListArray){
	 // if data is null, then update loading to add new team and
	if(gameSheetListArray == null){
		$('#loadingGameSheetList').text('Save New Sheet Below');
		$('#gameSheetLoading').text('0 Sheets Found');					
	} else {
		$.each(gameSheetListArray, function(key, gameSheets){
			//Use the Option() constructor to create a new HTMLOptionElement.				
			var option = new Option(gameSheets.gameSheetName, gameSheets.gameSheetID);
			//Append the option to our Select element.
			$("#gameSheet").append(option);
			// $("#loadingGameSheetList").append(option);
		});
		
			$.each(gameSheetListArray, function(key, gameSheets){
			//Use the Option() constructor to create a new HTMLOptionElement.				
			var option = new Option(gameSheets.gameSheetName, gameSheets.gameSheetID);
			//Append the option to our Select element.
			$("#loadingLoadGameSheetList").append(option);
		});
		
		//Change the text of the default "loading" option.
		$('#loadingGameSheetList').text('Select Sheet');
		$('#gameSheetLoading').text('Select Sheet');
	};
};


var gameSheetElement = document.getElementById('gameSheet');

gameSheetElement.onchange = function updateGameSheet(){
	
	var selectedValue = gameSheetElement.value,
		numPlyrsToSave = document.getElementById('numOfPlayers').value,
		selectedText = $("#gameSheet option:selected").text();
	
	var confirmUpdate = confirm("Update "+ selectedText + "?");
	
	if (confirmUpdate == true) {
		//proceed to update record
		
		dataToSendGameSheetOnSave = {	
							'teamID': parseInt(teamID),
							'sheetID': parseInt(selectedValue),
							'numPlyrsToSave':parseInt(numPlyrsToSave)
							};
		
		loadGameSheetToSave(dataToSendGameSheetOnSave);
	} else {};
};

$('#saveNewGameSheetButton').click(function(){

//Get sheet name from input box to save new sheet 
var sheetNameToSave = document.getElementById('gameSheetName').value,
	numPlyrsToSave = document.getElementById('numOfPlayers').value;


//create gameSheetID and then use new ID to savePositions
dataToSendGameSheetOnSave = {	
							'teamID': parseInt(teamID),
							'sheetID': 0,
							'sheetName': sheetNameToSave,
							'numPlyrsToSave':parseInt(numPlyrsToSave)
							};

loadGameSheetToSave(dataToSendGameSheetOnSave);
							
});




$('#loadGameSheetButton').click(function(){
	
	var sheetIDToLoad = document.getElementById('loadingLoadGameSheetList').value
		
		//Load # of players on field
		//numPlyrsToLoad = document.getElementById('numOfPlayers').value;
	
	//ajax call to send gameSheetID and pull playerPosition data
	//load array into ajax call to send to database
	$.ajax({
		type: 'POST',
		url: '../myTeams.php?loadGameSheet=' + parseInt(sheetIDToLoad),
		dataType: 'JSON',
		error: function(xhr, textStatus, error) {
				console.log(xhr.responseText);
				console.log(xhr.statusText);
				console.log(textStatus);
				console.log(error);
				}
		}).done( function (fieldPositions){
			
			fieldPositionsArray = fieldPositions['positions'];
			
			// console.log(fieldPositions);
			fieldPositionLoad(fieldPositionsArray);
			$("#numOfPlayers").val(fieldPositions['playersOnField']);
			$('#loadGameSheetModal').modal('hide');
			alert("Sheet Loaded");
		})
});



function loadGameSheetToSave(dataToSendGameSheetOnSave){

//get every element class fieldPlayer
	fieldPlayerCollection = document.getElementsByClassName('fieldPlayer'),
	fieldPlayerArray = Array.from(fieldPlayerCollection),
	arrayToSendPositionInfo = new Array(),
	arrayToSendGameSheet = new Array();
	
	
	for(x=0; x<fieldPlayerArray.length;x++){
		
		htmlID = fieldPlayerCollection[x].id,
			posJQuery = '#'+htmlID,
			pos = $(posJQuery).position(),
			leftPos = pos.left,
			topPos= pos.top;
		elementArrayInHTML = fieldPlayerArray[x].getAttribute('data-idjersey'),
		elementArrayInJSON = JSON.parse(elementArrayInHTML);

		//convert html to array 
		rosterIDJSON = elementArrayInJSON[0],
		jerseyNumberJSON = elementArrayInJSON[1],
				
		dataToSendPositionInfoOnSave = {
							'rosterID': rosterIDJSON,
							'htmlID': htmlID,
							'leftPos': leftPos,
							'topPos': topPos	
							};
		
		arrayToSendPositionInfo.push(dataToSendPositionInfoOnSave);
			
	};
	
	//if setAsDefault is checked then send 3rd array with setAsDefault
	//else send with only gameSheet and positionInfo
	if (document.getElementById("setDefaultSheet").checked){
		sendToGameSheet = {'gameSheet':dataToSendGameSheetOnSave, 'positionInfo':arrayToSendPositionInfo, 'setAsDefault':1};
	} else {
		sendToGameSheet = {'gameSheet':dataToSendGameSheetOnSave, 'positionInfo':arrayToSendPositionInfo};
	};
		
	
	jsonToSendGameSheet = JSON.stringify(sendToGameSheet);
	
	ajaxURL = 'myTeams.php?saveGameSheet';

	//load array into ajax call to send to database
	$.ajax({
		type: 'POST',
		url: ajaxURL,
		data: {'rowsToAdd': jsonToSendGameSheet},
		dataType: 'JSON',
		error: function(xhr, textStatus, error) {
				console.log(xhr.responseText);
				console.log(xhr.statusText);
				console.log(textStatus);
				console.log(error);
				}
		}).done( function (gameSheetRow){
			// console.log(gameSheetRow);
			alert("Sheet Saved");
			
			//GET ROW AND ADD AS SELECT OPTION
			var optionSGS = new Option(gameSheetRow[0].gameSheetName, gameSheetRow[0].gameSheetID),
				optionLGS = new Option(gameSheetRow[0].gameSheetName, gameSheetRow[0].gameSheetID);
			
			$("#gameSheet").append(optionSGS);
			$("#loadingLoadGameSheetList").append(optionLGS);
			
			$("#saveNewSheetForm").trigger("reset");
			
			$('#saveGameSheetModal').modal('hide');
			
		});
};


$('#editTeamDetails').click(function(){
     
    if($(this).prop('checked') == false){
		document.getElementById("teamName").disabled = true;
		document.getElementById("colorOne").disabled = true;
		document.getElementById("colorTwo").disabled = true;
		$("#saveTeamDetailsButtonDiv").hide();
		document.getElementById("saveTeamDetailsButton").disabled = true;
		
    }
    else {
		document.getElementById("teamName").disabled = false;
		document.getElementById("colorOne").disabled = false;
		document.getElementById("colorTwo").disabled = false;		
		$("#saveTeamDetailsButtonDiv").show();
		document.getElementById("saveTeamDetailsButton").disabled = false; }
});


$('#saveTeamDetailsButton').click(function(){

//Get sheet name from input box to save new sheet 
var teamNameChange = document.getElementById('teamName').value,
	colorOneChange = document.getElementById('colorOne').value,
	colorTwoChange = document.getElementById('colorTwo').value;


//include alert to send to confirm update
	var confirmTeamDetailsUpdate = confirm("Update "+ teamNameChange + " details?");

	if (confirmTeamDetailsUpdate == true) {

		//proceed to update record
		
		dataToSendTeamDetailsOnSave = {	
							'teamID': parseInt(teamID),
							'teamName': teamNameChange,
							'colorOne': colorOneChange,
							'colorTwo': colorTwoChange
							};
							
		JSONToSendTeamDetailsOnSave	= JSON.stringify(dataToSendTeamDetailsOnSave);				
		
		//load array into ajax call to send to database
	$.ajax({
		type: 'POST',
		url: '../myTeams.php?saveTeamDetails',
		data: {'updatedTeamDetails': JSONToSendTeamDetailsOnSave},
		error: function(xhr, textStatus, error) {
				console.log(xhr.responseText);
				console.log(xhr.statusText);
				console.log(textStatus);
				console.log(error);
				}
		}).done( function (data){
			// console.log(data);
			$('#editTeamDetails').prop('checked',false);
			document.getElementById("teamName").disabled = true;
			document.getElementById("colorOne").disabled = true;
			document.getElementById("colorTwo").disabled = true;
			$("#saveTeamDetailsButtonDiv").hide();
			document.getElementById("saveTeamDetailsButton").disabled = true;
			alert("Team Details Saved");
			
		});
		
	} else {;};
							
});



});