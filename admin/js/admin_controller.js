function getEndpoint(param)
{
	var endpoint;
	//Endpoints
	switch(param) {
		case "create_new_account":
			endpoint = "admin/player/edit/create_new_player.php";
            break;
        case "delete_player":
            endpoint = "admin/player/edit/delete_player.php";
            break;
        case "delete_team":
            endpoint = "admin/team/edit/delete_team.php";
            break;
        case "assign_achievement":
            endpoint = "admin/achievement/edit/assign_achievement.php";
            break;
        case "change_achievement":
            endpoint = "admin/achievement/edit/change_param.php";
            break;
        case "update_addon":
            endpoint = "admin/game/edit/change_addon.php";
            break;
        case "update_has_table":
            endpoint = "admin/game/edit/change_hastable.php";
            break;
        case "update_rawname":
            endpoint = "admin/game/edit/change_rawname.php";
            break;
        case "update_gamename":
            endpoint = "admin/game/edit/change_gamename.php";
            break;
        case "create_trigger":
            endpoint = "admin/achievement/edit/create_trigger.php";
            break;
        case "delete_tournament":
            endpoint = "admin/tm/delete/delete_tm.php";
            break;
        case "start_tournament":
            endpoint = "admin/tm/edit/start_tm.php";
            break;
        case "create_new_game":
            endpoint = "admin/game/edit/add_game.php";
            break;
        case "update_game_icon":
            endpoint = "admin/game/edit/update_icon.php";
            break;
        case "update_game_banner":
            endpoint = "admin/game/edit/update_banner.php";
            break;
        case "upload_keys":
            endpoint = "admin/key/create_keylist.php";
            break;
        case "create_achievement":
            endpoint = "admin/achievement/edit/create_achievement.php";
            break;
        case "update_achievement_image":
            endpoint = "admin/achievement/edit/change_acimage.php";
            break;
        case "create_tournament":
            endpoint = "admin/tm/create/create_tm.php";
            break;
        case "vote_tournament":
            endpoint = "admin/tm/create/vote_tm.php";
            break;
        case "delete_vote":
            endpoint = "admin/tm/delete/delete_vote.php";
            break;
	}

	return endpoint;

}