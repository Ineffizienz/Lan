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

/*#############################################################################################
#################################### User ##################################################### 
###############################################################################################*/
	
	function getNumber(event)
	{
		event.preventDefault();

		var c_name = $("#cover_name").val();

		obj = {c_name};

		postAjax(obj,getEndpoint("create_new_account"),displayResult);
	}

	function getId(event)
	{
		event.preventDefault();

		var player = $(this).children("i").attr("id");

		obj = {player};

		postAjax(obj,getEndpoint("delete_player"),displayResult);

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

		var image = $("#new_game_icon").prop('files')[0];
		var form_data = new FormData();

		form_data.append("game",game);
		form_data.append("raw_name",raw_name);

		if($("#new_game_icon").get(0).files.length == 0)
		{
			form_data.append("file","0");
		} else {
			form_data.append("file",image);
		}

		postFileAjax(form_data,getEndpoint("create_new_game"),displayResult);
	}

	function getNewRawName(event)
	{
		event.preventDefault();
		
		var n_raw = $(this).siblings(".game_raw_name").val();
		var game_id = retrieveGameId(this);

		obj = {game_id,n_raw};

		postAjax(obj,getEndpoint("update_rawname"),showResult);
	}

	function getNewGameName(event)
	{
		event.preventDefault();
		
		var game_name = $(this).siblings(".game_name").val();
		var reloadID = $(this).closest("td").children("span").attr("id");
		var game_id = retrieveGameID(this);

		obj = {game_id,game_name};

		postAjax(obj,getEndpoint("update_gamename"),showResult(reloadID));
	}

	function getHasTable(event)
	{
		event.preventDefault();

		var has_table = $(this).find('option:selected').attr("value");
		var game_id = retrieveGameId(this);

		obj = {game_id,has_table};

		postAjax(obj,getEndpoint("update_has_table"),displayResult);
	}

	function getAddonParam(event)
	{
		event.preventDefault();

		var addon = $(this).find('option:selected').attr("value");

		obj = {addon};

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

		if($(icon_id).get(0).files.length == 0)
		{
			form_data.append("file","0");
		} else {
			form_data.append("file",image);
		}

		postFileAjax(form_data,getEndpoint("update_game_icon"),showResult(icon,id));
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
		
		if($(image_id).get(0).files.length == 0)
		{
			form_data.append("file","0");
		} else {
			form_data.append("file",image);
		}

		postFileAjax(form_data,getEndpoint("create_achievement"),setResult);
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
		
		if($(image_id).get(0).files.length == 0)
		{
			form_data.append("file","0");
		} else {
			form_data.append("file",image);
		}
	
		postAjaxFile(form_data,getEndpoint("update_achievement_image"),setResult);
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
		event.stopPropagation();
		event.preventDefault();

		var tm_game = $("#tm_game").find("option:selected").attr("value");
		var tm_mode = $("#tm_mode").find("option:selected").attr("value");
		var tm_mode_details = $("#tm_mode_details").find("option:selected").attr("value");
		var tm_min_player = $("#tm_min_player").find("option:selected").attr("value");
		var tm_date = $("#tm_date").val();
		var tm_time_hour = $("#tm_time_hour").val();
		var tm_time_minute = $("#tm_time_minute").val();
		var tm_datetime = tm_date + tm_time_hour + tm_time_minute;

		var input_id = "#tm_banner";

		var image = $(input_id).prop('files')[0];
		var form_data = new FormData();

		form_data.append("tm_game",tm_game);
		form_data.append("tm_mode",tm_mode);
		form_data.append("tm_mode_details",tm_mode_details);
		form_data.append("tm_min_player",tm_min_player);
		form_data.append("tm_date",tm_date);
		form_data.append("tm_time_hour",tm_time_hour);
		form_data.append("tm_time_minute",tm_time_minute);
		form_data.append("tm_datetime",tm_datetime);
		
		if($(input_id).get(0).files.length == 0)
		{
			form_data.append("file","0");
		} else {
			form_data.append("file",image);
		}

		postFileAjax(form_data,getEndpoint("create_tournament"),setResult);
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

	function getTournamentParam(event)
	{
		event.preventDefault();

		var game_id = $(".game").attr("data-tm-game");
		var vote_id = $(this).attr("data-tm-vote");

		obj = {game_id, vote_id};

		postAjax(obj,getEndpoint("start_tournament"),setResult);
	}

	function getVoteParam(event)
	{
		event.preventDefault();

		var vote_id = $(this).attr("data-tm-vote");

		obj = {vote_id};

		postAjax(obj,getEndpoint("delete_vote"),setResult);
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

	function displayResult(err)
	{
		$("#result").show();
		$("#result").html(err);
		$("#result").fadeOut(3000);
	}

	function showMessage(result)
	{
		displayResult(result.message);
	}

	function setResult(result)
	{
		displayResult(result.message);

		if(result.parent_element in result)
		{
			reloadContent(result.parent_element,result.child_element);
		}
	}

	function reloadContent(parent_element,child_element)
	{
		if($.isArray(parent_element))
		{
			$.each(parent_element, function(key, value) {
				$(value).load(window.location.href + ' ' + child_element[key]);
			});
		} else {
			$(parent_element).load(window.location.href + ' ' + child_element);
		}
	}
	
	function showResult(result,reloadID)
	{
		displayResult(result.message);
		console.log(result.message);
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



	$("#create").on("click", getNumber);
	$("#upload").on("click", getFile);
	$(".p_button_delete").on("click", getId);
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
	$(document).on("click",".send_grn",getNewRawName);
	$(document).on("click",".send_gn",getNewGameName);
	$(document).on("click",".settings_edit",showInputField);
	$(document).on("click",".settings_edit",showGRNInputField);
	$(document).on("click","#create_tm",getTmGame);
	$(document).on("click",".delete_tm",getDelTmData);
	$(document).on("click",".start_tm",getStartingTmData);
	$(document).on("click","#create_new_trigger",getNewTrigger);
	$("#start_tm").on("click",getTournamentParam);
	$("#delete_vote").on("click",getVoteParam);

});