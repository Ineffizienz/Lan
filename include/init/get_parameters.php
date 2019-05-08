<?php


/*
###########################################################
######################## AUTH #############################
###########################################################
*/

function getRegIps($con) //bezieht die registrierten IPs
{
	$result = mysqli_query($con,"SELECT ip FROM player");
	while ($row=mysqli_fetch_array($result))
	{
		$ip[] = $row["ip"];
	}

	return $ip;
}

function getFirstLoginByIp($con,$ip) // reg_name.php
{
	$result = mysqli_query($con,"SELECT first_login FROM player WHERE ip = '$ip'");
	while ($row=mysqli_fetch_array($result))
	{
		$f_login = $row["first_login"];
	}

	return $f_login;
}

function getFirstLoginById($con,$id) // delete_player.php
{
	$result = mysqli_query($con,"SELECT first_login FROM player WHERE ID = '$id'");
	while ($row=mysqli_fetch_array($result))
	{
		$f_login = $row["first_login"];
	}
	
	return $f_login;
}


/*
###########################################################
######################## TICKET #############################
###########################################################
*/

function getTicketStatus($con,$ip) // reg_name.php
{
	$result = mysqli_query($con,"SELECT ticket_active FROM player WHERE ip = '$ip'");
	while($row=mysqli_fetch_array($result))
	{
		$activeTicket = $row["ticket_active"];
	}
	
	return $activeTicket;
}
	// ###### TICKET-ADMIN ######
	
	function getUserTicketRelation($con) // admin/admin_func/displayTicketStatus
	{
		$result = mysqli_query($con,"SELECT name, ticket_active FROM player");
		while($row=mysqli_fetch_assoc($result))
		{
			$ticketStatus[] = $row;
		}

		return $ticketStatus;
	}


/*
###########################################################
######################## USER #############################
###########################################################
*/

function getUserId($con,$ip) //ehemals retrieveUserId, bezieht die User_ID - function.php/initializePlayer + getUserRelatedStatusColor + status.php
{
	$result = mysqli_query($con,"SELECT ID FROM player WHERE ip = '$ip'");
	$row = mysqli_fetch_array($result);
	$user_id = $row["ID"];

	return $user_id;
}

function getAllUsername($con) //bezieht alle Username / auth.php + reg_name.php + change_user.php
{
	$result = mysqli_query($con,"SELECT name FROM player");
	while ($row = mysqli_fetch_array($result))
	{
		$users[] = $row["name"];
	}

	return $users;
}
function getBasicUserData($con) // admin/admin_func.php/addUsername
{
	$result = mysqli_query($con, "SELECT ID,name FROM player");
	while($row = mysqli_fetch_assoc($result))
	{
		$basic_user[] = $row;
	}
	
	return $basic_user;
}

function getSingleUsername($con,$ip) //bezieht den Username, der zur angegebenen IP gehört - index.php + function.php/displayPlayerAchievements + reg_name.php + profil_image.php
{
	$result = mysqli_query($con,"SELECT name FROM player WHERE ip = '$ip'");
	while ($row = mysqli_fetch_array($result))
	{
		$username = $row["name"];
	}

	if(empty($username))
	{
		$username = "";
	}

	return $username;
}

function getUserStatus($con,$user_id) //bezieht den Userstatus eines bestimmten Spielers
{
	$result = mysqli_query($con,"SELECT status FROM status WHERE user_id = '$user_id'");
	$row = mysqli_fetch_array($result);
	$user_status = $row["status"];

	return $user_status;
}

function getLastIp($con) // bezieht die zuletzt angelegte IP / admin/create_new_player.php
{
	$result = mysqli_query($con,"SELECT ip FROM player ORDER BY ID DESC LIMIT 1");
	$row = mysqli_fetch_array($result);
	$last_ip = $row["ip"];

	return $last_ip;
}

function getUserImage($con,$ip) // function.php/displayProfilImage
{
	$result = mysqli_query($con,"SELECT profil_image FROM player WHERE ip = '$ip'");
	$row = mysqli_fetch_array($result);
	$profil_image = $row["profil_image"];

	return $profil_image;
}

function getAllUserIDs($con) //Admin-Area / admin/delete_player.php
{
	$result = mysqli_query($con,"SELECT ID FROM player");
	while($row=mysqli_fetch_array($result))
	{
		$user_ids[] = $row["ID"];
	}
	
	return $user_ids;
}

