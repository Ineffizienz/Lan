$(document).ready(function(){

	var files;

	function getFileData(input_id)
	{
		//var image = $("#" + input_id + "").prop('files')[0];
		var image = $(input_id).prop('files')[0];
		var image_data = new FormData();

		image_data.append("file",image);

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

		var teamId = $("#del_team").find('option:selected').attr("name");

		deleteTeam(teamId,displayResult);
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

		var u_id = $("#user").find('option:selected').attr("name");
		var ac_id = $("#ac").find('option:selected').attr("name");
		
		assignAchievement(ac_id,u_id,displayResult);
	}

	function getChangedParam(event)
	{
		event.preventDefault();

		var ac_id = $(this).parents("#admin_ac_list").find(".ac_id").html();
		var trigger = $("#admin_ac_trig").find('option:selected').attr("name");
		var category = $("#admin_ac_cat").find('option:selected').attr("name");
		var visib = $("#admin_ac_visib").find('option:selected').attr("name");

		changeParam(ac_id,trigger,category,visib,displayResult);
	}
	
//############################ Game-Data ###################################
	
	function retrieveGameID(start)
	{
		var game_id = $(start).parents(".admin_game_data").find(".game_id").html();

		return game_id;
	}

	function getHasTable(event)
	{
		event.preventDefault();

		var has_table = $(this).find('option:selected').attr("name");

		updateHasTable(retrieveGameID(this),has_table,displayResult)
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
		var ac_cat = $("#ac_cat").find("option:selected").attr("name");
		var ac_trigger = $("#ac_trigger").find("option:selected").attr("name");
		var ac_visible = $("input[name='ac_visible']:checked").serialize();
		var ac_message = $("#ac_message").val();
		var image_id = "#ac_image";

		createAcData(ac_name,ac_cat,ac_trigger,ac_visible,ac_message,getFileData(image_id),displayResult);
	}

	function getFile(event)
	{
		event.stopPropagation();
		event.preventDefault();

		var input_id = "#list";
		var game = $("#clear").val();

		uploadFile(getFileData(input_id),game,displayResult);

	}

	function deleteTeam(teamId,fn)
	{
		return $.ajax({
			type: "get",
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
			data: {
				ac_id:item,
				u_name:name
			},
			success: fn
		});
	}

	function changeParam(ac_id,trigger,category,visib,fn)
	{
		return $.ajax({
			type: "post",
			url: "admin/achievement/edit/change_param.php",
			data: {
				ac_id:ac_id,
				trigger:trigger,
				category: category,
				visib: visib
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

	function displayResult(err)
	{
		$("#result").show();
		$("#result").html(err);
		$("#result").fadeOut(2000);
	}

	function showResult(result,reloadID)
	{
		displayResult(result.message);
		console.log(result.new_value);
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
	$(document).on("change","#admin_ac_trig",getChangedParam);
	$(document).on("change","#admin_ac_cat",getChangedParam);
	$(document).on("change","#admin_ac_visib",getChangedParam);
	$(document).on("change",".sec_has_table",getHasTable);
	$(document).on("change",".sec_icon_upload",getIconData);
	$(document).on("click",".send_grn",getNewRawName);
	$(document).on("click",".send_gn",getNewGameName);
	$(document).on("click",".settings_edit",showInputField);
	$(document).on("click",".settings_edit",showGRNInputField);

});