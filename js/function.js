/*#############################################################################################
#################################### Popups ################################################### 
###############################################################################################*/

function displayResetPasswordPopup(event)
{
	event.preventDefault();

	$(".reset_password_popup").show();
}

function closeResultPopup(event)
{
	event.preventDefault();

	$(".tm_result_popup").hide();
	$("#result_1").val("");
	$("#result_2").val("");
}

function closePasswordPopup(event)
{
	event.preventDefault();

	$(".reset_password_popup").hide();
	$(".error_container").hide();
	$(".placeholder_container").show();
	$("#new_password").val("");
	$("#new_password_checkup").val("");
}

$(document).ready(function(){

	var obj = {};
	
	$("#result").fadeOut(7000);

	/*var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = window.location.search.substring(1),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;
	
		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');
	
			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
			}
		}
	};

	var page = getUrlParameter("page");

	if(page == "single_tm")
	{
		
	}*/

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


	function displayResultPopup(event)
	{
		event.preventDefault();

		var context = $(this);

		var func_name = "getPlayerID";
				
		obj = {func_name};
		
		getAjaxData(obj,getEndpoint("get_data"),context).then(function(data) {
			
			var player = $(this).attr("id");

			if(!(player === ""))
			{
				if(!(player === "<i>Wildcard</i>"))
				{
					if((player == data.result))
					{
						$(this).find(".score_text").toggle();
						$(this).find(".tm_result_input").toggle();
						if(!$(this).find(".tm_result_input").is(":visible"))
						{
							getMatchResults($(this));
						}
					} else {
						console.log("You shall not pass.");
					}
				}
			}
		});
	
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

	function getNewPassword(event)
	{
		event.preventDefault();
		var new_password = $("#new_password").val();
		var new_password_checkup = $("#new_password_checkup").val();

		if((new_password == new_password_checkup) && !(new_password === "") && !(new_password_checkup === ""))
		{
			var account_name = $(this).attr("data-account-name");

			obj = {account_name,new_password};

			postAjax(obj,getEndpoint("reset_password"),displayResponse);
			
			$(".reset_password_popup").hide();
			$("#new_password").val("")
			$("new_password_checkup").val("");

		} else {
			errorHandling();
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

		getAjax(obj, getEndpoint("reject_gamekey"), displayResponse);
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
		
		var items = JSON.stringify({"#player_nick":"#player_nick"});

		obj = {new_username,items};

		postAjax(obj,getEndpoint("change_username"),Output);
		closePopup(event);
	}

	function getImage(event)
	{
		event.preventDefault();
		event.stopPropagation();
		
		var image = $(this).prop('files')[0];
		var items = JSON.stringify({".profil_image_container":".profil_image"});
		var image_data = new FormData();

		if(!fileValidation(image))
		{
			console.log("File error");
		} else {
			image_data.append("file",image);
			image_data.append("items",items);

			postFileAjax(image_data,getEndpoint("change_profil_image"),Output);
		}
	}

/*#############################################################################################
#################################### Preferences ############################################## 
###############################################################################################*/
	
	function getCheckedGame(event)
	{
		event.preventDefault();

		var checkedGame = $(this).attr("id");

		if($(this).is(":checked"))
		{
			var state = "checked";
		} else {
			var state = "unchecked";
		}

		var items = JSON.stringify({"#cloud_container":".cloud"});

		obj = {state, checkedGame, items};
		
		postAjax(obj,getEndpoint("add_pref"),Output);
	}

	function getPrefData(event)
	{
		event.preventDefault();

		var game_id = $(this).attr("data-game-id");

		var items = JSON.stringify({"#cloud_container":".cloud","#selBar":"#selBar_container"});

		obj = {game_id,items};

		postAjax(obj,getEndpoint("remove_pref"),Output);
	}

/*#############################################################################################
#################################### Tournaments ############################################## 
###############################################################################################*/

	function getVotedGame(event)
	{
		event.preventDefault();

		var game_id = $("#votedGame").find("option:selected").attr("value");

		obj = {game_id};

		postAjax(obj,getEndpoint("vote_tm"),displayResponse);
	}

	function getVoteID(event)
	{
		event.preventDefault();

		var vote_id = $(this).attr("data-voted-tm");
		
		obj = {vote_id};

		postAjax(obj,getEndpoint("add_vote"),refreshVoteItem);
	}

	function getJointPlayerID(event)
	{
		event.preventDefault();

		var tm_id = $(this).attr("data-tm-id");

		obj = {tm_id};

		disableButton(this,event);

		postAjax(obj,getEndpoint("join_tm"),refreshTournamentPlayerList);
	}

	function getLeaveTournament(event)
	{
		event.preventDefault();

		var tm_id = $(this).attr("data-tm-id");

		var items = JSON.stringify({"#player_list_container":"#tm_player_list"});

		obj = {tm_id, items};

		disableButton(this,event);

		postAjax(obj,getEndpoint("leave_tm"),refreshTournamentPlayerList);
	}

	function getMatchResults(context)
	{
		
		var tm_id = $(context).parents("ul").attr("data-tm-id");
		var stage = $(context).parents("ul").attr("data-stage");
		var pair_id = $(context).parents("ul").attr("data-pair-id");
		var player_id = $(context).attr("id");
		
		var result = $(context).find(".result_input").val();

		if (result.length > 2)
		{
			console.log("Eingabe zu groß.");
		} else {
			if($.isNumeric(result))
			{
				var items = JSON.stringify({"#bracket":".container"});
				obj = {tm_id, stage, pair_id, player_id, result, items};


				postAjax(obj,getEndpoint("enter_result"),Output);
			} else {
				//displayMessage("Seid nicht albern!")
				console.log("Eingabe ist keine Zahl.");
			}

		}
	}


/*############################################################################################*/
	
	function getAjax(obj, endpoint, fn){
		return $.ajax({
			type: "get",
			url: endpoint,
			dataType: 'json',
			data: obj,
			success: fn
		});
	}

	function getAjaxData(obj, endpoint, context){
		return $.ajax({
			type: "get",
			url: endpoint,
			context: context,
			dataType: 'json',
			data: obj
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
	function Output(response)
	{
		displayMessage(response.message);
		if(response.achievement)
		{
			displayAchievement(response.achievement);
		}
		if(!$.isEmptyObject(response.items))
		{
			refreshContent(response.items);
		}
	}

	function refreshContent(items)
	{
		for (const [key, value] of Object.entries(items)) {
			
			var parent = `${key}`;
			var child = `${value}`;
			
			$(""+parent+"").load(location.href + " " + child + "", userInteract);
		}
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
		/*$("#result").css("position","sticky");
		$("#result").css("top","75%");*/
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

	function sucRegAcc(response)
	{
		displayMessage(response.message);
	}
	
	function displayResponse(response) {
		
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

	function openSettingsPopup(event)
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
		$("#content").css("opacity", "1");
		$("#newuser").val("");
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
			e.preventDefault();
			
			switch(control) {
				case "new_user":
					getNewUser(e);
					break;
				case "team_name":
					retrieveTeam(e);
					break;
			}
		}
	}
	function doNothing(event)
	{
		event.preventDefault();
		event.stopPropagation();
	}

/*#############################################################################################
#################################### Basic Animation ########################################## 
###############################################################################################*/

$(".tm_vote_container").hover(function () {
	$(this).find(".tm_vote_for").animate({width: "60px"}, 100);
	$(this).find(".tm_vote_sign").show(0);
}, function () {
	$(".tm_vote_for").animate({width: "0px"}, 300);
	$(".tm_vote_sign").hide(0);
});

function disableButton(ele, event)
{
	event.preventDefault();

	if($(ele).attr("id") === "leave_tm")
	{
		$(ele).siblings("#join_tm").prop("disabled", false);
		$(ele).siblings("#join_tm").css("cursor", "pointer");
	} else {
		$(ele).siblings("#leave_tm").prop("disabled", false);
		$(ele).siblings("#leave_tm").css("cursor", "pointer");
	}
	$(ele).prop("disabled", "disabled");
	$(ele).css("cursor", "crosshair");
}

function showPrefAction(event)
{
	event.preventDefault();
	event.stopPropagation();

	$(this).siblings(".pref_action").toggle();

}

/*#############################################################################################
#################################### Events ################################################### 
###############################################################################################*/


function userInteract()
{
	// Preferences
	$(".checkmark_container input").on("change",getCheckedGame);
	$(".pref_container").on("mouseenter mouseleave", showPrefAction);
	$(".pref_action").on("click",getPrefData);
	$("#profil_image").on("change",getImage);
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

//Change username in settings

$("#edit_settings").on("click",openSettingsPopup);
$("#change_username").on("click", getNewUser);
$("#newuser").keypress(function(e) {
	keyEnter(e,"new_user");
});


$("#close_popup").on("click",closePopup);
$(document).on({mouseover: showName,mouseleave: hideName},".av_ac");
$(".leave_team").on("click",getLeaveData);
$(document).on("click",".add_pref", showPrefs);
$(".sbm").on("click",getWowData);
$("#vote_now").on("click",getVotedGame);
$(".tm_vote_for").on("click",getVoteID);
$("#join_tm").on("click",getJointPlayerID);
$("#send_result").on("click",getMatchResults);
$("#leave_tm").on("click",getLeaveTournament);
$("#save_new_password").on("click",getNewPassword);

//Popup
$("#reset_password").on("click",displayResetPasswordPopup);
$(".opponent").on("click",displayResultPopup);
$(".result_input").focusout(function() {
	$(".tm_result_input").css("display:none;");
});
$(".result_input").on("click",doNothing);
$(".confirm_result").on("click",getMatchResults);
$("#result_close_popup").on("click",closeResultPopup);
$("#close_password_popup").on("click",closePasswordPopup);


userInteract();

	
});