function getUsernameById($con,$id) // admin/assign_achievement.php
{
	$result = mysqli_query($con,"SELECT name FROM player WHERE ID = '$id'");
	while($row=mysqli_fetch_array($result))
	{
		$username = $row["name"];
	}

	return $username;
}

function getUserPref($con,$id)
{
	$result = mysqli_query($con,"SELECT preferences FROM pref WHERE user_id = '$id'");
	while($row=mysqli_fetch_array($result))
	{
		$user_pref = $row["preferences"];
	}

	return $user_pref;
}


/*
###########################################################
######################## PLAYER ###########################
###########################################################
*/


function getPlayerData($con) // bezieht die spielerrelavanten Daten aller Spieler / admin/player_settings_view.php
{
	$result = mysqli_query($con,"SELECT ID, name, ip, team_id, team_captain FROM player");
	while($row=mysqli_fetch_assoc($result))
	{
		$player[] = $row;
	}

	return $player;
}

function getPlayerID($con,$ip) // generate.php + reject_key.php
{
	$result = mysqli_query($con,"SELECT ID FROM player WHERE ip = '$ip'");
	while ($row=mysqli_fetch_array($result))
	{
		$player_id = $row["ID"];
	}

	return $player_id;
}
function getSinglePlayerTeam($con,$ip) // index.php
{
	$result = mysqli_query($con,"SELECT team_id FROM player WHERE ip='$ip'");
	$row = mysqli_fetch_array($result);
	$team_id = $row["team_id"];

	$team = getTeamName($con,$team_id);

	return $team;
}
function getSinglePlayerPref($con,$ip)
{
	$player_id = getUserId($con,$ip);

	$result = mysqli_query($con,"SELECT preferences FROM pref WHERE user_id = '$player_id'");
	while ($row=mysqli_fetch_array($result))
	{
		$prefString = $row["preferences"];
	}

	$player_pref = array();
	if(!empty($prefString))
	{
		$player_pref = explode(", ",$prefString);
	}


	return $player_pref;

}

/*
###########################################################
######################## GAMES ############################
###########################################################
*/

function getGameData($con)
{
	$result = mysqli_query($con,"SELECT ID, name, raw_name, icon, has_table FROM games");
	while($row=mysqli_fetch_assoc($result))
	{
		$gameData[] = $row;
	}

	return $gameData;
}
function getGameID($con,$game)
{
	$result = mysqli_query($con,"SELECT ID FROM games WHERE name = '$game'");
	$row = mysqli_fetch_array($result);
	$gameID = $row["ID"];

	return $gameID;
}

function getGameInfo($con) // function.php/generate_options
{
	$result = mysqli_query($con,"SELECT name, raw_name AS id FROM games ORDER BY name");
	while($row=mysqli_fetch_assoc($result))
	{
		$gameinfo[] = $row;
	}

	return $gameinfo;
}

function getGameInfoById($con,$game_id)
{
	$result = mysqli_query($con,"SELECT name, raw_name, icon FROM games WHERE ID = '$game_id'");
	while($row=mysqli_fetch_assoc($result))
	{
		$gameinfo[] = $row;
	}

	return $gameinfo;
}

function getRawName($con) //bezieht die Spaltennamen zu allen Spielen, die im System hinterlegt sind (raw_name = teil1_teil2) / function.php/verifyGame + admin/key_status.php
{
	$result = mysqli_query($con,"SELECT raw_name FROM games ORDER BY name");
	while($row=mysqli_fetch_array($result))
	{
		$raw_name[] = $row["raw_name"];
	}

	return $raw_name;
}

function getSingleRawName($con,$game) //bezieht einen bestimmten Spaltennamen, der im System hinterlegt ist (raw_name = teil1_teil2) / generate.php + reject_key.php
{
	$result = mysqli_query($con,"SELECT raw_name FROM games WHERE name = '$game'");
	$row = mysqli_fetch_array($result);
	$raw_name = $row["raw_name"];

	return $raw_name;
}

function getGames($con) //ehemals rebuild_games, bezieht den tatsächlichen Spielnamen (name ungleich raw_name) / function.php/verifyGames + admin/key_status.php
{
	$result = mysqli_query($con,"SELECT name FROM games ORDER BY name ASC");
	while($row=mysqli_fetch_array($result))
	{
		$games[] = $row["name"];
	}
	return $games;
}

function getGameIcon($con,$game_id)
{
	$result = mysqli_query($con,"SELECT game_icon FROM games WHERE ID = '$game_id'");
	if(!empty($result))
	{
		$row = mysqli_fetch_array($result);
		$game_icon = $row["game_icon"];	

		return $game_icon;
	}
}

