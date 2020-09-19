$(document).ready(function() {


//load the table as a dataTable
var playersRoster = $('#loadPlayers').DataTable( 
	{
		"searching"		: true,
        "processing"	: true,
        "ajax"			: "loadPlayerList.php",
		"scrollX"		: true,
		"scrollY"		: '50vh',
        "scrollCollapse": true,
		"pageLength"	: 30,
        "paging"		: true,
		"autoWidth"		: false,
		"language"		: {
							"lengthMenu": ""
							},
		"columnDefs"    : [ { "targets": 3, "render": function(data){
                                return moment(data).format( 'MM/DD/YYYY' );
						    }},
                            { "targets": 4, render: function ( toFormat ) {
								var tPhone;
								
								tPhone=toFormat.toString();            
								tPhone='(' + tPhone.substring(0,3) + ') ' + tPhone.substring(3,6) + '-' + tPhone.substring(6,10);   
								
								return tPhone;
							}},
                            { "targets": 10, render: function ( toFormat ) {
								var tPhone;
								
								tPhone=toFormat.toString();            
								tPhone='(' + tPhone.substring(0,3) + ') ' + tPhone.substring(3,6) + '-' + tPhone.substring(6,10);   
								
								return tPhone;
							}},
							{ "targets": 15, 
							  "visible": false,
							  "searchable": false
						    }
							],
		"fixedColumns"	:   {
							leftColumns: 2
							},
		"select"		:  'single'
    });

playersRoster.on('select', function(e, dt, type, indexes) {   //add event handler for selecting row and returning data
	
	var rawRowData = playersRoster.rows( indexes ).data(),
		rowData = rawRowData.toArray();
 
	updateMenuChanges(rowData[0][15]);
	
	console.log(rawRowData);
	console.log(rowData);
			//run function that loads update form
	populateForm(rowData);
	showTab(0);
	$("#newPlayerBox").show(0);
	playersRoster.rows('.selected').deselect()
	
});

//open up add players box and populate with returned data
//include Update button to update existing user record

$('#formCloser').click( function(){

	$("#newPlayerBox").hide(0);
	restoreFormValidation();
	
});

// process the form
function submitForm(event) {
	// get the form data
	// there are many ways to get this data using jQuery (you can use the class or id also)
	var formData = $('form').serializeArray(),
		recordID = document.getElementById("pForm").getAttribute('data-recordID');
	
	if (document.getElementById("pForm").getAttribute('data-formType')=='u'){
		var submitURL = 'addPlayerInfo.php?'+recordID;
	} else {
		var submitURL = 'addPlayerInfo.php';
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
					function(){
						alert("Record Saved");
						$("#newPlayerBox").hide(0);
						$("#pForm").trigger('reset');
						showTab(0);
						currentTab = 0;
						removeFinishFromStep();
						restoreFormValidation();
						playersRoster.ajax.reload();
						
					}
		
	});
	
			
	// stop the form from submitting the normal way and refreshing the page
	event.preventDefault();	

	};


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
	} 

//Phone Masks
$("#phNum").inputmask({"mask": "(999) 999-9999"});

$("#altPhoneNum").inputmask({"mask": "(999) 999-9999"});


//function to load new player form on button click

$("#addPlayerButton").click(function (){

	//run function that clears and restores default form
	showTab(0);
	$("#newPlayerBox").show(0);
});


$("#newPlayerBox").on('click', function(e) 			//user clicks outside of innerbox
	{
		var container = $("#formContainer");

		// if the target of the click isn't the container nor a descendant of the container
		if (!container.is(e.target) && container.has(e.target).length === 0) 
		{
			$("#newPlayerBox").hide();
			restoreFormValidation();
		};
	});


	
var currentTab = 0; // Current tab is set to be the first tab (0)
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
	
	document.getElementById("pForm").setAttribute('data-formType','');
	document.getElementById("pForm").setAttribute('data-recordID','');
	document.getElementById("formTitle").innerHTML = "New Player";
	document.getElementById("clearBtn").style.display = "inline-block";
	document.getElementById("optionalFormInput").style.display = "none";
	showTab(0);
	$("#pForm").trigger('reset');
	showTab(0);
	currentTab = 0;
	removeFinishFromStep();
	
}

/*
when selecting a row use link to
 1) update title from "New Player" to "Update Player"; 
 2) disable Clear button; 
 3) update Submit button to Update
 4) clicking Update will submitForm (or use a new php)
 */
function updateMenuChanges(recordID){
	document.getElementById("formTitle").innerHTML = "Update Player";
	document.getElementById("clearBtn").style.display = "none";
	document.getElementById("pForm").setAttribute('data-formType','u');
	document.getElementById("pForm").setAttribute('data-recordID',recordID);
};


function populateForm(formValues)
{
	document.getElementById("firstName").value = formValues[0][1];
	document.getElementById("lastName").value = formValues[0][2];
	document.getElementById("dob").value = formValues[0][3];
	document.getElementById("phNum").value = formValues[0][4];
	document.getElementById("playerID").value = formValues[0][5];
	document.getElementById("prefComm").value = formValues[0][6];
	document.getElementById("inputEmail").value = formValues[0][7];
	document.getElementById("prefLang").value = formValues[0][8];
	document.getElementById("nickName").value = formValues[0][9];
	document.getElementById("altPhoneNum").value = formValues[0][10];
	document.getElementById("jerseyNumber").value = formValues[0][0];
	document.getElementById("uniformSize").value = formValues[0][11];
	document.getElementById("primaryPosition").value = formValues[0][12];
	document.getElementById("secondaryPosition").value = formValues[0][13];
	document.getElementById("commentsBox").value = formValues[0][14];
};

});



