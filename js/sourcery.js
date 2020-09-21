$(document).ready(function() {
	
$(function loadList(){
	
	$.ajax({
            url: '../myTeams.php?getList',
			dataType: 'JSON',
			error: function(xhr, textStatus, error) {
				console.log(xhr.responseText);
				console.log(xhr.statusText);
				console.log(textStatus);
				console.log(error);
				},
            success: function(data){
 
 // if data is null, then update loading to add new team and
			if(data == null){
				$('.getTeamLoading').text('Add New Team');
				$('.getTeamLoading').value('newTeam');				
			} else {
 
                //Log the data to the console so that
                //you can get a better view of what the script is returning.
                // console.log(data);
				
				$.each(data, function(key, teamValues){
                    //Use the Option() constructor to create a new HTMLOptionElement.				
					var option = new Option(teamValues.teamName, teamValues.teamID);
                    //Convert the HTMLOptionElement into a JQuery object that can be used with the append method.
                    // $(option).html(teamValues.teamName);
                    //Append the option to our Select element.
                    $('.getTeam').append(option);
                });
 
                //Change the text of the default "loading" option.
                $('.getTeamLoading').text('Select Team');
				$('#team').append(new Option("Add New Team","newTeam"));
			};	
			}
		});
	
});

$('.getTeam').click(function navigateToTeamPage(){
 
var selectedValue = this.value;
	
	if(selectedValue == "newTeam"){
		//open New Team prompt
		console.log("In New Team selection");
		$('#modalNewTeam').modal('show');
	} else if(selectedValue == ""){
		return;
	} else {
		//navigate to Teams page and pass on selectionValue in query string i.e. myTeams.php?teamPage=selectionValue
		console.log("In Team's page selection");
		var redirectToPage = "teamView.php?teamID=" + selectedValue;
		window.location.href = redirectToPage;
	};
});


$('#saveTeamButton').click(function(){

	$('#saveNewTeamForm').submit(function(event){
	
	// event.preventDefault();
	
	var formData = $('#saveNewTeamForm').serialize()
	
	$.ajax({
            url: '../myTeams.php?saveTeam=1',
			data: formData,
			error: function(xhr, textStatus, error) {
				console.log(xhr.responseText);
				console.log(xhr.statusText);
				console.log(textStatus);
				console.log(error);
				},
            success: function(data){
				alert("Record Saved");
			}
		});
	});
});

});