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
		case "change_profil_image":
			endpoint = "include/profil/profil_image.php";
			break;
	}

	return endpoint;

}