$(document).ready(function() {

		/* Load Position on Field
		// This code will occur with initial load.
		// 1. have this element load values from table and select the one with the highest recordID
		// 2. identify element htmlId
		// 3. look in array for same element 
		// 4. assign top and left position to element */
	$( function loadPosition(){
		
			$.ajax({
				url: "load-position.php",
				dataType: 'JSON',
				error: function(xhr, textStatus, error) {
					console.log(xhr.responseText);
					console.log(xhr.statusText);
					console.log(textStatus);
					console.log(error);
					}
				}).done( function syncFieldPlayers(data){
									
					var selectElements = document.getElementsByClassName('fieldPlayer');

					// for each array value grab the ID and position and pull values.	
					for(var i=0; i<selectElements.length; i++)
					{	var elementId = selectElements[i].id;
											
						var valueIndexInArray = data.findIndex(data => data.htmlID === elementId);
																		
						if(elementId == data[valueIndexInArray].htmlID){
							document.getElementById(elementId).style.left = data[valueIndexInArray].htmlLeft + 'px';
							document.getElementById(elementId).style.top = data[valueIndexInArray].htmlTop + 'px';
							document.getElementById(elementId).innerHTML = data[valueIndexInArray].jerseyNumber;
						} else {
							//handle empty fieldPlayer assignment
							document.getElementById(elementId).innerHTML = '#';
						};
					};
				});
	});

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
			elementID = $(elem).attr('id'),
			pos = elem.position(),
			leftPos = pos.left,
			topPos= pos.top;
		
			//Save values to data attribute
			dataAttributeParent = document.getElementById("availablePlayersInnerBox");
			dataAttributeParent.setAttribute('data-idposition','["'+elementID+'","'+leftPos+'","'+topPos+'"]'); 
			
			//Display Available Players box and associated functionality
			
			$("#availablePlayersBox").addClass('showing'); 
			availablePlayersShowing()//call function to unbind event and run show()
	};
		

		//Available Players Table
	//load the pop-up availablePlayersBox as a DataTable and declare as variablel to use again
	var availablePlayersTable = $('#loadOnFieldPlayers').DataTable( 
		{
			"dom"			: 'frtip',
			"searching"		: false,
			"processing"	: true,
			"ajax"			: "loadPlayerList.php", //display only specific columns to match table html where (new) column onField = no
			"scrollX"		: true,
			"scrollY"		: '50vh',
			"scrollCollapse": true,
			"pageLength"	: 30,
			"paging"		: true,
			"columns"		: [
								{ "data": [0] },
								{ "data": [1] },
								{ "data": [2] },
								{ "data": [9] },
								{ "data": [12] },
								{ "data": [13] },
								{ "data": [14] }
							],
			"select"		:  {
									"style"	: 'single',
									"info"	: false
								}/* ,
			"buttons"		: [
								{
									"extend": 'selected',
									"text": 'Switch Players',
									"action": function ( e, dt, button, config ) {
										
										alert( dt.rows( { selected: true } ).indexes().length +' row(s) selected' );
										
										//Save values;
										//Print to console;
										
									}
								}
							] */
		});	

		
		//Function to unbind event and show box
	function availablePlayersShowing(){
		
			playerOnField.off('doubletap'); //call newClickFunctions	(!calling off is disabling the whole call)
			$("#availablePlayersBox").show(50,newClickFunctions());//called by addClass 'showing'  	
			
			};

		
			//If clicking inside availablePlayersBox but outside availablePlayersInnerBox, then close availablePlayersBox
	function newClickFunctions() 
	{
	$("#availablePlayersBox").on('click', function(e) 			//user clicks outside of innerbox
		{
			var container = $("#availablePlayersInnerBox");

			// if the target of the click isn't the container nor a descendant of the container
			if (!container.is(e.target) && container.has(e.target).length === 0) 
			{
				$("#availablePlayersBox").removeClass('showing');
				$("#availablePlayersBox").hide();
				$("#availablePlayersBox").off('click');
			};
		});
			
		availablePlayersTable.on( 'select', function ( e, dt, type, indexes ) 
			{		
			var selectedRow = availablePlayersTable.row( {selected:true} ),
				selectedRowData = selectedRow.data(),			
					playerID = parseInt(selectedRowData[5]),
					jerseyNumber = parseInt(selectedRowData[0]),
				elementArrayInHTML = dataAttributeParent.getAttribute('data-idposition'),
				elementArrayInJSON = JSON.parse(elementArrayInHTML),
					//convert html to array 
					elementID = elementArrayInJSON[0],
					leftPos = elementArrayInJSON[1],
					topPos = elementArrayInJSON[2];		

					$.ajax({
						type: 'POST',
						url: 'save-position.php',
						data: {'recordIDfromRoster':playerID,'jerseyNumber': jerseyNumber,'htmlID':elementID, 'newleft':leftPos, 'newtop':topPos},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
								alert("Save-position.php throwing error");
							}
						}).done( function syncFieldPlayers(){									

									document.getElementById(elementID).innerHTML = jerseyNumber;
							
									selectedRow.deselect();
									dataAttributeParent.setAttribute('data-idposition','');
									$("#availablePlayersBox").removeClass('showing');
									$("#availablePlayersBox").hide();
									availablePlayersTable.off( 'select');
								
							});		
			});	
		};
});


//functions based on events and not dependant on page load.

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



 