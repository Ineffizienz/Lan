function getEndpoint(param)
{
	var endpoint;
	//Endpoints
	switch(param) {
		case "create_team":
			endpoint = "include/team/create_team.php";
			break;
		case "reg_wow_account":
			endpoint = "include/wow_server/register.php";
			break;
		case "reset_password":
			endpoint = "include/wow_server/reset_password.php";
			break;
		case "get_gamekey":
			endpoint = "include/key/generate.php";
			break;
		case "reject_gamekey":
			endpoint = "include/key/reject_key.php";
			break;
		case "join_team":
			endpoint = "include/team/join_team.php";
			break;
		case "leave_team":
			endpoint = "include/team/leave_team.php";
			break;
		case "delete_team":
			endpoint = "include/team/delete_team.php";
			break;
		case "get_p_status":
			endpoint = "include/player/status.php";
			break;
		case "change_username":
			endpoint = "include/profil/change_user.php";
			break;
		case "add_pref":
			endpoint = "include/profil/add_pref.php";
			break;
		case "remove_pref":
			endpoint = "include/profil/remove_pref.php";
			break;
		case "change_profil_image":
			endpoint = "include/profil/profil_image.php";
			break;
		case "vote_tm":
			endpoint = "include/tournament/create/vote_tm.php";
			break;
		case "add_vote":
			endpoint = "include/tournament/create/add_vote.php";
			break;
		case "join_tm":
			endpoint = "include/tournament/edit/join_tm.php";
			break;
		case "leave_tm":
			endpoint = "include/tournament/edit/leave_tm.php";
			break;
		case "enter_result":
			endpoint = "include/tournament/edit/enter_result.php";
			break;
		case "get_data":
			endpoint = "include/event/event_functions.php";
			break;
	}

	return endpoint;

}