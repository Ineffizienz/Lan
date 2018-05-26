$(document).ready(function(){

	var files;
	// Input-functions after the Event (change/click)
	// Creating variables and calling generateKey()
	function getFileData(input_id)
	{
		var image = $("#" + input_id + "").prop('files')[0];
		var image_data = new FormData();

		image_data.append("file",image);

		return image_data;
	}
	
	function showGamekeyOnChange(event)
	{
		event.preventDefault();

		var game = $("#keygen").val();

		generateKey(game, showKey);
	}

	function showGamekeyOnClick(event)
	{
		event.preventDefault();

		var game = $("#keygen").val();

		generateNewKey(game, displayResponse);
	}

	function chooseTeam(event)
	{
		event.preventDefault();

		joinTeam(displayJoinedTeam);
	}

	function removeTeam(event)
	{
		event.preventDefault();

		var team = $('#name').val();

		destroyTeam(team, displayResponse);
	}

	function retrieveTeam(event)
	{
		event.preventDefault();

		var team = $('#t_name').val();

		createTeam(team, displayResponse);
	}

	function changeStatus(event)
	{
		event.preventDefault();

		var status = $('#changeStatus option:selected').attr("name");

		buildStatus(status,displayStatus);
	}

	function getNewUser(event)
	{
		event.preventDefault();

		var new_username = $('#newuser').val();

		changeUsername(new_username,displayChanges);
	}
	
	function getLeaveData(event)
	{
		event.preventDefault();
		
		var team = $("#team_id").val();
		var user = $(".leave_team").attr("id");
		
		leaveTeam(team,user,reloadTeamName);
	}

	function getCheckedGame(event)
	{
		event.preventDefault();

		var checkedGame = $(this).attr("id");
		
		addPref(checkedGame,reactToChange);
	}

	function getImage(event)
	{
		event.preventDefault();
		event.stopPropagation();
		
		var image_id = "profil_image";

		uploadProfilImage(getFileData(image_id),displayResponse);
	}

	// Retrieving the data from PHP-File and calling showKey, as mentioned in the show-functions
	function generateKey(game, fn){
		return $.ajax({
			type: "get",
			url: "include/key/generate.php",
			data: {
				games:game
			},
			success: fn
		});
	}

	function generateNewKey(game, fn){
		return $.ajax({
			type: "get",
			url: "include/key/reject_key.php",
			dataType: 'json',
			data: {
				games:game
			},
			success: fn
		});
	}

	function createTeam(team, fn)
	{
		return $.ajax({
			type: "post",
			url: "include/team/create_team.php",
			dataType: 'json',
			cache: false,
			data: {
				team: team
			},
			success: fn
		});
	}

	function joinTeam(fn)
	{
		return $.ajax({
			type: "get",
			dataType: "json",
			url: "include/team/join_team.php",
			data: {},
			success: fn
		});
	}

	function leaveTeam(team,user,fn)
	{
		return $.ajax({
			type: "get",
			dataType: "json",
			url: "include/team/leave_team.php",
			data: {
				team:team,
				user:user
			},
			success: fn
		});
	}
	function addPref(game_id, fn)
	{
		return $.ajax({
			type: "post",
			dataType: "json",
			url: "include/profil/add_pref.php",
			data: {
				checkedGame:game_id
			},
			success: fn
		});
	}

	function destroyTeam(team, fn)
	{
		return $.ajax({
			type: "get",
			url: "include/team/delete_team.php",
			data: {
				name: team
			},
			success: fn
		});
	}

	function buildStatus(status, fn)
	{
		return $.ajax({
			type: "get",
			dataType: "json",
			url: "include/player/status.php",
			data: {
				status: status
			},
			success: fn
		});
	}

	function changeUsername(user, fn)
	{
		return $.ajax({
			type: "post",
			dataType: "json",
			url: "include/profil/change_user.php",
			data: {
				user:user
			},
			success: fn
		});
	}

	function uploadProfilImage(data,fn)
	{
		return $.ajax({
			type: "post",
			dataType: "json",
			url: "include/profil/profil_image.php",
			cache: false,
			contentType: false,
			processData: false,
			data: data,
			success: fn
		});
	}

	//Output-Funktion
	function reactToChange(output) 	{
		
		displayMessage(output.message);

		console.log(output.message);

		$(".cloud").load("include/ajax_function.php?function=displayPrefs");
	}
	
	function showKey(key) {
		$("#display_key").slideDown();
		document.getElementById("displayKey").innerHTML = key;

		$('#reject').show();
	}

	function displayMessage(message) {
		$("#result").show();
		$("#result").html(message);
		$("#result").fadeOut(7000);
	}

	function displayAchievement(achievement) {
		$("#achievement").html(achievement);
		$("#achievement").fadeOut(9000);
	}

	function displayResponse(response) {

		displayMessage(response.message);

		displayAchievement(response.achievement);

		$("#displayKey").html(response.key);

		$('#t_name').val("");
		$('#name').val("");

	}

	function displayStatus(stat)
	{
		$("#status_circle").css("background-color",stat.color);
	}

	function displayJoinedTeam(response) {
		displayMessage(response.message);

		$("#teams").load(location.href + " #teams");
	}

	function reloadTeamName(response) {
		displayMessage(response.message);

		$("#t_name").load(location.href + " #t_name");
		$("#t_captain").load(location.href + " #t_captain");
		$("#t_member").load(location.href + " #t_member");
	}

	function displayChanges(response)
	{
		displayMessage(response.message);
		$("#settings_change_popup").hide(0,function(){
			$("#content").css("opacity", "1");
			$("#user").load(location.href + " #user");
			$("#newuser").val("");
		});
	}

	function changePopup(event)
	{
		event.preventDefault();

		$("#settings_change_popup").show();
		$("#settings_change_popup").css("opacity","1");
		$("#content").css("opacity", "0.5");
	}

	function closePopup(event)
	{
		event.preventDefault();

		$("#settings_change_popup").hide();
		$("#popup_response").load(location.href + " #popup_response");
		$("#content").css("opacity", "1");
		$("#user").load(location.href + " #user");
	}

	function showName(event)
	{
		event.preventDefault();

		$(this).children().show();
		//$(".ac_name").show();
	}

	function hideName(event)
	{
		event.preventDefault();

		$(this).children().hide();
	}

	function showPrefs(event)
	{
		event.preventDefault();

		$("#selBar").slideToggle();
	}


	$("#create-team").on("click", retrieveTeam);
	$("#keygen").on("change", showGamekeyOnChange);
	$("#reject").on("click", showGamekeyOnClick);
	$("#join").on("click", chooseTeam);
	$("#changeStatus").on("change",changeStatus);
	$("#delete").on("click",removeTeam);
	$("#edit_settings").on("click",changePopup);
	$("#change_username").on("click",getNewUser);
	$("#close_popup").on("click",closePopup);
	$(document).on({mouseover: showName,mouseleave: hideName},".av_ac");
	$("#profil_image").on("change",getImage);
	$(".leave_team").on("click",getLeaveData);
	$(document).on("click",".add_pref",showPrefs);
	$(document).on("change",".checkmark_container input",getCheckedGame);
});

