$(document).ready(function(){

	var files;

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

		return image_data;
	}

	function getNumber(event)
	{
		event.preventDefault();

		var c_name = $("#cover_name").val();

		createNewPlayer(c_name,displayResult);
	}

	function getId(event)
	{
		event.preventDefault();

		var id = $(this).children("i").attr("id");

		deletePlayer(id,displayResult);
	}

	function getTeamId(event)
	{
		event.preventDefault();

		var teamId = $("#del_team").find('option:selected').attr("value");

		deleteTeam(teamId,showResult);
	}
	
	function getNewGame(event)
	{
		event.stopPropagation();
		event.preventDefault();
		
		var game = $("#new_game").val();
		var raw_name = $("input[name='new_raw_table']:checked").serialize();
		var image_id = "#new_game_icon";

		addNewGame(game,raw_name,getFileData(image_id),displayResult);
	}
	
	function getSelectedItems(event)
	{
		event.preventDefault();

		var u_id = $("#user").find('option:selected').attr("value");
		var ac_id = $("#ac").find('option:selected').attr("value");
		
		assignAchievement(ac_id,u_id,setResult);
	}

	function getChangedParam(event)
	{
		event.preventDefault();

		var ac_id = $(this).attr("data-ac-id");
		var param = $(this).attr("name");
		var param_val = $(this).find("option:selected").attr("value");

		changeParam(ac_id,param,param_val,setResult);
	}

	function getChangedAcImage(event)
	{
		event.preventDefault();

		var ac_id = $(this).attr("data-ac-id");
		var image_id = "#ac_image_" + ac_id;

		changeAcImage(ac_id,getFileData(image_id),setResult);
	}
	
//############################ Game-Data ###################################
	
	function retrieveGameID(start)
	{
		var game_id = $(start).parents(".admin_game_data").find(".game_id").html();

		return game_id;
	}

	function getAddonParam(event)
	{
		event.preventDefault();

		var addon = $(this).find('option:selected').attr("value");

		updateAddon(retrieveGameID(this),addon,displayResult);
	}
	

	function getHasTable(event)
	{
		event.preventDefault();

		var has_table = $(this).find('option:selected').attr("value");

		updateHasTable(retrieveGameID(this),has_table,displayResult);
	}
	
	function getIconData(event)
	{
		event.stopPropagation();
		event.preventDefault();
		
		var game_id = retrieveGameID(this);
		var icon_id = "#lbl_" + $(this).siblings().attr("for");
		
		updateIcon(game_id,getFileData(this),showResult(icon_id));
	}
	
	function getNewRawName(event)
	{
		event.preventDefault();
		
		var n_raw = $(this).siblings(".game_raw_name").val();
		
		updateRawName(retrieveGameID(this),n_raw,showResult);
	}
	
	function getNewGameName(event)
	{
		event.preventDefault();
		
		var n_gname = $(this).siblings(".game_name").val();
		var reloadID = $(this).closest("td").children("span").attr("id");

		updateGameName(retrieveGameID(this),n_gname,showResult(reloadID));
	}

	function getGnData(event)
	{
		event.preventDefault();
		
	}

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

		createAcData(ac_name,ac_cat,ac_trigger,ac_visible,ac_message,getFileData(image_id),setResult);
	}

	function getNewTrigger(event)
	{
		event.preventDefault();

		var trigger_name = $("#new_ac_trigger").val();

		createNewTrigger(trigger_name,setResult);
	}

	function getFile(event) //Upload for new Keys
	{
		event.stopPropagation();
		event.preventDefault();

		var input_id = "#list";
		var game = $("#clear").val();

		uploadFile(getFileData(input_id),game,displayResult);

	}

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

		createTm(tm_game,tm_mode,tm_mode_details,tm_min_player,tm_datetime,getFileData(input_id),setResult);
	}

	function getDelTmData(event)
	{
		event.preventDefault();

		var tm_id = $(this).attr("id");
		
		// Define Elements for immediate reaction of the web-page
		var reload_element = $(this).parents("table").attr("id");
		var parent_reload = $(this).parents("div").attr("id");
		
		deleteTm(tm_id,setResult);
	}

	function getStartingTmData(event)
	{
		event.preventDefault();

		var tm_id = $(this).attr("name");

		$(this).prop('disabled', true);
		
		startTm(tm_id,setResult);
	}

	function deleteTeam(teamId,fn)
	{
		return $.ajax({
			type: "get",
			dataType: "json",
			url: "admin/team/edit/delete_team.php",
			data: {
				id:teamId
			},
			success: fn
		});
	}
	
	function addNewGame(game,raw_name,data,fn)
	{
		return $.ajax({
			type: "post",
			url: "admin/game/edit/add_game.php?game=" + game + "&raw_name=" + raw_name,
			cache: false,
			contentType: false,
			processData: false,
			data: data,
			success: fn
		});
	}

	function createNewPlayer(c_name, fn)
	{
		return $.ajax({
			type: "post",
			url: "admin/player/edit/create_new_player.php",
			data: {
				cover: c_name
			},
			success: fn
		});
	}

	function deletePlayer(id, fn)
	{

		return $.ajax({
			type: "post",
			url: "admin/player/edit/delete_player.php",
			data: {
				player:id
			},
			success: fn
		});
	}

	function createAcData(ac_name,ac_cat,ac_trigger,ac_visible,ac_message,data,fn)
	{
		return $.ajax({
			type: "post",
			url: "admin/achievement/edit/create_achievement.php?ac_name=" + ac_name + "&ac_cat=" + ac_cat + "&ac_trigger=" + ac_trigger + "&ac_visible=" + ac_visible + "&ac_message=" + ac_message,
			dataType: "json",
			cache: false,
			contentType: false,
			processData: false,
			data: data,
			success: fn
		});
	}

	function uploadFile(data,game,fn)
	{
		return $.ajax({
			type: "post",
			dataType: "json",
			url: "admin/key/create_keylist.php?game=" + game,
			cache: false,
			contentType: false,
			processData: false,
			data: data,
			success: fn
		});
	}

	function assignAchievement(item,name,fn)
	{
		return $.ajax({
			type: "post",
			url: "admin/achievement/edit/assign_achievement.php",
			dataType: "json",
			data: {
				ac_id:item,
				u_name:name
			},
			success: fn
		});
	}

	function createNewTrigger(trigger_name,fn)
	{
		return $.ajax({
			type: "post",
			url: "admin/achievement/edit/create_trigger.php",
			dataType: "json",
			data: {
				n_trigger:trigger_name
			},
			success: fn
		});
	}

	function changeParam(ac_id,param,param_val,fn)
	{
		return $.ajax({
			type: "post",
			url: "admin/achievement/edit/change_param.php",
			dataType: "json",
			data: {
				ac_id:ac_id,
				param:param,
				param_val: param_val
			},
			success: fn
		});
	}

	function changeAcImage(ac_id,ac_image,fn)
	{
		return $.ajax({
			type: "post",
			url: "admin/achievement/edit/change_acimage.php?ac_id=" + ac_id,
			dataType: "json",
			cache: false,
			contentType: false,
			processData: false,
			data: ac_image,
			success: fn
		});
	}

	function updateAddon(game_id,addon,fn)
	{
		return $.ajax({
			type: "post",
			dataType: "json",
			url: "admin/game/edit/change_addon.php",
			data: {
				game_id:game_id,
				addon:addon
			},
			success: fn
		});
	}
	
	function updateHasTable(game_id,has_table,fn)
	{
		return $.ajax({
			type: "post",
			url: "admin/game/edit/change_hastable.php",
			data: {
				game_id:game_id,
				has_table:has_table
			},
			success: fn
		});
	}
	
	function updateIcon(game_id,image_data,fn)
	{
		return $.ajax({
			type: "post",
			url: "admin/game/edit/update_icon.php?game_id=" + game_id,
			cache: false,
			contentType: false,
			processData: false,
			data: image_data,
			success: fn
		});
	}
	
	function updateRawName(game_id,n_raw,fn)
	{
		return $.ajax({
			type: "post",
			url: "admin/game/edit/change_rawname.php",
			data: {
				game_id:game_id,
				n_raw:n_raw
			},
			success: fn
		});
	}
	
	function updateGameName(game_id,n_gname,fn)
	{
		return $.ajax({
			type: "post",
			dataType: "json",
			url: "admin/game/edit/change_gamename.php",
			data: {
				game_id:game_id,
				game_name:n_gname
			},
			success: fn
		});
	}

	function createTm(tm_game,tm_mode,tm_mode_details,tm_min_player,tm_datetime,image_data,fn)
	{
		return $.ajax({
			type: "post",
			dataType: "json",
			url: "admin/tm/create/create_tm.php?game=" + tm_game + "&mode=" + tm_mode + "&mode_details=" + tm_mode_details + "&min_player=" + tm_min_player + "&datetime=" + tm_datetime,
			cache: false,
			contentType: false,
			processData: false,
			data: image_data,
			success: fn
		});
	}

	function deleteTm(tm_id,fn)
	{
		return $.ajax({
			type: "post",
			dataType: "json",
			url: "admin/tm/delete/delete_tm.php",
			data: {
				tm_id:tm_id
			},
			success: fn
		});
	}

	function startTm(tm_id,fn)
	{
		return $.ajax({
			type: "post",
			dataType: "json",
			url: "admin/tm/edit/start_tm.php",
			data: {
				tm_id:tm_id
			},
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

});