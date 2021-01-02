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

		postAjax(obj,getEndpoint("delete_player"),setResult);

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

		postAjax(obj,getEndpoint("delete_team"),showResult);
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
		
		var p_element = getParentElement(this);
		var c_element = getChildElement(this);

		var form_data = new FormData();

		form_data.append("game",game);
		form_data.append("raw_name",raw_name);
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

	function getNewRawName(event)
	{
		event.preventDefault();

		p_element = getParentElement(this);
		c_element = getChildElement(this);
		
		var n_raw = $(this).siblings(".game_raw_name").val();
		var game_id = retrieveGameID(this);

		obj = {game_id,n_raw,p_element,c_element};

		postAjax(obj,getEndpoint("update_rawname"),setSpanResult);
	}

	function getNewGameName(event)
	{
		event.preventDefault();

		var p_element = getParentElement(this);
		var c_element = getChildElement(this);
		
		var game_name = $(this).siblings(".game_name").val();
		var reloadID = $(this).closest("td").children("span").attr("id");
		var game_id = retrieveGameID(this);

		obj = {game_id,game_name,p_element,c_element};

		postAjax(obj,getEndpoint("update_gamename"),setSpanResult);
	}

	function getNewShortTitle(event)
	{
		event.preventDefault();

		var p_element = getParentElement(this);
		var c_element = getChildElement(this);

		var game_short_title = $(this).siblings(".game_short_title").val();
		var game_id = retrieveGameID(this);

		obj = {game_id,game_short_title,p_element,c_element};

		postAjax(obj,getEndpoint("update_shorttitle"),setSpanResult);
	}

	function getHasTable(event)
	{
		event.preventDefault();

		var has_table = $(this).find('option:selected').attr("value");
		var game_id = retrieveGameID(this);

		obj = {game_id,has_table};

		postAjax(obj,getEndpoint("update_has_table"),displayResult);
	}

	function getAddonParam(event)
	{
		event.preventDefault();

		var game_id = retrieveGameID(this);
		var addon = $(this).find('option:selected').attr("value");

		obj = {game_id,addon};

		postAjax(obj,getEndpoint("update_addon"),displayResult);

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
			displayResult("Dateifehler.");
		} else {
			if(image.length == 0)
			{
				form_data.append("file","0");
			} else {
				form_data.append("file",image);
			}
			postFileAjax(form_data,getEndpoint("update_game_icon"),showResult(icon_id));
		}
	}

	function getBannerData(event)
	{
		event.stopPropagation();
		event.preventDefault();

		var game_id = retrieveGameID(this);
		var banner_id = "#" + $(this).siblings().attr("for");

		console.log(banner_id);
		var image = $(banner_id).prop('files')[0];
		var form_data = new FormData();

		form_data.append("game_id",game_id);

		if(!fileValidation(image))
		{
			displayResult("Dateifehler.");
		} else {
			if(image.length == 0)
			{
				form_data.append("file","0");
			} else {
				form_data.append("file",image);
			}
			postFileAjax(form_data,getEndpoint("update_game_banner"),showResult(banner_id));
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

		postFileAjax(form_data,getEndpoint("upload_keys"),displayResult);
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
			postFileAjax(form_data,getEndpoint("create_achievement"),setResult);
		}
	}

	function getSelectedItems(event)
	{
		event.preventDefault();

		var u_id = $("#user").find('option:selected').attr("value");
		var ac_id = $("#ac").find('option:selected').attr("value");

		obj = {u_id,ac_id};

		postAjax(obj,getEndpoint("assign_achievement"),setResult);
	}

	function getChangedParam(event)
	{
		event.preventDefault();

		var ac_id = $(this).attr("data-ac-id");
		var param = $(this).attr("name");
		var param_val = $(this).find("option:selected").attr("value");

		obj = {ac_id,param,param_val};

		postAjax(obj,getEndpoint("change_achievement"),setResult);
	}

	function getChangedAcImage(event)
	{
		event.preventDefault();

		var ac_id = $(this).attr("data-ac-id");
		var image_id = "#ac_image_" + ac_id;

		var image = $(image_id).prop('files')[0];
		var form_data = new FormData();

		form_data.append("ac_id",ac_id);

		if(fileValidation(image))
		{
			form_data.append("file",image);
			postAjaxFile(form_data,getEndpoint("update_achievement_image"),setResult);
		} else {
			console.log("File error");
		}
		
	}

	function getNewTrigger(event)
	{
		event.preventDefault();

		var n_trigger = $("#new_ac_trigger").val();

		obj = {n_trigger};

		postAjax(obj,getEndpoint("create_trigger"),setResult);
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

		obj = {game_id,mode,mode_details,tm_time_from,tm_time_to}
		
		postAjax(obj,getEndpoint("create_tournament"),setResult);
	}

	function getDelTmData(event)
	{
		event.preventDefault();

		var tm_id = $(this).attr("id");
		
		// Define Elements for immediate reaction of the web-page
		var reload_element = $(this).parents("table").attr("id");
		var parent_reload = $(this).parents("div").attr("id");

		obj = {tm_id};

		postAjax(obj,getEndpoint("delete_tournament"),setResult);
		
	}

	function getStartingTmData(event)
	{
		event.preventDefault();

		var tm_id = $(this).attr("name");

		$(this).prop('disabled', true);

		obj = {tm_id};

		postAjax(obj,getEndpoint("start_tournament"),setResult);

	}

	function getArchivData(event)
	{
		event.preventDefault();

		var tm_id = $(this).attr("name");

		obj = {tm_id};

		postAjax(obj,getEndpoint("archiv_tournament"),setResult);
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

		obj = {game_id, mode, mode_details, tm_time_from, tm_time_to, vote_id};

		$("#tm_create_popup").hide();

		postAjax(obj,getEndpoint("create_tournament"),setResult);
	}

	function getVoteParam(event)
	{
		event.preventDefault();

		var vote_id = $(this).attr("data-tm-vote");

		obj = {vote_id};

		postAjax(obj,getEndpoint("delete_vote"),setResult);
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

		obj = {lan_title,date_from,date_to};

		postAjax(obj,getEndpoint("create_lan"),setResult);
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

	function displayResult(err)
	{
		$("#result").show();
		$("#result").css("position","sticky");
		$("#result").css("top","75%");
		$("#result").html(err);
		$("#result").fadeOut(3000);
	}

	function reloadContent(reloadProperties)
	{
		$(reloadProperties["parent_element"]).load(location.href + ' ' + reloadProperties["child_element"]);
	}

// DECAPRETATED

	function showMessage(result)
	{
		displayResult(result.message);
	}

	function displayTicketID(result)
	{
		displayResult(result.message["messageText"]);

		if(result.hasOwnProperty("ticket_id"))
		{
			console.log(this);
			$("#"+result.player_id).find(".ticket_id").html(result.ticket_id);
		}
	}

	function setResult(result)
	{
		displayResult(result.message["messageText"]);

		if(result.hasOwnProperty("reloadProp"))
		{
			reloadContent(result.reloadProp["parent_element"],result.reloadProp["child_element"]);
		}
	}

	function setSpanResult(result)
	{
		displayResult(result.message["messageText"]);

		$(result.reloadProp["parent_element"]).find(result.reloadProp["child_element"]).text(result.reloadProp["new_item"]);
		$(result.reloadProp["parent_element"]).find(result.reloadProp["child_element"]).attr("id",result.reloadProp["new_item"]);

		$(".settings_gn").slideUp();
		$(".settings_grn").slideUp();
	}
	
	function showResult(result,reloadID)
	{
		displayResult(result.message["messageText"]);
		$("" + reloadID + "").html(result.new_value);
	}

	function showInputField(event)
	{
		event.preventDefault();
		$(this).siblings(".settings_gn").slideToggle();
	}

	function showGRNInputField(event)
	{
		event.preventDefault();
		$(this).siblings(".settings_grn").slideToggle();
	}

	function showGSTInputField(event)
	{
		event.preventDefault();
		$(this).siblings(".settings_gst").slideToggle();
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
	$("#b_del_team").on("click", getTeamId);
	$("#b_add_game").on("click", getNewGame);
	$(document).on("click","#activate_ac", getSelectedItems);
	$(document).on("click","#create_ac",getAcData);
	$(document).on("change",".admin_ac_trig",getChangedParam);
	$(document).on("change",".admin_ac_cat",getChangedParam);
	$(document).on("change",".admin_ac_visib",getChangedParam);
	$(document).on("change",".ac_image",getChangedAcImage);
	$(document).on("change",".sec_is_addon",getAddonParam);
	$(document).on("change",".sec_has_table",getHasTable);
	$(document).on("change",".sec_icon_upload",getIconData);
	$(document).on("change",".sec_banner_upload",getBannerData);
	$(document).on("click",".send_grn",getNewRawName);
	$(document).on("click",".send_gn",getNewGameName);
	$(document).on("click",".send_gst",getNewShortTitle);
	$(document).on("click",".settings_edit",showInputField);
	$(document).on("click",".settings_edit",showGRNInputField); // GRN = game_raw_name
	$(document).on("click",".settings_edit",showGSTInputField); // GST = game_short_title
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
	$(document).on("click","#create_lan",getLanData);

});