$(document).ready(function(){

	var files;
	var obj = {};

	function getFileData(input_id)
	{
		var image = $(input_id).prop('files')[0];
		var image_data = new FormData();

		if($(input_id).get(0).files.length == 0)
		{
			image_data.append("file","0");
		} else {
			image_data.append("file",image);
		}

		//console.log(image_data);

		return image_data;
	}

	function retrieveGameID(start)
	{
		var game_id = $(start).parents(".admin_game_data").find(".game_id").html();

		return game_id;
	}

	function getParentElement(button)
	{
		var substr = $(button).attr("data-reload-parent").substr(0,3);

		if(substr == "id_")
		{	
			return "#" + $(button).attr("data-reload-parent").substr(3);
		} else {
			return "." + $(button).attr("data-reload-parent").substr(3);
		}
	}

	function getChildElement(button)
	{
		var substr = $(button).attr("data-reload-child").substr(0,3);
		if(substr == "id_")
		{
			return "#" + $(button).attr("data-reload-child").substr(3);
		} else {
			return "." + $(button).attr("data-reload-child").substr(3);
		}
		
	}

/*#############################################################################################
#################################### User ##################################################### 
###############################################################################################*/
	
	function getNumber(event)
	{
		event.preventDefault();

		var c_name = $("#cover_name").val();

		var p_element = getParentElement(this);
		var c_element = getChildElement(this);

		obj = {c_name,p_element,c_element};

		postAjax(obj,getEndpoint("create_new_account"),OutputData);
	}

	function getId(event)
	{
		event.preventDefault();

		var player = $(this).closest("tr").attr("id");

		var p_element = getParentElement(this);
		var c_element = getChildElement(this);

		obj = {player,p_element,c_element};

		postAjax(obj,getEndpoint("delete_player"),OutputData);

	}

	function getTicketData(event)
	{
		event.preventDefault();

		var player_id = $(this).closest("tr").attr("id");

		obj = {player_id};

		getAjax(obj,getEndpoint("create_ticket"),displayTicketID);
	}

/*#############################################################################################
#################################### Teams #################################################### 
###############################################################################################*/

	function getTeamId(event)
	{
		event.preventDefault();

		var teamId = $("#del_team").find('option:selected').attr("value");

		obj = {teamId};

		postAjax(obj,getEndpoint("delete_team"),OutputData);
	}
	
/*#############################################################################################
#################################### Games #################################################### 
###############################################################################################*/
	
	function getNewGame(event)
	{
		event.stopPropagation();
		event.preventDefault();
		
		var game = $("#new_game").val();
		var raw_name = $("input[name='new_raw_table']:checked").serialize();
		var tm_game = $("input[name='new_tm_game']:checked").serialize();
		
		var p_element = getParentElement(this);
		var c_element = getChildElement(this);

		var form_data = new FormData();

		form_data.append("game",game);
		form_data.append("raw_name",raw_name);
		form_data.append("tm_game",tm_game);
		form_data.append("p_element",p_element);
		form_data.append("c_element",c_element);

		if(!$("#new_game_icon").val())
		{
			form_data.append("game_icon","0");
		} else {
			var image_icon = $("#new_game_icon").prop('files')[0];
			if(!fileValidation(image_icon))
			{
				form_data.append("game_icon","0");
				console.log("Dateifehler");
			} else {
				form_data.append("game_icon",image_icon);
			}
		}

		if(!$("#new_game_banner").val())
		{
			form_data.append("game_banner","0");
		} else {
			var image_banner = $("#new_game_banner").prop('files')[0];
			if(!fileValidation(image_banner))
			{
				form_data.append("game_banner","0");
				console.log("Dateifehler");
			} else {
				form_data.append("game_banner",image_banner);
			}
		}
			
		postFileAjax(form_data,getEndpoint("create_new_game"),OutputData);
	}

	function getNewGameName(event)
	{
		event.preventDefault();

		var p_element = getParentElement(this);
		var c_element = getChildElement(this);
		
		var game_name = $(this).siblings(".game_name").val();
		var game_id = retrieveGameID(this);

		obj = {game_id,game_name,p_element,c_element};

		postAjax(obj,getEndpoint("update_gamename"),OutputData);
	}

	function getNewShortTitle(event)
	{
		event.preventDefault();

		var p_element = getParentElement(this);
		var c_element = getChildElement(this);

		var game_short_title = $(this).siblings(".game_short_title").val();
		var game_id = retrieveGameID(this);

		obj = {game_id,game_short_title,p_element,c_element};

		postAjax(obj,getEndpoint("update_shorttitle"),OutputData);
	}

	function getHasTable(event)
	{
		event.preventDefault();

		var has_table = $(this).find('option:selected').attr("value");
		var game_id = retrieveGameID(this);

		obj = {game_id,has_table};

		postAjax(obj,getEndpoint("update_has_table"),OutputData);
	}

	function getTournamentGame(event)
	{
		event.preventDefault();

		var tm_game = $(this).find('option:selected').attr("value");
		var game_id = retrieveGameID(this);

		obj = {game_id,tm_game};

		postAjax(obj,getEndpoint("update_tm_game"),OutputData);
	}

	function getAddonParam(event)
	{
		event.preventDefault();

		var game_id = retrieveGameID(this);
		var addon = $(this).find('option:selected').attr("value");

		obj = {game_id,addon};

		postAjax(obj,getEndpoint("update_addon"),OutputData);

	}

	function getIconData(event)
	{
		event.stopPropagation();
		event.preventDefault();
		
		var game_id = retrieveGameID(this);
		var icon_id = "#lbl_" + $(this).siblings().attr("for");

		var image = $(icon_id).prop('files')[0];
		var form_data = new FormData();

		form_data.append("game_id",game_id);

		if(!fileValidation(image))
		{
			displaySirBrummel("Dateifehler.");
		} else {
			if(image.length == 0)
			{
				form_data.append("file","0");
			} else {
				form_data.append("file",image);
			}
			postFileAjax(form_data,getEndpoint("update_game_icon"),OutputData);
		}
	}

	function getBannerData(event)
	{
		event.stopPropagation();
		event.preventDefault();

		var game_id = retrieveGameID(this);
		var banner_id = "#" + $(this).siblings().attr("for");

		var image = $(banner_id).prop('files')[0];
		var form_data = new FormData();

		form_data.append("game_id",game_id);

		if(!fileValidation(image))
		{
			displaySirBrummel("Dateifehler.");
		} else {
			if(image.length == 0)
			{
				form_data.append("file","0");
			} else {
				form_data.append("file",image);
			}
			postFileAjax(form_data,getEndpoint("update_game_banner"),OutputData);
		}
	}

	function getFile(event) //Upload for new Keys
	{
		event.stopPropagation();
		event.preventDefault();

		var input_id = "#list";
		var game = $("#clear").val();

		var image = $(input_id).prop('files')[0];
		var form_data = new FormData();

		form_data.append("game",game);
		
		if($(input_id).get(0).files.length == 0)
		{
			form_data.append("file","0");
		} else {
			form_data.append("file",image);
		}

		postFileAjax(form_data,getEndpoint("upload_keys"),OutputData);
	}

	function getGameIdToDelete(event)
	{
		event.preventDefault();

		var game_id = $(this).attr("id");

		var p_element = "#game-data-form";
		var c_element = "#admin_game_list";

		obj = {game_id,p_element,c_element};

		postAjax(obj,getEndpoint("delete_game"),OutputData);
	}

/*#############################################################################################
#################################### Achievements #############################################
###############################################################################################*/
	
	function getAcData(event)
	{
		event.stopPropagation();
		event.preventDefault();

		var ac_name = $("#ac_name").val();
		var ac_cat = $("#ac_cat").find("option:selected").attr("value");
		var ac_trigger = $("#ac_trigger").find("option:selected").attr("value");
		var ac_visible = $("input[name='ac_visible']:checked").serialize(); // möglicherweise fehlerhaft
		var ac_message = $("#ac_message").val();
		var image_id = "#ac_image";

		var image = $(image_id).prop('files')[0];
		var form_data = new FormData();

		form_data.append("ac_name",ac_name);
		form_data.append("ac_cat",ac_cat);
		form_data.append("ac_trigger",ac_trigger);
		form_data.append("ac_visible",ac_visible);
		form_data.append("ac_message",ac_message);
		
		if(!fileValidation(image))
		{
			console.log("File error");
		} else {
			if(image.length == 0)
			{
				form_data.append("file","0");
			} else {
				form_data.append("file",image);
			}
			postFileAjax(form_data,getEndpoint("create_achievement"),OutputData);
		}
	}

	function getSelectedItems(event)
	{
		event.preventDefault();

		var u_id = $("#user").find('option:selected').attr("value");
		var ac_id = $("#ac").find('option:selected').attr("value");

		obj = {u_id,ac_id};

		postAjax(obj,getEndpoint("assign_achievement"),OutputData);
	}

	function getChangedParam(event)
	{
		event.preventDefault();

		var ac_id = $(this).attr("data-ac-id");
		var param = $(this).attr("name");
		var param_val = $(this).find("option:selected").attr("value");

		obj = {ac_id,param,param_val};

		postAjax(obj,getEndpoint("change_achievement"),OutputData);
	}

	function getChangedAcImage(event)
	{
		event.preventDefault();

		var ac_id = $(this).attr("data-ac-id");
		var image_id = "#ac_image_" + ac_id;

		var p_element = ".ac_img_label";
		var c_element = ".ac_image_disp";

		var image = $(image_id).prop('files')[0];
		var form_data = new FormData();

		form_data.append("ac_id",ac_id);
		form_data.append("p_element",p_element);
		form_data.append("c_element",c_element);

		if(fileValidation(image))
		{
			form_data.append("file",image);
			postAjaxFile(form_data,getEndpoint("update_achievement_image"),OutputData);
		} else {
			console.log("File error");
		}
		
	}

	function getNewTrigger(event)
	{
		event.preventDefault();

		var n_trigger = $("#new_ac_trigger").val();

		obj = {n_trigger};

		postAjax(obj,getEndpoint("create_trigger"),OutputData);
	}
	
/*#############################################################################################
#################################### Tournaments ##############################################
###############################################################################################*/

	function getTmGame(event)
	{
		event.preventDefault();

		var game_id = $("#tm_game").find("option:selected").attr("value");
		var mode = $("#tm_mode").find("option:selected").attr("value");
		var mode_details = $("#tm_mode_details").find("option:selected").attr("value");
		
		var date_from = $("#tm_date_from").val();
		var time_hour_from = $("#tm_time_hour_from").val();
		var time_minute_from = $("#tm_time_minute_from").val();
		var tm_time_from = date_from + time_hour_from + time_minute_from;
		
		var date_to = $("#tm_date_to").val();
		var time_hour_to = $("#tm_time_hour_to").val();
		var time_minute_to = $("#tm_time_minute_to").val(); 
		var tm_time_to = date_to + time_hour_to + time_minute_to;

		var p_element = getParentElement(this);
		var c_element = getChildElement(this);

		obj = {game_id,mode,mode_details,tm_time_from,tm_time_to,p_element,c_element}
		
		postAjax(obj,getEndpoint("create_tournament"),OutputData);
	}

	function getDelTmData(event)
	{
		event.preventDefault();

		var tm_id = $(this).attr("id");
		
		var p_element = getParentElement(this);
		var c_element = getParentElement(this);

		obj = {tm_id,p_element,c_element};

		postAjax(obj,getEndpoint("delete_tournament"),OutputData);
		
	}

	function getStartingTmData(event)
	{
		event.preventDefault();

		var tm_id = $(this).attr("name");

		$(this).prop('disabled', true);

		obj = {tm_id};

		postAjax(obj,getEndpoint("start_tournament"),OutputData);

	}

	function getArchivData(event)
	{
		event.preventDefault();

		var tm_id = $(this).attr("name");

		var p_element = getParentElement(this);
		var c_element = getChildElement(this);

		obj = {tm_id,p_element,c_element};

		postAjax(obj,getEndpoint("archiv_tournament"),OutputData);
	}

	function getTournamentParam(event)
	{
		event.preventDefault();

		var game_id = $("#game_id").val();
		var mode = $("#tm_mode").find("option:selected").attr("value");
		var mode_details = $("#tm_mode_details").find("option:selected").attr("value");
		var tm_time_from = $("#tm_time_from").val(); //Fehlermeldung wenn nicht vollständig!
		var tm_time_to = $("#tm_time_to").val(); //Fehlermeldung wenn nicht vollständig!
		var vote_id = $("#vote_id").val();

		var p_element = getParentElement(this);
		var c_element = getChildElement(this);

		obj = {game_id, mode, mode_details, tm_time_from, tm_time_to, vote_id, p_element, c_element};

		$("#tm_create_popup").hide();

		postAjax(obj,getEndpoint("create_tournament"),OutputData);
	}

	function getVoteParam(event)
	{
		event.preventDefault();

		var vote_id = $(this).attr("data-tm-vote");

		var p_element = getParentElement(this);
		var c_element = getChildElement(this);

		obj = {vote_id,p_element,c_element};

		postAjax(obj,getEndpoint("delete_vote"),OutputData);
	}

/*#############################################################################################
#################################### World of Warcraft ########################################
###############################################################################################*/

function getPasswordData(event)
{
	event.preventDefault();

	var account_id = $(this).attr("id").substr(4);

	obj = {account_id};

	postAjax(obj,getEndpoint("reset_wow_password"),OutputData);
}

function getWoWRegionData(event)
{
	event.preventDefault();

	var region_id = $("#region_id").val();
	var region_name = $("#region_name").val();

	var p_element = getParentElement(this);
	var c_element = getChildElement(this);

	obj = {region_id,region_name,p_element,c_element};

	postAjax(obj,getEndpoint("add_wow_region"),OutputData);
}

function getNewRegionID(event)
{
	event.preventDefault();

	var region_id = $(this).parents("tr").attr("id");
	var new_region_id = $(this).siblings(".new_region_id").val();

	var p_element = getParentElement(this);
	var c_element = getChildElement(this);

	obj = {region_id,new_region_id,p_element,c_element};

	postAjax(obj,getEndpoint("update_region_id"),OutputData);
}

function getNewRegionName(event)
{
	event.preventDefault();

	var region_id = $(this).parents("tr").attr("id");
	var new_region_name = $(this).siblings(".new_region_name").val();

	var p_element = getParentElement(this);
	var c_element = $(p_element).find("td:nth-child(2)");

	obj = {region_id,new_region_name,p_element,c_element};

	postAjax(obj,getEndpoint("update_region_name"),OutputData);
}

function getDeleteWowRegion(event)
{
	event.preventDefault();

	var region_id = $(this).attr("id");

	var p_element = getParentElement(this);
	var c_element = getChildElement(this);

	obj = {region_id,p_element,c_element};

	postAjax(obj,getEndpoint("delete_wow_region"),OutputData);
}

function getCharData(event)
{
	event.preventDefault();

	var account_id = $(this).parent().attr("class").substr(4);
	var char_name = $(this).parent().attr("id");

	obj = {account_id,char_name};

	postAjax(obj,getEndpoint("delete_wow_char"),OutputData);
}

/*#############################################################################################
#################################### Lan ######################################################
###############################################################################################*/

	function getLanData(event)
	{
		event.preventDefault();

		var lan_title = $("#lan_title").val();
		var date_from = $("#lan_date_from").val();
		var date_to = $("#lan_date_to").val();

		var p_element = getParentElement(this);
		var c_element = getChildElement(this);

		obj = {lan_title,date_from,date_to,p_element,c_element};

		postAjax(obj,getEndpoint("create_lan"),OutputData);
	}


//########################### Send Data ##################################################################
	
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

//########################### Output Data ##################################################################

	function OutputData(result)
	{
		displaySirBrummel(result.message["messageText"]);

		if(result.hasOwnProperty("reloadProp"))
		{
			reloadContent(result.reloadProp);
		} else if (result.hasOwnProperty("imageReload")) {
			reloadImageContent(result.imageReload);
		}
	}

	function displaySirBrummel(err)
	{
		$("#result").show();
		$("#result").css("position","sticky");
		$("#result").css("top","75%");
		$("#result").html(err);
		$("#result").fadeOut(3000);
	}

	function reloadContent(reloadProperties)
	{
		if(reloadProperties["new_item"] !== 0)
		{
			setSpanResult(reloadProperties);
		} else {
			$(reloadProperties["parent_element"]).load(location.href + ' ' + reloadProperties["child_element"]);
		}
	}

	function setSpanResult(reloadProperties)
	{
		$(reloadProperties["parent_element"]).find(reloadProperties["child_element"]).text(reloadProperties["new_item"]);
		$(reloadProperties["parent_element"]).find(reloadProperties["child_element"]).attr("id",reloadProperties["new_item"]);

		$(".settings_edit").slideUp();
	}

/*
###########################################################
######################## VISUAL EFFECTS ###################
###########################################################
*/

	function displayTicketID(result)
	{
		displaySirBrummel(result.message["messageText"]);

		if(result.hasOwnProperty("ticket_id"))
		{
			console.log(this);
			$("#"+result.player_id).find(".ticket_id").html(result.ticket_id);
		}
	}
	
	function showEditField(event)
	{
		event.preventDefault();
		$(this).siblings(".settings_span").slideToggle();
	}
	
	function displayPopup(event)
	{
		event.preventDefault();

		var vote_id = $(this).attr("data-tm-vote");
		var game_id = $(this).attr("data-tm-game");
		var game_name = $(this).attr("data-tm-game-name");

		$("#game_id").val(game_id);
		$("#vote_id").val(vote_id);
		$("#game_name").val(game_name);

		$("#tm_create_popup").show();
	}

	function closePopup(event)
	{
		event.preventDefault();
		$("#tm_create_popup").hide();
	}

	function disableOnChange(event)
	{
		var mode_value = $(this).find("option:selected").attr("value");

		if(mode_value == "1")
		{
			$("#tm_mode_details").prop('disabled','disabled');
		} else {
			$("#tm_mode_details").prop('disabled', false);
		}
	}

	function displayAccountChars(event)
	{
		event.preventDefault();

		$(this).siblings(".char_list").slideToggle();

	}

/*
###########################################################
######################## TIME-EVENT #######################
###########################################################
*/

setInterval(refreshVotes,20000);

function refreshVotes()
{
	$("#vote_page").load(location.href + ' #tm_votes');
}

	$(document).on("click","#create", getNumber);
	$("#upload").on("click", getFile);
	$(document).on("click",".p_button_delete", getId);
	$("#b_add_game").on("click", getNewGame);
	$(document).on("click","#activate_ac", getSelectedItems);
	$(document).on("click","#create_ac",getAcData);
	$(document).on("change",".admin_ac_trig",getChangedParam);
	$(document).on("change",".admin_ac_cat",getChangedParam);
	$(document).on("change",".admin_ac_visib",getChangedParam);
	$(document).on("change",".ac_image",getChangedAcImage);
	$(document).on("change",".sec_is_addon",getAddonParam);
	$(document).on("change",".sec_has_table",getHasTable);
	$(document).on("change",".sec_tm_game",getTournamentGame)
	$(document).on("change",".sec_icon_upload",getIconData);
	$(document).on("change",".sec_banner_upload",getBannerData);
	$(document).on("click",".send_gn",getNewGameName);
	$(document).on("click",".send_gst",getNewShortTitle);
	$(document).on("click",".delete_game",getGameIdToDelete);
	$(document).on("click","#create_tm",getTmGame);
	$(document).on("click",".delete_tm",getDelTmData);
	$(document).on("click",".start_tm",getStartingTmData);
	$(document).on("click",".archiv_tm",getArchivData);
	$(document).on("click","#create_new_trigger",getNewTrigger);
	$("#start_tm").on("click",getTournamentParam);
	$(document).on("click",".delete_vote",getVoteParam);
	$(document).on("click",".define_tm",displayPopup);
	$(document).on("click","#tm_close_popup",closePopup);
	$(document).on("change","#tm_mode",disableOnChange);
	$(document).on("click",".create_ticket",getTicketData);
	$(document).on("click","#b_add_region",getWoWRegionData);
	$(document).on("click",".delete_wow_region",getDeleteWowRegion);
	$(document).on("click",".settings_edit",showEditField);
	$(document).on("click",".send_region_id",getNewRegionID);
	$(document).on("click",".send_region_name",getNewRegionName);
	$(document).on("click",".show_chars",displayAccountChars);
	$(document).on("click",".reset_wow_password",getPasswordData);
	$(document).on("click",".delete_char",getCharData);
	$(document).on("click","#create_lan",getLanData);

});