function getHasTableByGameID($con,$game_id)
{
	$result = mysqli_query($con,"SELECT has_table FROM games WHERE game_id = '$game_id'");
	$row = mysqli_fetch_array($result);
	$has_table = $row["has_table"];
	
	return $has_table;
}

function getRawNameByID($con,$game_id)
{
	$result = mysqli_query($con,"SELECT raw_name FROM games WHERE game_id = '$game_id'");
	$row = mysqli_fetch_array($result);
	$o_rawname = $raw["raw_name"];
	
	return $o_rawname;
}


/*
###########################################################
######################## GAME-KEY #########################
###########################################################
*/

function getPlayerGameKey($con,$id,$raw_name) //bezieht den Gamekey, der für einen bestimmten Spieler hinterlegt wurde / function.php/generateGameKey
{
	$result = mysqli_query($con,"SELECT game_key FROM $raw_name WHERE player_id = '$id'");
	while ($row=mysqli_fetch_array($result))
	{
		$key = $row["game_key"];
	}

	if(empty($key))
	{
		$key = array();
	}

	return $key;
}

function getGameKey($con,$raw_name) //bezieht einen freien Gamekey / function.php/generateGameKey
{
	$result = mysqli_query($con,"SELECT game_key FROM $raw_name WHERE player_id IS NULL");
	$row = mysqli_fetch_array($result);
	$first_key = $row["game_key"];

	return $first_key;
}

function getOldGameKey($con,$id,$raw_name) // reject_key.php
{
	$result = mysqli_query($con,"SELECT game_key FROM $raw_name WHERE player_id = '$id'");
	$row = mysqli_fetch_array($result);
	$old_key = $row["game_key"];

	return $old_key;
}

function getNewGameKey($con,$raw_name) //bezieht einen neuen GameKey, wenn der alte nicht funktioniert hat / reject_key.php
{
	$result = mysqli_query($con,"SELECT game_key FROM $raw_name WHERE player_id IS NULL");
	$row=mysqli_fetch_array($result);
	$new_key = $row["game_key"];

	return $new_key;
}

function getAllKeys($con,$raw_name) // function.php/verifyKey
{
	$result = mysqli_query($con,"SELECT game_key FROM $raw_name");
	while($row=mysqli_fetch_array($result))
	{
		$all_keys[] = $row["game_key"];
	}

	return $all_keys;
}

/*
###########################################################
######################## TEAMS ############################
###########################################################
*/

function getAllTeams($con) // function.php/members
{
	$result = mysqli_query($con,"SELECT ID, name FROM tm_teamname");
	while($row=mysqli_fetch_assoc($result))
	{
		$teams[] = $row;
	}

	return $teams;
}

function getTeamNames($con) //bezieht alle Teamnamen / create_team.php
{
	$result = mysqli_query($con,"SELECT name FROM tm_teamname");
	while ($row=mysqli_fetch_array($result))
	{
		$team_names[] = $row["name"];
	}

	return $team_names;
}

function getTeamId($con,$ip) //spielerspezifische Team ID / function.php/ownTeam + teamMembers + join_team.php + leave_team.php
{
	$result = mysqli_query($con,"SELECT team_id FROM player WHERE ip = '$ip'");
	$row = mysqli_fetch_array($result);
	$team_id = $row["team_id"];

	return $team_id;
}

function getTeamIdByName($con,$tmn) // delete_team.php
{
	$result = mysqli_query($con,"SELECT ID FROM tm_teamname WHERE name = '$teamname'");
	$row = mysqli_fetch_array($result);
	$team_id = $row["ID"];

	return $team_id;
}

function getTeamName($con,$team_id) //teamspezifischer Name / function.php/ownTeam + displayTeams
{
	$result = mysqli_query($con,"SELECT name FROM tm_teamname WHERE ID = '$team_id'");
	$row = mysqli_fetch_array($result);
	$name = $row["name"];

	return $name;
}

function getJoinedTeamName($con,$single_team) //gibt das Team aus, dem man zufällig beigetreten ist / join_team.php
{
	$result = mysqli_query($con,"SELECT name FROM tm_teamname WHERE ID = '$single_team'");
	$row = mysqli_fetch_array($result);
	$team_name = $row["name"];

	return $team_name;
}

function getTeamMember($con,$team_id) //bezieht alle Mitglieder von allen Teams / function.php/members + admin/team_status.php
{
	$result = mysqli_query($con,"SELECT name FROM player WHERE team_id = '$team_id'");
	while ($row=mysqli_fetch_array($result))
	{
		$member[] = $row["name"];
	}

	if (empty($member))
	{
		$member = array();
	}

	return $member;
}

