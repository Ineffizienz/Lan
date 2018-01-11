$(document).ready(function(){

	var files;

	function getNumber(event)
	{
		event.preventDefault();

		var count = $("#count").val();
		var c_name = $("#cover_name").val();

		createNewPlayer(count,c_name,displayResult);
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
	
	function getSelectedItems(event)
	{
		event.preventDefault();

		u_id = $("#user").find('option:selected').attr("name");
		ac_id = $("#ac").find('option:selected').attr("name");
		
		assignAchievement(ac_id,u_id,displayResult);
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
		var file_data = $("#ac_image").prop('files')[0];
		var data = new FormData();

		data.append("file",file_data);

		createAcData(ac_name,ac_cat,ac_trigger,ac_visible,ac_message,data,displayResult);
	}

	function getFile(event)
	{
		event.stopPropagation();
		event.preventDefault();

		var file_data = $('#list').prop('files')[0];
		var data = new FormData();
		var game = $("#clear").val();

		data.append("file",file_data);


		uploadFile(data,game,displayResult);

	}

	function deleteTeam(teamId,fn)
	{
		console.log(teamId);
		return $.ajax({
			type: "get",
			url: "admin/team/edit/delete_team.php",
			data: {
				id:teamId
			},
			success: fn
		});
	}

	function createNewPlayer(count, c_name, fn)
	{
		return $.ajax({
			type: "post",
			url: "admin/player/edit/create_new_player.php",
			data: {
				i:count,
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

	function displayResult(err)
	{
		document.getElementById("result").innerHTML = err;
	}




	$("#create").on("click", getNumber);
	$("#upload").on("click", getFile);
	$(".p_button_delete").on("click", getId);
	$("#b_del_team").on("click", getTeamId);
	$(document).on("click","#activate_ac", getSelectedItems);
	$(document).on("click","#create_ac",getAcData);

});