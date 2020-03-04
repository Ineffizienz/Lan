<?php


/*
###########################################################
######################## AUTH #############################
###########################################################
*/

function getFirstLoginById($con,$id)
{
	/* Used in:
		:User
		- index.php

		:Admin
		- delete_player.php
	*/

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

	// ###### TICKET-ADMIN ######
	
	function getUserTicketRelation($con)
	{
		/* Used in:
			:Admin
			- admin_func.php/displayTicketStatus
		*/

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

function getAllUsername($con)
{
	/* Used in:
		:User
		- change_user.php
	*/

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
 * @return boolean false if the name does not exist or the player id if it does
 */
function checkPlayernameExists(mysqli $con, string $name)
{
	$res = mysqli_query($con, "SELECT ID FROM player WHERE name = '$name';");
	if(mysqli_num_rows($res) == 0)
		return false;
	return mysqli_fetch_array($res)['ID'];
}

function getBasicUserData($con)
{
	/* Used in:
		:Admin
		- admin_func.php/addUsername
	*/
	$result = mysqli_query($con, "SELECT ID,name FROM player");
	while($row = mysqli_fetch_assoc($result))
	{
		$basic_user[] = $row;
	}
	
	return $basic_user;
}

function getSingleUsername($con, $player_id): array
{
	/* Used in:
		:User
		- index.php
		- profil_image.php
		- index.php
	*/

	$result = mysqli_query($con,"SELECT name, real_name FROM player WHERE ID = '$player_id'");
	return mysqli_fetch_assoc($result);
}

function getUserStatus($con,$user_id) //bezieht den Userstatus eines bestimmten Spielers
{
	$result = mysqli_query($con,"SELECT status FROM status WHERE user_id = '$user_id'");
	$row = mysqli_fetch_array($result);
	$user_status = $row["status"];

	return $user_status;
}

function getLastIp($con)
{
	/* Used in:
		:Admin
		- create_new_player.php
	*/

	$result = mysqli_query($con,"SELECT ip FROM player ORDER BY ID DESC LIMIT 1");
	$row = mysqli_fetch_array($result);
	$last_ip = $row["ip"];

	return $last_ip;
}

function getUserImage(mysqli $con, $player_id)
{
	/* Used in:
		:User
		- function.php/displayProfilImage
	*/

	$result = mysqli_query($con,"SELECT profil_image FROM player WHERE ID = '$player_id'");
	$row = mysqli_fetch_array($result);
	$profil_image = $row["profil_image"];

	return $profil_image;
}

function getAllUserIDs($con)
{
	/* Used in:
		:Admin
		- delete_player.php
	*/

	$result = mysqli_query($con,"SELECT ID FROM player");
	while($row=mysqli_fetch_array($result))
	{
		$user_ids[] = $row["ID"];
	}
	
	return $user_ids;
}

/*
###########################################################
######################## PLAYER ###########################
###########################################################
*/


function getPlayerData($con)
{
	/* Used in:
		:Admin
		- player_settings_view.php
	*/

	$result = mysqli_query($con,"SELECT ID, name, ip, wow_account, team_id, team_captain FROM player");
	while($row=mysqli_fetch_assoc($result))
	{
		$player[] = $row;
	}

	return $player;
}

function getSinglePlayerTeam($con, $player_id)
{
	/* Used in:
		:User
		- function.php/displaySinglePlayerTeam
	*/

	$result = mysqli_query($con,"SELECT team_id FROM player WHERE ID='$player_id'");
	$row = mysqli_fetch_array($result);
	$team_id = $row["team_id"];

	$team = getTeamName($con,$team_id);

	return $team;
}
function getSinglePlayerPref($con, $player_id)
{
	/* Used in:
		:User
		- function.php/displayPlayerPrefs
		- function.php/createCheckbox
	*/
	$result = mysqli_query($con,"SELECT games.icon, games.short_title FROM pref LEFT JOIN games ON pref.game_id = games.ID WHERE pref.player_id = '$player_id'");
	while ($row=mysqli_fetch_assoc($result))
	{
		$player_pref[] = $row;
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

function getGameData($con) // function.php/createCheckbox, admin_func.php/displaySingleGame
{
	/* Used in:
		:User
		- function.php/createCheckbox

		:Admin
		- admin_func.php/displaySingleGame
	*/

	$result = mysqli_query($con,"SELECT ID, name, raw_name, icon, banner, has_table FROM games");
	while($row=mysqli_fetch_assoc($result))
	{
		$gameData[] = $row;
	}

	return $gameData;
}
function getFullGameData($con)
{
	/* Used in:
		:Admin
		- admin_func.php/displayTmGames
	*/

	$result = mysqli_query($con,"SELECT ID, name, raw_name, icon, has_table FROM games WHERE addon IS NULL");
	while($row=mysqli_fetch_assoc($result))
	{
		$gameData[] = $row;
	}
	
	return $gameData;
}

function getMainGameData($con)
{
	/*Used in:
		:User
		- function.php/generateVoteOption
	*/

	$result = mysqli_query($con,"SELECT ID, name FROM games WHERE addon IS NULL");
	while($row=mysqli_fetch_assoc($result))
	{
		$gameData[] = $row;
	}

	return $gameData;
}

function getGameInfo($con)
{
	/* Used in:
		:User
		- function.php/generate_options
	*/

	$result = mysqli_query($con,"SELECT name, ID AS id FROM games ORDER BY name");
	while($row=mysqli_fetch_assoc($result))
	{
		$gameinfo[] = $row;
	}

	return $gameinfo;
}

function getGameInfoById($con,$game_id)
{
	/* Used in:
		:User
		- function.php/displayRunningVotes
		- function.php/displayTournaments

		:Admin
		- admin_func.php/displayVotedTournaments
		- admin_func.php/displayTournaments
	*/


	$result = mysqli_query($con,"SELECT name, raw_name, short_title, icon, banner FROM games WHERE ID = '$game_id'");
	while($row=mysqli_fetch_assoc($result))
	{
		$gameinfo = $row;
	}

	return $gameinfo;
}

function getRawName($con)
{
	/* Used in:
		:Admin
		- admin_func.php/verifyGame
	*/

	$result = mysqli_query($con,"SELECT raw_name FROM games ORDER BY name");
	while($row=mysqli_fetch_array($result))
	{
		$raw_name[] = $row["raw_name"];
	}

	return $raw_name;
}

function getGames($con)
{
	/* Used in:
		:Admin
		- admin_func.php/verifyGame
	*/

	$result = mysqli_query($con,"SELECT name FROM games ORDER BY name ASC");
	while($row=mysqli_fetch_array($result))
	{
		$games[] = $row["name"];
	}
	return $games;
}

function getGameIcon($con,$game_id)
{
	/* Used in:
		:Admin
		- update_icon.php
	*/

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
	/* Used in:
		:User
		- function.php/displayTournamentLocked

		:Admin
		- update_banner.php
	*/

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
	/* Used in:
		:Admin
		- change_rawname.php
	*/
	$result = mysqli_query($con,"SELECT has_table FROM games WHERE game_id = '$game_id'");
	$row = mysqli_fetch_array($result);
	$has_table = $row["has_table"];
	
	return $has_table;
}

function getRawNameByID($con,$game_id)
{
	/* Used in:
		:Admin
		- change_rawname.php
	*/
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

function getPlayerGameKey($con, int $player_id, int $game_id) //bezieht den Gamekey, der für einen bestimmten Spieler hinterlegt wurde / function.php/generateGameKey
{
	/* Used in:
		:User
		- function.php/generateGameKey
	*/

	$result = mysqli_query($con, "SELECT gamekey FROM gamekeys WHERE (game_id = '$game_id') AND (player_id = '$player_id') AND (rejected = '0') LIMIT 1;");
	if(mysqli_num_rows($result) > 0)
		return mysqli_fetch_array($result)["gamekey"];
	else
		return false;
}

function getGameKey($con, int $game_id)
{
	/* Used in:
		:User
		- function.php/generateGameKey
	*/

	$result = mysqli_query($con,"SELECT gamekey FROM gamekeys WHERE (game_id = '$game_id') AND (player_id IS NULL) AND (rejected = '0') LIMIT 1;");
	if(mysqli_num_rows($result) == 0)
		return false;
	else
		return mysqli_fetch_array($result)["gamekey"];
}

function getAllPlayerKeys($con,$player_id)
{
	/* Used in:
		:Admin
		- delete_player.php
	*/

	return mysqli_num_rows(mysqli_query($con,"SELECT gamekey FROM gamekeys WHERE player_id = '$player_id'")) > 0;
}

/*
###########################################################
######################## TEAMS ############################
###########################################################
*/

function getAllTeams($con)
{
	/* Used in:
		:User
		- function.php/members
		- join_team.php

		:Admin
		- admin_func.php/displayTeams
		- team_status.php
	*/

	$result = mysqli_query($con,"SELECT ID, name FROM tm_teamname");
	while($row=mysqli_fetch_assoc($result))
	{
		$teams[] = $row;
	}

	return $teams;
}

function getAllTeamsWithMember($con)
{
	/* Used in:
		:User
		- function.php/members
	*/

	$result = mysqli_query($con,"SELECT tm_teamname.ID, tm_teamname.name, player.name FROM tm_teamname LEFT JOIN player ON tm_teamname.ID = player.team_id");
	while($row=mysqli_fetch_assoc($result))
	{
		$teams[] = $row;
	}

	return $teams;
}

function getTeamNames($con)
{
	/* Used in:
		:User
		- create_team.php
	*/

	$result = mysqli_query($con,"SELECT name FROM tm_teamname");
	while ($row=mysqli_fetch_array($result))
	{
		$team_names[] = $row["name"];
	}

	return $team_names;
}

function getTeamId($con, $player_id)
{
	/* Used in:
		:User
		- function.php/ownTeam
		- function/teamMembers
		- function/displaySinglePlayerTeam
		- function/displayCaptain
		- function/displayPlayerTeamMember
		- join_team.php
	*/

	$result = mysqli_query($con,"SELECT team_id FROM player WHERE ID = '$player_id'");
	$row = mysqli_fetch_array($result);
	$team_id = $row["team_id"];

	return $team_id;
}

function getTeamIdByName($con,$tmn)
{
	/* Used in:
		:User
		- delete_team.php
	*/

	$result = mysqli_query($con,"SELECT ID FROM tm_teamname WHERE name = '$teamname'");
	$row = mysqli_fetch_array($result);
	$team_id = $row["ID"];

	return $team_id;
}

function getTeamName($con,$team_id)
{
	/* Used in:
		:User
		- function.php/ownTeam

		:Admin
		- player_settings_view.php
	*/

	$result = mysqli_query($con,"SELECT name FROM tm_teamname WHERE ID = '$team_id'");
	$row = mysqli_fetch_array($result);
	$name = $row["name"];

	return $name;
}

function getJoinedTeamName($con,$single_team)
{
	/* Used in:
		:User
		- join_team.php
	*/

	$result = mysqli_query($con,"SELECT name FROM tm_teamname WHERE ID = '$single_team'");
	$row = mysqli_fetch_array($result);
	$team_name = $row["name"];

	return $team_name;
}

function getTeamMember($con,$team_id)
{
	/* Used in:
		:User
		- function.php/members

		:Admin
		- team_status.php
	*/

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

function getTeamMembers($con, $player_id, $team_id)
{
	/* Used in:
		:User
		- function.php/members
		- function.php/teamMembers
		- function.php/displayPlayerTeamMembers
	*/

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

function getTeamCaptain($con, $team_id)
{
	/* Used in:
		:User
		- function.php/displayCaptain
	*/

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
	/* Used in:
		:User
		- function.php/displayCaptain
	*/

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

function getStatus($con,$id)
{
	/* Used in:
		:User
		- function.php/getUserRelatedStatusColor
		- function.php/getUserStatusOption
	*/

	$result = mysqli_query($con,"SELECT status AS id FROM status WHERE user_id = '$id'");
	$row = mysqli_fetch_array($result);
	$status = $row["id"];

	return $status;
}
function getStatusColor($con,$status)
{
	/* Used in:
		:User
		- function.php/getUserRelatedStatusColor
		- status.php
	*/

	$result = mysqli_query($con,"SELECT status_color FROM status_color WHERE status_value = '$status'");
	$row = mysqli_fetch_array($result);
	$s_color = $row["status_color"];

	return $s_color;
}

function getStatusData($con)
{
	/* Used in:
		:User
		- function.php/getUserStatusOption
	*/

	$result = mysqli_query($con,"SELECT status_id AS id,status_name AS name FROM status_name");
	while($row=mysqli_fetch_assoc($result))
	{
		$status_data[] = $row;
	}

	return $status_data;
}

function getStatusName($con,$status_id)
{
	/* Used in:
		:User
		- function.php/UserStatusName
	*/

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

function getAllAchievements($con)
{
	/* Used in:
		:Admin
		- admin_func.php/displayAchievements
	*/

	$sql = "SELECT ac.ID, ac.title, ac.image_url, ac.message, ac_category.ID AS catID, ac_category.c_name, ac_trigger.ID AS trigID, ac_trigger.trigger_title, ac.ac_visibility FROM ac LEFT JOIN ac_category ON ac.ac_category = ac_category.ID LEFT JOIN ac_trigger ON ac.ac_trigger = ac_trigger.ID";

	$result = mysqli_query($con,$sql);
	while ($row=mysqli_fetch_assoc($result))
	{
		$all_achievements[] = $row;
	}

	return $all_achievements;
}
function getAchievementById($con,$ac_id)
{
	/* Used in:
		:User
		- function.php/displayPlayerAchievements
	*/

	$result = mysqli_query($con,"SELECT * FROM ac WHERE ID = '$ac_id'");
	while($row=mysqli_fetch_array($result))
	{
		$achievement[] = $row;
	}

	return $achievement;
}

function getUserAchievements($con,$player_id)
{
	/* Used in:
		:User
		- function.php/displayPlayerAchievements

		:Admin
		- assign_achievement.php
	*/

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

function getAvailableAchievements($con, $player_id)
{
	/* Used in:
		:User
		- function.php/displayAvailableAchievements
	*/

	$sql = "SELECT ac.ID, ac.title FROM ac WHERE ac.ac_visibility = 'Sichtbar' AND NOT EXISTS (SELECT null FROM ac_player WHERE ac_player.player_id = '$player_id' AND ac_player.ac_id = ac.ID)";
	$result = mysqli_query($con,$sql);
	while($row=mysqli_fetch_assoc($result))
	{
		$basic_ac[] = $row;
	}

	return $basic_ac;
}

function getAllAchievementByName($con)
{
	/* Used in:
		:Admin
		- admin_func.php/addUsername
	*/

	$result = mysqli_query($con,"SELECT ID,title FROM ac");
	while($row=mysqli_fetch_assoc($result))
	{
		$ac_option[] = $row;
	}
	
	return $ac_option;
}

function getAchievementCategories($con)
{
	/* Used in:
		:Admin
		- admin_func.php/displayAchievements
		- admin_func.php/displayCategories
	*/

	$result = mysqli_query($con,"SELECT ID, c_name AS name FROM ac_category");
	while($row=mysqli_fetch_assoc($result))
	{ 
		$ac_categories[] = $row;
	}

	return $ac_categories;
}

function getAchievementTrigger($con) // admin/admin_func.php/displayTrigger
{
	/* Used in:
		:Admin
		- admin_func.php/displayAchievements
		- admin_func.php/displayAcTrigger
	*/

	$result = mysqli_query($con,"SELECT ID,trigger_title AS name FROM ac_trigger");
	while($row=mysqli_fetch_assoc($result))
	{
		$ac_trigger[] = $row;
	}

	return $ac_trigger;
}

function getParamByAcID($con,$ac_id)
{
	/* Used in:
		:Admin
		- change_param.php
	*/

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
	/* Used in:
		:User
		- function.php/selectWowAccount
	*/

	$result = mysqli_query($con,"SELECT wow_account FROM player WHERE ID = '$player_id'");
	while($row=mysqli_fetch_array($result))
	{
		$account_name = $row["wow_account"];
	}

	return $account_name;
}

function getWowId($con_wow,$wow_account)
{
	/* Used in:
		:User
		- function.php/selectWowAccount
	*/

	$result = mysqli_query($con_wow,"SELECT id FROM account WHERE username = '$wow_account'");
	while($row=mysqli_fetch_array($result))
	{
		$account_id = $row["id"];
	}

	return $account_id;
}

function getChars($con_char,$wow_id)
{
	/* Used in:
		:User
		- function.php/selectWowAccount
	*/

	$result = mysqli_query($con_char,"SELECT name, race, class, level, map FROM characters.characters WHERE account = '$wow_id'");
	while($row=mysqli_fetch_array($result))
	{
		$chars[] = $row;
	}

	return $chars;
}

function getRealmName($con)
{
	/* Used in:
		:User
		- index.php
	*/

	$result = mysqli_query($con,"SELECT name FROM realmlist");
	while($row=mysqli_fetch_array($result))
	{
		$n_realm = $row["name"];
	}

	return $n_realm;
}

function getServerStatus($con)
{
	/* Used in:
		:User
		- function.php/displayServerStatus
	*/

	$result = mysqli_query($con,"SELECT flag FROM realmlist");
	while($row=mysqli_fetch_array($result))
	{
		$s_status = $row["flag"];
	}

	return $s_status;
}


/*
###########################################################
##################### Tournament-Votes ####################
###########################################################
*/

function getPlayerIdsFromVote($con,$vote_id)
{
	/* Used in:
		:Admin
		- create_tm.php
	*/

	$result = mysqli_query($con,"SELECT player_id FROM tm_vote_player WHERE tm_vote_id = '$vote_id'");
	while($row=mysqli_fetch_array($result))
	{
		$votedPlayerIds[] = $row["player_id"];
	}

	return $votedPlayerIds;
}

function getVotedGames($con,$game_id)
{
	/* Used in:
		:Admin
		- vote_tm.php
	*/

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
	/* Used in:
		:User
		- function.php/displayRunningVotes

		:Admin
		- admin_func.php/displayVotedTournaments
	*/

	$result = mysqli_query($con,"SELECT ID, game_id, vote_count, starttime, DATE_FORMAT(`endtime`, '%d.%m.%Y %H:%i') AS endtime, vote_closed FROM tm_vote ORDER BY endtime DESC");
	if(!empty($result))
	{
		while($row=mysqli_fetch_assoc($result))
		{
			$votedTournaments[] = $row;
		}
	}

	if(empty($votedTournaments) || !isset($votedTournaments))
	{
		$votedTournaments = array();
	}

	return $votedTournaments;
}

function getVotedTournamentsUser($con)
{

	/* Used in:
		:User
		- function.php/displayRunningVotes
	*/

	$result = mysqli_query($con,"SELECT tm_vote.ID, tm_vote.game_id, tm_vote.vote_count, DATE_FORMAT(`endtime`, '%d.%m.%Y %H:%i') AS endtime, tm_vote.vote_closed, games.banner FROM tm_vote LEFT JOIN games ON tm_vote.game_id = games.ID ORDER BY tm_vote.endtime DESC");
	while($row=mysqli_fetch_assoc($result))
	{
		$votedTournaments[] = $row;
	}

	return $votedTournaments;
}

function getVoteIds($con)
{
	/* Used in:
		:User
		- add_vote.php
	*/

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
	/* Used in:
		:User
		- vote_tm.php
	*/

	$result = mysqli_query($con,"SELECT ID FROM tm_vote WHERE game_id = '$game_id'");
	while($row=mysqli_fetch_array($result))
	{
		$voteID = $row["ID"];
	}
	
	return $voteID;
}

function getVotedGamesByPlayerId($con,$player_id)
{
	/* Used in:
		:User
		- vote_tm.php
	*/

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
	/* Used in:
		:User
		- add_vote.php
	*/

	return mysqli_num_rows(mysqli_query($con, "SELECT * FROM tm_vote_player WHERE tm_vote_id = '$vote_id' AND player_id ='$player_id';")) > 0;
}

function getVotedPlayers($con,$vote_id)
{
	/* Used in:
		:Admin
		- create_tm.php
	*/

	return mysqli_num_rows(mysqli_query($con,"SELECT player_id FROM tm_vote_player WHERE tm_vote_id = '$vote_id'"));
}

function getVoteById($con,$vote_id)
{
	/* Used in:
		:User
		- add_vote.php
	*/

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

function getSingleTournamentGame($con,$tm_id)
{
	/* Used in:
		:User
		- function.php/displayTournamentLocked
	*/

	$result = mysqli_query($con,"SELECT game_id FROM tm WHERE ID = '$tm_id'");
	while($row=mysqli_fetch_array($result))
	{
		$tm_game = $row["game_id"];
	}

	return $tm_game;
}

function getTournaments($con)
{
	/* Used in:
		:User
		- function.php/displayTournaments
		- function.php/displayTournamentTree

		:Admin
		- admin_func.php/displayTournaments
	*/

	$result = mysqli_query($con,"SELECT ID, game_id, mode, mode_details, player_count, tm_period_id FROM tm");
	if(!empty($result))
	{
		while($row=mysqli_fetch_assoc($result))
		{
			$tms[] = $row;
		}
	}

	if(empty($tms) || !isset($tms))
	{
		$tms = array();
	}

	return $tms;
}

function getGamesFromTournament($con,$game_id)
{
	/* Used in:
		:User
		- vote_tm.php
	*/

	return mysqli_num_rows(mysqli_query($con,"SELECT ID FROM tm WHERE game_id = '$game_id'")) > 0;
}


function getLastTmId($con)
{
	/* Used in:
		:Admin
		- create_tm.php
	*/

	$result = mysqli_query($con,"SELECT ID FROM tm ORDER BY ID DESC LIMIT 1");
	while($row=mysqli_fetch_array($result))
	{
		$last_tm_id = $row["ID"];
	}

	return $last_tm_id;
}

function getMatchesIdFromPaarung($con,$tm_id)
{
	/* Used in:
		:Admin
		- delete_tm.php
	*/

	$result = mysqli_query($con,"SELECT matches_id FROM tm_paarung WHERE tournament = '$tm_id'");
	while($row=mysqli_fetch_array($result))
	{
		$matches_id[] = $row["matches_id"];
	}

	return $matches_id;
}

function getSingleMatchesIdFromPaarung($con,$pair_id)
{
	/* Used in:
		:User
		- function.php/displayTournamentLocked
		- function.php/matchResultHandling
		- enter_result.php
	*/

	$result = mysqli_query($con,"SELECT matches_id FROM tm_paarung WHERE ID = '$pair_id'");
	while($row=mysqli_fetch_array($result))
	{
		$matches_id = $row["matches_id"];
	}

	return $matches_id;
}

function getMatchIdFromMatches($con,$matches_id)
{
	/* Used in:
		:User
		- function.php/displayTournamentLocked
		- function.php/matchResultHandling
		- enter_result.php

		:Admin
		- delete_tm.php
	*/

	$result = mysqli_query($con,"SELECT match_id FROM tm_matches WHERE ID = '$matches_id'");
	while($row=mysqli_fetch_array($result))
	{
		$match_id = $row["match_id"];
	}

	if(empty($match_id))
	{
		$match_id = "";
	}

	return $match_id;
}

function getPlayerIdFromGamerslist($con,$tm_id)
{
	/* Used in:
		:Admin
		- create_tm.php
	*/

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

function getPlayerFromGamerslist($con,$tm_id)
{
	/* Used in:
		:User
		- function.php/displayTournamentParticipants
	*/

	$result = mysqli_query($con,"SELECT player_id FROM tm_gamerslist WHERE tm_id = '$tm_id'");
	while($row=mysqli_fetch_array($result))
	{
		$player_ids[] = $row["player_id"];
	}

	$player_name = array();

	foreach ($player_ids as $player_id)
	{
		$result = mysqli_query($con,"SELECT name FROM player WHERE ID = '$player_id'");
		while($row=mysqli_fetch_array($result))
		{
			$single_name = $row["name"];
		}

		array_push($player_name,$single_name);
	}

	return $player_name;
}

function getTmById($con,$tm_id)
{
	/* Used in:
		:Admin
		- delete_tm.php
		- start_tm.php
	*/

	$result = mysqli_query($con,"SELECT ID FROM tm WHERE ID = '$tm_id'");
	while($row=mysqli_fetch_array($result))
	{
		$existing_tm = $row["ID"];
	}

	return $existing_tm;
}

function getTournamentPeriodId($con)
{
	/* Used in:
		:Admin
		- create_tm.php
	*/

	$result = mysqli_query($con,"SELECT ID FROM tm_period ORDER BY ID DESC LIMIT 1");
	while($row=mysqli_fetch_array($result))
	{
		$tm_period_id = $row["ID"];
	}

	return $tm_period_id;
}

function getTournamentPeriod($con,$period_id)
{
	/* Used in:
		:User
		- function.php/displayTournaments

		:Admin
		- admin_func.php/displayTournaments
	*/

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

function getPeriodIdFromTournament($con,$tm_id)
{
	/* Used in:
		:Admin
		- delete_tm.php
	*/

	$result = mysqli_query($con,"SELECT tm_period_id FROM tm WHERE ID = '$tm_id'");
	while($row=mysqli_fetch_array($result))
	{
		$tm_period_id = $row["tm_period_id"];
	}

	return $tm_period_id;
}

function getTournamentStatus($con,$tm_id)
{
	/* Used in:
		:User
		- function.php/displayTournamentTree

		:Admin
		- delete_tm.php
		- start_tm.php
	*/

	$result = mysqli_query($con,"SELECT tm_locked FROM tm WHERE ID = '$tm_id'");
	while($row=mysqli_fetch_array($result))
	{
		$tm_locked = $row["tm_locked"];
	}

	if(empty($tm_locked))
	{
		$tm_locked = "";
	}

	return $tm_locked;
}

function getTmBanner($con,$tm_id)
{
	/* Used in:
		:User
		- function.php/displayTournamentParticipants
	*/

	$result = mysqli_query($con,"SELECT banner FROM games INNER JOIN tm ON games.ID = tm.game_id WHERE tm.ID = '$tm_id'");
	while($row=mysqli_fetch_array($result))
	{
		$game_banner = $row["banner"];
	}

	return $game_banner;
}

function getPlayerCountTm($con,$tm_id)
{
	/* Used in:
		:User
		- leave_tm.php
		- join_tm.php
	*/

	$result = mysqli_query($con,"SELECT player_count FROM tm WHERE ID = '$tm_id'");
	while($row=mysqli_fetch_array($result))
	{
		$player_count = $row["player_count"];
	}

	return $player_count;
}

function getJointPlayer($con,$tm_id,$player_id)
{
	/* Used in:
		:User
		- join_tm.php
	*/

	return mysqli_num_rows(mysqli_query($con,"SELECT player_id FROM tm_gamerslist WHERE tm_id = '$tm_id' AND player_id = '$player_id'")) > 0;
}

function getUsernameFromGamerslist($con,$gamerslist_id)
{
	/* Used in:
		:User
		- function.php/displayTournamentLocked
	*/

	$result = mysqli_query($con,"SELECT name FROM player INNER JOIN tm_gamerslist ON tm_gamerslist.player_id = player.ID WHERE tm_gamerslist.ID = '$gamerslist_id'");
	while($row=mysqli_fetch_array($result))
	{
		$player_name = $row["name"];
	}

	if(!isset($player_name) || empty($player_name))
	{
		$player_name = "";
	}

	return $player_name;
}

function getGamerslistIdByPlayerId($con,$player_id,$tm_id)
{
	/* Used in:
		:User
		- enter_result.php
	*/

	$result = mysqli_query($con,"SELECT ID FROM tm_gamerslist WHERE player_id = '$player_id' AND tm_id = '$tm_id'");
	while($row=mysqli_fetch_array($result))
	{
		$gamerslist_id = $row["ID"];
	}

	return $gamerslist_id;
}

function getGamerslistIdFromPair($con,$gamerslist_id,$pair_id)
{
	/* Used in:
		:User
		- enter_result.php
	*/

	return mysqli_num_rows(mysqli_query($con,"SELECT team_1, team_2 FROM tm_paarung WHERE ID = '$pair_id' AND ((team_1 = '$gamerslist_id') || (team_2 = '$gamerslist_id'))")) > 0;
}

function getNewMatchId($con)
{
	/* Used in:
		:Admin
		- start_tm.php
	*/

	$result = mysqli_query($con,"SELECT ID FROM tm_match ORDER BY ID DESC LIMIT 1");
	while($row=mysqli_fetch_array($result))
	{
		$new_match_id = $row["ID"];
	}

	return $new_match_id;
}

function getNewMatchesId($con)
{
	/* Used in:
		:Admin
		- start_tm.php
	*/

	$result = mysqli_query($con,"SELECT ID FROM tm_matches ORDER BY ID DESC LIMIT 1");
	while($row=mysqli_fetch_array($result))
	{
		$new_matches_id = $row["ID"];
	}

	return $new_matches_id;
}

function getPairCount($con,$tm_id)
{
	/* Used in:
		:Admin
		- start_tm.php
	*/

	return mysqli_num_rows(mysqli_query($con,"SELECT ID FROM tm_paarung WHERE (tournament = '$tm_id') AND (successor IS NULL)"));
}

function getLastPairId($con,$tm_id)
{
	/* Used in:
		:Admin
		- start_tm.php
	*/

	$result = mysqli_query($con,"SELECT ID FROM tm_paarung WHERE (tournament = '$tm_id') AND (successor IS NULL) ORDER BY ID DESC LIMIT 1");
	while($row=mysqli_fetch_array($result))
	{
		$last_pair_id = $row["ID"];
	}

	return $last_pair_id;
}

function getFirstPairId($con,$tm_id,$stage)
{
	/* Used in:
		:Admin
		- start_tm.php
	*/

	$result = mysqli_query($con,"SELECT ID FROM tm_paarung WHERE (tournament = '$tm_id') AND (successor IS NULL) AND (stage = '$stage') ORDER BY ID ASC LIMIT 2");
	while($row=mysqli_fetch_array($result))
	{
		$first_pair_id[] = $row["ID"];
	}

	return $first_pair_id;
}

function getGamerslistIdByPair($con,$pair_id)
{
	/* Used in:
		:User
		- function.php/matchResultHandling
	*/

	$result = mysqli_query($con,"SELECT team_1, team_2 FROM tm_paarung WHERE ID = '$pair_id'");
	while($row=mysqli_fetch_assoc($result))
	{
		$gamerslist_ids = $row;
	}

	return $gamerslist_ids;
}

function getSuccessorFromPair($con,$pair_id)
{
	/* Used in:
		:User
		- function.php/displayTournamentLocked
		- function.php/matchResultHandling
	*/

	$result = mysqli_query($con,"SELECT successor FROM tm_paarung WHERE ID = '$pair_id'");
	while($row=mysqli_fetch_array($result))
	{
		$successor = $row["successor"];
	}

	return $successor;
}

function getStages($con,$tm_id)
{
	/* Used in:
		:User
		- function.php/displayTournamentLocked
	*/

	$result = mysqli_query($con,"SELECT DISTINCT stage FROM tm_paarung WHERE tournament = '$tm_id'");
	while($row=mysqli_fetch_array($result))
	{
		$stages[] = $row["stage"];
	}

	return $stages;
}

function getPairsByStages($con,$tm_id,$stage)
{
	/* Used in:
		:User
		- function.php/displayTournamentLocked
	*/

	$result = mysqli_query($con,"SELECT ID, team_1, team_2 FROM tm_paarung WHERE tournament = '$tm_id' AND stage = '$stage'");
	while($row=mysqli_fetch_assoc($result))
	{
		$pairs[] = $row;
	}

	return $pairs;
}

function getFirstLevelWildcard($con,$tm_id)
{
	/* Used in:
		:Admin
		- start_tm.php
	*/
	$result = mysqli_query($con,"SELECT ID FROM tm_paarung WHERE tournament = '$tm_id' AND team_2 IS NULL AND stage = '1'");
	while($row=mysqli_fetch_array($result))
	{
		$wildcard = $row["ID"];
	}

	if(empty($wildcard) || !isset($wildcard))
	{
		$wildcard = "";
	}

	return $wildcard;
}

function getGamerslistIdAndSuccessor($con,$pair_id)
{
	/* Used in:
		:Admin
		- start_tm.php
	*/

	$result = mysqli_query($con,"SELECT team_1, successor FROM tm_paarung WHERE ID = '$pair_id'");
	while($row=mysqli_fetch_assoc($result))
	{
		$pair_data[] = $row;
	}

	return $pair_data;
}

function getSuccessorCount($con,$successor_id)
{
	/* Used in:
		:User
		- function.php/displayTournamentLocked
		- function.php/matchResultHandling
	*/

	mysqli_num_rows(mysqli_query($con,"SELECT ID FROM tm_paarung WHERE successor = '$successor_id'"));
}

function getResultP1FromMatch($con,$match_id)
{
	/* Used in:
		:User
		- function.php/displayTournamentLocked
		- function.php/matchResultHandling
	*/

	$result = mysqli_query($con,"SELECT result_team1 FROM tm_match WHERE ID = '$match_id'");
	while($row=mysqli_fetch_array($result))
	{
		$result_p1 = $row["result_team1"];
	}

	if(!isset($result_p1))
	{
		$result_p1 = "";
	}

	return $result_p1;
}

function getResultP2FromMatch($con,$match_id)
{
	/* Used in:
		:User
		- function.php/displayTournamentLocked
		- function.php/matchResultHandling
	*/

	$result = mysqli_query($con,"SELECT result_team2 FROM tm_match WHERE ID = '$match_id'");
	while($row=mysqli_fetch_array($result))
	{
		$result_p2 = $row["result_team2"];
	}

	if(!isset($result_p2))
	{
		$result_p2 = "";
	}

	return $result_p2;
}

function getSinglePlayerIDFromGamerslist($con,$tm_id,$player_id)
{
	/* Used in:
		:User
		- leave_tm.php
	*/

	return mysqli_num_rows(mysqli_query($con,"SELECT ID FROM tm_gamerslist WHERE tm_id = '$tm_id' AND player_id = '$player_id'")) > 0;
}

function getSecondPairId($con,$pair_id,$successor_id)
{
	/* Used in:
		:User
		- function.php/matchResultHandling
	*/

	$result = mysqli_query($con,"SELECT ID FROM tm_paarung WHERE successor = '$successor_id' AND ID != '$pair_id'");
	while($row=mysqli_fetch_array($result))
	{
		$second_pair = $row["ID"];
	}

	return $second_pair;
}

function getMatchLockTime($con,$match_id)
{
	/* Used in:
		:User
		- enter_result.php
	*/

	$result = mysqli_query($con,"SELECT match_locked FROM tm_matches WHERE match_id = '$match_id'");
	while($row=mysqli_fetch_array($result))
	{
		$lock_time = $row["match_locked"];
	}

	if(empty($lock_time))
	{
		$lock_time = "";
	}

	return $lock_time;
}
?>