function getTeamMembers($con,$ip,$team_id) //bezieht die Teammitglieder eines Spielers /function.php/teamMembers
{
	$result = mysqli_query($con,"SELECT name FROM player WHERE team_id = '$team_id' AND ip != '$ip'");
	while ($row=mysqli_fetch_array($result))
	{
		$team_members[] = $row["name"];
	}

	if (empty($team_members))
	{
		$team_members = array();
	}

	return $team_members;
}

function getTeamCaptain($con,$team_id) //bezieht den Team Captain eines spezifischen Teams
{
	$result = mysqli_query($con,"SELECT name FROM player WHERE team_id = '$team_id' AND team_captain != NULL");
	while ($row=mysqli_fetch_array($result))
	{
		$captain = $row["name"];
	}

	if (empty($captain))
	{
		$captain = "";
	}

	return $captain;
}
function getCaptainStatus($con,$ip)
{
	$result = mysqli_query($con,"SELECT team_captain FROM player WHERE ip = '$ip'");
	$row = mysqli_fetch_array($result);
	$team_captain = $row["team_captain"];

	return $team_captain;
}

/*
###########################################################
######################## STATUS ############################
###########################################################
*/

function getStatus($con,$id) // function.php/getUserRelatedStatusColor + status.php
{
	$result = mysqli_query($con,"SELECT status AS id FROM status WHERE user_id = '$id'");
	$row = mysqli_fetch_array($result);
	$status = $row["id"];

	return $status;
}
function getStatusColor($con,$status) // function.php/getUserRelatedStatusColor
{
	$result = mysqli_query($con,"SELECT status_color FROM status_color WHERE status_value = '$status'");
	$row = mysqli_fetch_array($result);
	$s_color = $row["status_color"];

	return $s_color;
}
function getStatusData($con)
{
	$result = mysqli_query($con,"SELECT status_id AS id,status_name AS name FROM status_name");
	while($row=mysqli_fetch_assoc($result))
	{
		$status_data[] = $row;
	}

	return $status_data;
}
function getStatusName($con,$status_id)
{
	$result = mysqli_query($con,"SELECT status_name AS name FROM status_name WHERE status_id = '$status_id'");
	while($row=mysqli_fetch_array($result))
	{
		$status_name = $row["name"];
	}

	return $status_name;
}


/*
###########################################################
######################## ACHIEVEMENTS #####################
###########################################################
*/

function getAllAchievements($con) //used in Admin-Area / admin/admin_func.php/displayAchievements
{
	$sql = "SELECT ac.ID, ac.title, ac.image_url, ac.message, ac_category.ID AS catID, ac_category.c_name, ac_trigger.ID AS trigID, ac_trigger.trigger_title, ac.ac_visibility FROM ac LEFT JOIN ac_category ON ac.ac_categorie = ac_category.ID LEFT JOIN ac_trigger ON ac.ac_trigger = ac_trigger.ID";

	$result = mysqli_query($con,$sql);
	while ($row=mysqli_fetch_assoc($result))
	{
		$all_achievements[] = $row;
	}

	return $all_achievements;
}
function getAchievementById($con,$ac_id) // function.php/displayUserAchievements
{
	$result = mysqli_query($con,"SELECT * FROM ac WHERE ID = '$ac_id'");
	while($row=mysqli_fetch_array($result))
	{
		$achievement[] = $row;
	}

	return $achievement;
}

function getUserAchievements($con,$user_id) // function.php/displayUserAchievements + admin/assign_achievement.php
{
	$result = mysqli_query($con,"SELECT ac_id FROM ac_player WHERE `$user_id` = '1'");
	while($row=mysqli_fetch_assoc($result))
	{
		$ac_id[] = $row["ac_id"];
	}

	if (empty($ac_id))
	{
		$ac_id = array();
	}

	return $ac_id;
}

function getAvailableAchievements($con,$ip) // function.php/displayAvailableAchievements
{
	$username = getSingleUsername($con,$ip);

	$sql = "SELECT ac.ID,ac.title FROM ac LEFT JOIN ac_player ON ac.ID = ac_player.ac_id WHERE ac.ac_visibility = '1' AND ac_player.1 IS NULL";
	$result = mysqli_query($con,$sql);
	while($row=mysqli_fetch_assoc($result))
	{
		$basic_ac[] = $row;
	}

	return $basic_ac;
}

