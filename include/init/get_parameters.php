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

function getAllUsername($con) //bezieht alle Username / change_user.php
{
	$result = mysqli_query($con,"SELECT name FROM player");
	while ($row = mysqli_fetch_array($result))
	{
		$users[] = $row["name"];
	}

	return $users;
}

/**
 * 
 * @param mysqli $con
 * @param string $name
 * @return boolean true when name already exists
 */
function checkPlayernameExists(mysqli $con, string $name)
{
	return mysqli_num_rows(mysqli_query($con, "SELECT ID FROM player WHERE name = '$name';")) > 0;
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

function getSingleUsername($con, $player_id) //bezieht den Username, der zur angegebenen ID gehört - index.php + function.php/displayPlayerAchievements + reg_name.php + profil_image.php
{
	$result = mysqli_query($con,"SELECT name FROM player WHERE ID = '$player_id'");
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

function getUserImage($con, $player_id) // function.php/displayProfilImage
{
	$result = mysqli_query($con,"SELECT profil_image FROM player WHERE ID = '$player_id'");
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
function getSinglePlayerTeam($con, $player_id) // index.php
{
	$result = mysqli_query($con,"SELECT team_id FROM player WHERE ID='$player_id'");
	$row = mysqli_fetch_array($result);
	$team_id = $row["team_id"];

	$team = getTeamName($con,$team_id);

	return $team;
}
function getSinglePlayerPref($con, $player_id)
{
	$result = mysqli_query($con,"SELECT game_id FROM pref WHERE player_id = '$player_id'");
	while ($row=mysqli_fetch_array($result))
	{
		$player_pref[] = $row["game_id"];
	}

	if(empty($player_pref))
	{
		$player_pref = array();
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
	$result = mysqli_query($con,"SELECT ID, name, raw_name, icon, banner, has_table FROM games");
	while($row=mysqli_fetch_assoc($result))
	{
		$gameData[] = $row;
	}

	return $gameData;
}
function getFullGameData($con)
{
	$result = mysqli_query($con,"SELECT ID, name, raw_name, icon, has_table FROM games WHERE addon IS NULL");
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
	$result = mysqli_query($con,"SELECT name, raw_name, short_title, icon, banner FROM games WHERE ID = '$game_id'");
	while($row=mysqli_fetch_assoc($result))
	{
		$gameinfo = $row;
	}

	return $gameinfo;
}

function getblankRawName($con) //admin/key_status.php
{
	$result = mysqli_query($con,"SELECT raw_name FROM games WHERE has_table = '1'");
	while($row=mysqli_fetch_array($result))
	{
		$raw_name[] = $row["raw_name"];
	}

	return $raw_name;
}

function getRawName($con) //bezieht die Spaltennamen zu allen Spielen, die im System hinterlegt sind (raw_name = teil1_teil2) / function.php/verifyGame
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
	
	} else {
		$game_icon = array();
	}

	return $game_icon;
}

function getGameBanner($con,$game_id)
{
	$result = mysqli_query($con,"SELECT banner FROM games WHERE ID = '$game_id'");
	if(!empty($result))
	{
		$row = mysqli_fetch_array($result);
		$game_banner = $row["banner"];
	} else {
		$game_banner = array();
	}

	return $game_banner;
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
	$o_rawname = $row["raw_name"];
	
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

function getTeamId($con, $player_id) //spielerspezifische Team ID / function.php/ownTeam + teamMembers + join_team.php + leave_team.php
{
	$result = mysqli_query($con,"SELECT team_id FROM player WHERE ID = '$player_id'");
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

function getTeamMembers($con, $player_id, $team_id) //bezieht die Teammitglieder eines Spielers /function.php/teamMembers
{
	$result = mysqli_query($con,"SELECT name FROM player WHERE team_id = '$team_id' AND ID != '$player_id'");
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

function getTeamCaptain($con, $team_id) //bezieht den Team Captain eines spezifischen Teams
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
function getCaptainStatus($con, $player_id)
{
	$result = mysqli_query($con,"SELECT team_captain FROM player WHERE ID = '$player_id'");
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
	$sql = "SELECT ac.ID, ac.title, ac.image_url, ac.message, ac_category.ID AS catID, ac_category.c_name, ac_trigger.ID AS trigID, ac_trigger.trigger_title, ac.ac_visibility FROM ac LEFT JOIN ac_category ON ac.ac_category = ac_category.ID LEFT JOIN ac_trigger ON ac.ac_trigger = ac_trigger.ID";

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

function getUserAchievements($con,$player_id) // function.php/displayUserAchievements + admin/assign_achievement.php
{
	$result = mysqli_query($con,"SELECT ac_id FROM ac_player WHERE player_id = '$player_id'");
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

function getAvailableAchievements($con, $player_id) // function.php/displayAvailableAchievements
{
	$sql = "SELECT ac.ID, ac.title FROM ac WHERE ac.ac_visibility = 'Sichtbar' AND NOT EXISTS (SELECT null FROM ac_player WHERE ac_player.player_id = '$player_id' AND ac_player.ac_id = ac.ID)";
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
	$result = mysqli_query($con, "SELECT ac.ac_category, ac.ac_visibility, ac.ac_trigger FROM ac WHERE ac.ID = '$ac_id'");
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

function getWowAccount($con,$player_id)
{
	$result = mysqli_query($con,"SELECT wow_account FROM player WHERE ID = '$player_id'");
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


/*
###########################################################
######################## Tournament-Votes #################
###########################################################
*/

function getPlayerIdsFromVote($con,$vote_id)
{
	$result = mysqli_query($con,"SELECT player_id FROM tm_vote_player WHERE tm_vote_id = '$vote_id'");
	while($row=mysqli_fetch_array($result))
	{
		$votedPlayerIds[] = $row["player_id"];
	}

	return $votedPlayerIds;
}

function getVotedGames($con,$game_id)
{
	$result = mysqli_query($con,"SELECT ID, vote_count FROM tm_vote WHERE game_id = '$game_id'");
	while($row=mysqli_fetch_assoc($result))
	{
		$votedGames = $row;
	}

	if(empty($votedGames))
	{
		$votedGames = array();
	}

	return $votedGames;
}

function getVotedTournaments($con)
{
	$result = mysqli_query($con,"SELECT ID, game_id, vote_count, starttime, endtime, vote_closed FROM tm_vote");
	while($row=mysqli_fetch_assoc($result))
	{
		$votedTournaments[] = $row;
	}

	if(empty($votedTournaments))
	{
		$votedTournaments = array();
	}

	return $votedTournaments;
}

function getVoteIds($con)
{
	$result = mysqli_query($con,"SELECT ID FROM tm_vote");
	while($row=mysqli_fetch_array($result))
	{
		$votes[] = $row["ID"];
	}

	if(empty($votes))
	{
		$votes = array();
	}

	return $votes;
}

function getTournamentVoteId($con,$game_id)
{
	$result = mysqli_query($con,"SELECT ID FROM tm_vote WHERE game_id = '$game_id'");
	while($row=mysqli_fetch_array($result))
	{
		$voteID = $row["ID"];
	}
	
	return $voteID;
}

function getVotedGamesByPlayerId($con,$player_id)
{
	$result = mysqli_query($con,"SELECT tm_vote_id FROM tm_vote_player WHERE player_id = '$player_id'");
	while($row=mysqli_fetch_array($result))
	{
		$votedGames[] = $row["tm_vote_id"];
	}

	if(empty($votedGames))
	{
		$votedGames = array();
	}

	return $votedGames;
}

function getPlayerVotes($con,$player_id,$vote_id)
{
	return mysqli_num_rows(mysqli_query($con, "SELECT * FROM tm_vote_player WHERE tm_vote_id = '$vote_id' AND player_id ='$player_id';")) > 0;
}

function getVotedPlayers($con,$vote_id)
{
	return mysqli_num_rows(mysqli_query($con,"SELECT player_id FROM tm_vote_player WHERE tm_vote_id = '$vote_id'"));
}

function getVoteById($con,$vote_id)
{
	$result = mysqli_query($con,"SELECT game_id, vote_count, starttime, endtime FROM tm_vote WHERE ID = '$vote_id'");
	while($row=mysqli_fetch_assoc($result))
	{
		$tm_vote = $row;
	}

	if(empty($tm_vote))
	{
		$tm_vote = array();
	}

	return $tm_vote;
}

/*
###########################################################
######################## Tournaments ######################
###########################################################
*/

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
	$result = mysqli_query($con,"SELECT ID, game_id, mode, mode_details, player_count, tm_period_id FROM tm");
	while($row=mysqli_fetch_assoc($result))
	{
		$tms[] = $row;
	}

	return $tms;
}

function getLastTmId($con)
{
	$result = mysqli_query($con,"SELECT ID FROM tm ORDER BY ID DESC LIMIT 1");
	while($row=mysqli_fetch_array($result))
	{
		$last_tm_id = $row["ID"];
	}

	return $last_tm_id;
}

function getGamersListIds($con)
{
	$result = mysqli_query($con,"SELECT ID FROM tm_gamerslist");
	while($row=mysqli_fetch_assoc($result))
	{
		$gamerslist_id = $row["ID"];
	}

	return $gamerslist_id;
}

function getTeamsByTmId($con,$tm_id)
{
	$result = mysqli_query($con,"SELECT ID FROM tm_team WHERE tournament_id = '$tm_id'");
	while($row=mysqli_fetch_assoc($result))
	{
		$team_ids = $row["ID"];
	}

	return $team_ids;
}

function getLastMatchId($con)
{
	$result = mysqli_query($con,"SELECT ID FROM tm_match ORDER BY ID DESC LIMIT 1");
	while($row=mysqli_fetch_array($result))
	{
		$match_id = $row["ID"];
	}

	return $match_id;
}

function getMatchesIdByMatchId($con,$match_id)
{
	$result = mysqli_query($con,"SELECT ID FROM tm_matches WHERE match_id = '$match_id'");
	while($row=mysqli_fetch_array($result))
	{
		$matches_id = $row["ID"];
	}

	return $matches_id;
}

function getPlayerIdFromGamerslist($con,$tm_id)
{
	$result = mysqli_query($con,"SELECT player_id FROM tm_gamerslist WHERE tm_id = '$tm_id'");
	while($row=mysqli_fetch_array($result))
	{
		$player_ids[] = $row["player_id"];
	}

	if(empty($player_ids))
	{
		$player_ids = array();
	}

	return $player_ids;
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

function getTournamentPeriodId($con)
{
	$result = mysqli_query($con,"SELECT ID FROM tm_period ORDER BY ID DESC LIMIT 1");
	while($row=mysqli_fetch_array($result))
	{
		$tm_period_id = $row["ID"];
	}

	return $tm_period_id;
}

function getTournamentPeriod($con,$period_id)
{
	$result = mysqli_query($con,"SELECT DATE_FORMAT(`time_from`, '%d.%m.%Y %H:%i') AS time_from, DATE_FORMAT(`time_to`, '%d.%m.%Y %H:%i') AS time_to FROM tm_period WHERE ID = '$period_id'");
	while($row=mysqli_fetch_assoc($result))
	{
		$tm_period = $row;
	}

	if(empty($tm_period))
	{
		$tm_period = array();
	}

	return $tm_period;
}
?>