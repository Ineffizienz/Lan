$(document).ready(function(){

	var obj = {};

	function checkInput(accountname,password,email)
	{
		var validate;
		if (accountname == "")
		{
			validate = "Du hast vergessen einen Accountnamen einzutragen";
			return validate;
		} else {
			if (accountname.length < 3)
			{
				return "Dein Accoutname ist zu kurz.";
			} else {
				if (password == "")
				{
					return "Du hast kein Passwort angegeben.";
				} else {
					if(password.length < 6)
					{
						return "Dein Passwort ist zu kurz.";
					} else {
						if (password == accountname)
						{
							return "Dein Passwort und dein Accountname sollten sich schon unterscheiden.";
						} else {
							if (email == "")
							{
								return "Du hast keine Email angegeben";
							} else {
								if (email.length < 8) // @ prüfen wäre besser
								{
									return "Das ist keine Mail!";
								} else {
									return true;
								}
							}
						}
					}
				}
			}
		}
	}


/*#############################################################################################
#################################### World of Warcraft ######################################## 
###############################################################################################*/
	
	function getWowData(event)
	{
		event.preventDefault();

		var accountname = $("#accountname").val();
		var password = $("#password").val();
		var email = $("#email").val();

		var validate = checkInput(accountname,password,email);
		if (validate == true)
		{
			obj = {accountname,password,email};
			postAjax(obj,getEndpoint("reg_wow_account"),sucRegAcc);
		} else {
			throwError(validate);
		}
	}

/*#############################################################################################
#################################### Game-Key ################################################# 
###############################################################################################*/
	
	function showGamekeyOnChange(event)
	{
		event.preventDefault();

		var game = $(this).find('option:selected').attr("value");

		obj = {game};

		getAjax(obj, getEndpoint("get_gamekey"), showKey);
	}

	function showGamekeyOnClick(event)
	{
		event.preventDefault();

		var game = $("#keygen").val();

		obj = {game};

		getAjax(obj, getEndpoint("reject_key"), displayResponse);
	}

/*#############################################################################################
#################################### Teams #################################################### 
###############################################################################################*/

	function chooseTeam(event)
	{
		event.preventDefault();

		getAjax(obj,getEndpoint("join_team"),displayJoinedTeam);
	}

	function getLeaveData(event)
	{
		event.preventDefault();
		
		var team = $("#team_id").val();

		obj = {team};
		
		getAjax(obj,getEndpoint("leave_team"),reloadTeamName);
	}

	function removeTeam(event)
	{
		event.preventDefault();

		var team = $('#name').val();

		obj = {team};

		getAjax(obj, getEndpoint("delete_team"), displayResponse);
	}

	function retrieveTeam(event) //Testcase
	{
		event.preventDefault();

		var newTeam = {};

		var team = $('#t_name').val();

		newTeam = {team};

		postAjax(newTeam, getEndpoint("create_team"), displayResponse);
	}

/*#############################################################################################
#################################### Status ################################################### 
###############################################################################################*/

	function changeStatus(event)
	{
		event.preventDefault();

		var status = $(this).find('option:selected').attr("value");

		obj = {status};

		getAjax(obj, getEndpoint("get_p_status"),displayStatus);
	}

/*#############################################################################################
#################################### User ##################################################### 
###############################################################################################*/

	function getNewUser(event)
	{
		event.preventDefault();

		var new_username = $('#newuser').val();

		obj = {new_username};

		postAjax(obj,getEndpoint("change_username"),displayChanges);
	}

	function getImage(event)
	{
		event.preventDefault();
		event.stopPropagation();
		
		var image = $(this).prop('files')[0];
		var image_data = new FormData();

		image_data.append("file",image);

		postFileAjax(image_data,getEndpoint("change_profil_image"),displayResponse);
	}

/*#############################################################################################
#################################### Preferences ############################################## 
###############################################################################################*/
	
	function getCheckedGame(event)
	{
		event.preventDefault();

		var checkedGame = $(this).attr("id");

		obj = {checkedGame};
		
		postAjax(obj,getEndpoint("add_pref"),reactToChange);
	}

/*#############################################################################################
#################################### Tournaments ############################################## 
###############################################################################################*/

	function getVotedGame(event)
	{
		event.preventDefault();

		var game_id = ("#votedGame").find("option:selected").attr("value");

		obj = {game_id};

		postAjax(obj,getEndpoint("vote_tm"),displayResponse);
	}


/*############################################################################################*/
	
	function getAjax(obj, endpoint, fn){
		return $.ajax({
			type: "get",
			url: endpoint,
			dataType: "json",
			data: obj,
			success: fn
		});
	}

	function postAjax(obj, endpoint, fn)
	{
		return $.ajax({
			type: "post",
			url: endpoint,
			dataType: 'json',
			data: obj,
			success: fn
		});
	}

	function postFileAjax(file, endpoint, fn)
	{
		return $.ajax({
			type: "post",
			url: endpoint,
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			data: file,
			success: fn
		});
	}

	//Output-Funktion
	function reactToChange(output) 	{
		
		displayMessage(output.message);

		$("#cloud_container").load(window.location.href + ' .cloud');
	}
	
	function throwError(error)
	{
		$('#error').show();
		$('#error').html(error);
		$('#error').fadeOut(7000);
	}

	function showKey(response) {
		$("#display_key").slideDown();
		$("#displayKey").html(response.key);

		$('#reject').show();
	}

	function displayMessage(message) {
		$("#result").show();
		$("#result").html(message);
		$("#result").fadeOut(7000);
	}

	function displayAchievement(achievement) {
		
		if(!achievement == "")
		{
			$("#popup_container").html(achievement).slideDown("slow", function() {
				$("#popup_container").delay(3000).slideUp("slow");
			});
		}
	}

	function displayProfilImage() {
		$(".profil_image_container").load(window.location.href + ' ' + ".profil_image");
	}

	function sucRegAcc(response)
	{
		displayMessage(response.message);
	}
	
	function displayResponse(response) {

		if(response.image)
		{
			displayProfilImage();
		}
		
		displayMessage(response.message);

		if(response.achievement)
		{
			displayAchievement(response.achievement);
		}

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
		if(response.achievement)
		{
			displayAchievement(response.achievement);
		}
		
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

	function keyEnter(e,control)
	{
		if(e.which == 13)
		{
			event.preventDefault();

			if(control == "new_user") 
			{
				getNewUser(event);
			} else {
				retrieveTeam(event);
			}
		}
	}

	//Create new team
	$("#create-team").on("click", retrieveTeam);
	$("#t_name").keypress(function(e) {
		keyEnter(e,"team_name");
	});
	//
	$(document).on("change","#keygen", showGamekeyOnChange);
	$("#reject").on("click", showGamekeyOnClick);
	$("#join").on("click", chooseTeam);
	$("#changeStatus").on("change",changeStatus);
	$("#delete").on("click",removeTeam);
	$("#edit_settings").on("click",changePopup);
	
	//Change username in settings
	$("#change_username").on("click",getNewUser);
	$("#newuser").keypress(function(e) {
		keyEnter(e,"new_user");
	});


	$("#close_popup").on("click",closePopup);
	$(document).on({mouseover: showName,mouseleave: hideName},".av_ac");
	$("#profil_image").on("change",getImage);
	$(".leave_team").on("click",getLeaveData);
	$(document).on("click",".add_pref",showPrefs);
	$(document).on("change",".checkmark_container input",getCheckedGame);
	$(".sbm").on("click",getWowData);
	$("#vote_now").on("click",getVotedGame);
});