function getAllAchievementByName($con) // admin/admin_func.php/addUsername
{
	$result = mysqli_query($con,"SELECT ID,title FROM ac");
	while($row=mysqli_fetch_assoc($result))
	{
		$ac_option[] = $row;
	}
	
	return $ac_option;
}

function getAchievementCategories($con) // admin/admin_func.php/displayCategories
{
	$result = mysqli_query($con,"SELECT ID, c_name AS name FROM ac_category");
	while($row=mysqli_fetch_assoc($result))
	{ 
		$ac_categories[] = $row;
	}

	return $ac_categories;
}

function getAchievementTrigger($con) // admin/admin_func.php/displayTrigger
{
	$result = mysqli_query($con,"SELECT ID,trigger_title AS name FROM ac_trigger");
	while($row=mysqli_fetch_assoc($result))
	{
		$ac_trigger[] = $row;
	}

	return $ac_trigger;
}

function getAchievementVisibility($con)
{
	$result = mysqli_query($con,"SELECT ac_visibility FROM ac");
	while($row=mysqli_fetch_array($result))
	{
		$ac_visibility[] = $row["ac_visibility"];
	}

	return $ac_visibility;
}

function getParamByAcID($con,$ac_id)
{
	$result = mysqli_query($con, "SELECT ac_trigger, ac_categorie, ac_visibility FROM ac WHERE ID = '$ac_id'");
	while($row=mysqli_fetch_assoc($result))
	{
		$acParam[] = $row;
	}

	return $acParam;
}


/*
###########################################################
######################## WOW-Server #######################
###########################################################
*/

function getWowAccount($con,$ip)
{
	$result = mysqli_query($con,"SELECT wow_account FROM player WHERE ip = '$ip'");
	while($row=mysqli_fetch_array($result))
	{
		$account_name = $row["wow_account"];
	}

	return $account_name;
}

function getWowId($con_wow,$wow_account)
{
	$result = mysqli_query($con_wow,"SELECT id FROM account WHERE username = '$wow_account'");
	while($row=mysqli_fetch_array($result))
	{
		$account_id = $row["id"];
	}

	return $account_id;
}

function getChars($con_char,$wow_id)
{
	$result = mysqli_query($con_char,"SELECT name, race, class, level, map FROM characters.characters WHERE account = '$wow_id'");
	while($row=mysqli_fetch_array($result))
	{
		$chars[] = $row;
	}

	return $chars;
}

function getRealmName($con)
{
	$result = mysqli_query($con,"SELECT name FROM realmlist");
	while($row=mysqli_fetch_array($result))
	{
		$n_realm = $row["name"];
	}

	return $n_realm;
}

function getServerStatus($con)
{
	$result = mysqli_query($con,"SELECT flag FROM realmlist");
	while($row=mysqli_fetch_array($result))
	{
		$s_status = $row["flag"];
	}

	return $s_status;
}

//###################### Tournaments ################################

function getTournamentGames($con)
{
	$result = mysqli_query($con,"SELECT game FROM tm");
	while($row=mysqli_fetch_assoc($result))
	{
		$tm_game[] = $row["game"];
	}

	if(empty($tm_game))
	{
		$tm_game = array();
	}

	return $tm_game;
}

function getTournamentModes($con,$game)
{
	$result = mysqli_query($con,"SELECT mode FROM tm WHERE game = '$game'");
	while($row=mysqli_fetch_array($result))
	{
		$tm_mode = $row["mode"];
	}

	if(empty($tm_mode))
	{
		$tm_mode = array();
	}

	return $tm_mode;
}

function getTournaments($con)
{
	$result = mysqli_query($con,"SELECT ID, DATE_FORMAT(`starttime`, '%d.%m.%Y %H:%i') AS starttime, game, mode, player_count FROM tm");
	while($row=mysqli_fetch_assoc($result))
	{
		$tms[] = $row;
	}

	return $tms;
}

function getTmById($con,$tm_id)
{
	$result = mysqli_query($con,"SELECT ID FROM tm WHERE ID = '$tm_id'");
	while($row=mysqli_fetch_array($result))
	{
		$existing_tm = $row["ID"];
	}

	return $existing_tm;
}

function getTmStartById($con,$tm_id)
{
	$result = mysqli_query($con,"SELECT starttime FROM tm WHERE ID = '$tm_id'");
	while($row=mysqli_fetch_array($result))
	{
		$tm_starttime = $row["starttime"];
	}

	return $tm_starttime;
}

?>