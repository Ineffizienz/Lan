<?php

	/*
	********************* GET-PARAMETERS ****************************
	*/

	include("init/get_parameters.php");

	/*
	***************************** Build-Functions **********************************
	*/

	function build_content($file) // liest HTML-Fragmente ein und fügt sie an der entsprechenden Stelle ein
	{
		if (file_exists("template/" . $file))
		{
			$data = fopen("template/" . $file, "r");
			while (!feof($data))
			{
				if (!isset($content))
				{
					$content = fgets($data);
				} else {	
					$content .= fgets($data);
				}
			}
			fclose($data);
			return ($content);
		}
	}

	function build_option($optionArr,$selected)
	{
		
		$output_option = "<option value='" . $selected["id"] . "' selected>" . $selected["name"];

		$part = file_get_contents("template/part/option.html");

		foreach ($optionArr as $option)
		{
			if($option["id"] !== $selected["id"])
			{
				$output_option .= str_replace(array("--VALUE--","--NAME--"),array($option["id"],$option["name"]),$part);	
			} 
		}
		
		return $output_option;	
	}

	/**
	 * 
	 * @param mysqli $con
	 * @param string $username
	 * @param int $player_id
	 * @return boolean true on success
	 */
	function initializePlayer(mysqli $con, string $username, int $player_id)
	{
		$sql_user = "UPDATE player SET name='$username' WHERE ID='$player_id'";
		if (mysqli_query($con,$sql_user))
		{
			$sql_fl = "UPDATE player SET first_login = '0' WHERE ID='$player_id'";
			if(mysqli_query($con,$sql_fl))
			{
				$sql_status = "INSERT INTO status (user_id,status) VALUES ('$player_id','1')";
				if(mysqli_query($con,$sql_status))
				{
					$sql_ac = "ALTER TABLE ac_player ADD `$player_id` INT(11) NULL";
					if(mysqli_query($con,$sql_ac))
					{
						return true;
					}
				}
			}
		}
		return false;
	}
	
	function validateImage($filesize,$filetype)
	{
		if (isset($filesize) && ($filesize != 0))
		{
			if($filesize < 5242880)
			{
				if($filesize < 5)
				{
					return "ERR_FILE_TO_SMALL";
				} else {
					if(($filetype !== "jpg") && ($filetype !== "png") && ($filetype !== "jpeg") && ($filetype !== "gif"))
					{
						return "ERR_NO_IMAGE_TYPE";
					} else {
						return 1;
					}	
				}	
			} else {
				return "ERR_FILE_TO_HUGE";
			}
		} else {
			return "ERR_NO_IMAGE";
		}
	}
	
	function ownTeam($con,$ip) //maybe not used anymore
	{
		$team_id = getTeamId($con,$ip);

		if (empty($team_id))
		{
			$team = "<span>Du bist gegenwärtig in keinem Team.</span>";
		} else {
			$name = getTeamName($con,$team_id);
			$team = "<span>Dein akutelles Team ist: " . $name . "</span>";
		}

		return $team;
	}
	
	function countPlayer($con) //Count overall players
	{
		$result = mysqli_query($con,"SELECT COUNT(name) AS total FROM player");
		$c_player = mysqli_fetch_assoc($result);

		return $c_player["total"];
	}

	function countTeammember($con,$team_id) //Count your teammember
	{
		$result = mysqli_query($con,"SELECT COUNT(name) AS total FROM player WHERE team_id = '$team_id'");
		$c_teammember = mysqli_fetch_assoc($result);

		return $c_teammember["total"];
	}

	function countTeams($con) //Count overall Teams
	{
		$result = mysqli_query($con,"SELECT COUNT(ID) AS total FROM tm_teamname");
		$c_teams = mysqli_fetch_assoc($result);

		return $c_teams["total"];
	}
	function generate_options($con) // generiert options für select-Feld beim keygen
	{
		$gameinfo = getGameInfo($con);

		$selected = array("id"=>"default","name"=>"Bitte wähle ein Spiel aus");

		$option = build_option($gameinfo,$selected);
		
		return $option;
	}
	function members($con) // gibt die vorhanden Teams aus (Teamname + Spieler)
	{
		$teams = getAllTeams($con);

		if (!empty($teams))
		{
			foreach ($teams as $team)
			{
				$member = getTeamMember($con,$team["ID"]);
				$member = implode(", ",$member);

				$part = file_get_contents("template/part/team.html");

				if(!isset($team_list))
				{
					$team_list = str_replace(array("--TEAM_ID--","--TEAM_NAME--","--MEMBER--"),array($team["ID"],$team["name"],$member),$part);
				} else {
					$team_list .= str_replace(array("--TEAM_ID--","--TEAM_NAME--","--MEMBER--"),array($team["ID"],$team["name"],$member),$part);
				}
			}
		}

		return $team_list;
	}
	function teamMembers($con,$player_id) //Gibt die eigenen Teammitglieder aus
	{
		$team_id = getTeamId($con,$player_id);  // beziehen der eigenen Team-ID
		$team_members = getTeamMembers($con,$player_id,$team_id);

		if (!empty($team_members))
		{
			foreach ($team_members as $team_member)
			{
				if (!isset($members))
				{
					$members = "<option>" . $team_member;
				} else {
					$members .= "<option>" . $team_member;
				}
			}
			return $members;
		}
	}
	function getUserRelatedStatusColor($con,$player_id)
	{
		//$user_id = getUserId($con,$ip); --> remove
		$status = getStatus($con,$player_id);
		$status_color = getStatusColor($con,$status);

		$circle = "<div id='status_circle' style='background-color:" . $status_color . ";'>&nbsp;</div>";

		return $circle;
	}

	function getUserStatusOption($con,$player_id)
	{
		//$user_id = getUserId($con,$ip); --> remove
		$status_data = getStatusData($con);
		$user_status = getStatus($con,$player_id);
		$status_name = getStatusName($con,$user_status);

		
		$selected = array("id"=>$user_status,"name"=>$status_name);
		$output = build_option($status_data,$selected);
		
		return $output;

	}

	function generateGameKey($con,$raw_name,$player_id)
	{
		$key = getPlayerGameKey($con,$player_id,$raw_name);

		if ((empty($key)) || ($key == "") || ($key == NULL))
		{

			$first_key = getGameKey($con,$raw_name);

			if(empty($first_key))
			{
				$message_code = "ERR_NO_KEY";
				return $message_code;
			} else {
				mysqli_query($con,"UPDATE $raw_name SET player_id = '$player_id' WHERE game_key = '$first_key'");
				return $first_key;
			}
		} else {
			return $key;
		} 
	}

	function displayProfilImage($con,$player_id)
	{

		$profil_image = getUserImage($con,$player_id);

		if (empty($profil_image))
		{
			return build_content("part/empty_image.html");
		} else {
			$image_template = file_get_contents("template/part/profil_image.html");

			$output = str_replace("--IMAGE_PATH--",$profil_image,$image_template);

			return $output;
		}
		
	}
	
	function displaySinglePlayerTeam($con, $player_id)
	{
		$team_id = getTeamId($con, $player_id);
		
		if(empty($team_id))
		{
			$teamname = getSinglePlayerTeam($con, $player_id);
			return $teamname;
		} else {
			$teamname = getSinglePlayerTeam($con, $player_id);
			$player_team = file_get_contents("template/part/player_team.html");
			$output = str_replace(array("--TEAM--","--TEAMID--","--ID--"), array($teamname,$team_id, $player_id), $player_team);
			
			return $output;
		}
	}

	function displayCaptain($con, $player_id)
	{
		$self_captain = getCaptainStatus($con, $player_id);

		if(empty($self_captain))
		{
			$team_id = getTeamId($con, $player_id);
			$team_captain = getTeamCaptain($con, $team_id);

			if(empty($team_captain))
			{
				$output = "<i>Kein Teamchef bestimmt.</i>";
			} else {
				$output = $team_captain;
			}
		}

		return $output;
	}

	function displayPlayerTeamMember($con, $player_id)
	{
		$team_id = getTeamId($con, $player_id);

		$team_member = getTeamMembers($con, $player_id, $team_id);

		if(empty($team_member))
		{
			$output = "<i>Du hast gegenwärtig keine anderen Teammitglieder.</i>";
		} else {
			$output = implode(", ",$team_member);
		}

		return $output;
	}

	function displayPlayerPrefs($con, $player_id)
	{
		$player_pref = getSinglePlayerPref($con, $player_id);

		if(empty($player_pref))
		{
			$output = "<i>Du hast deine Präferenzen noch nicht festgelegt.</i>";
		} else {
			$part = file_get_contents("template/part/single_pref.html");

			foreach ($player_pref as $pref)
			{
				$gameInfo = getGameInfoById($con,$pref);
				if (!isset($output))
				{
					$output = str_replace(array("--GAME_ID--","--ICON--","--PREF--"), array($pref,$gameInfo["icon"],$gameInfo["short_title"]), $part);
				} else {
					$output .= str_replace(array("--GAME_ID--","--ICON--","--PREF--"), array($pref,$gameInfo["icon"],$gameInfo["short_title"]), $part);
				}
				
			}
		}

		return $output;
	}
	
	function createCheckbox($con, $player_id)
	{
		$games = getGameData($con);

		if(empty($games))
		{
			$output = "<i>Keine Spiele vorhanden</i>";
		} else {

			$userPrefs = getSinglePlayerPref($con, $player_id);

			$part = file_get_contents("template/part/checkbox_container.html");
			$checked_part = file_get_contents("template/part/checkbox_container_checked.html");

			foreach ($games as $game)
			{
				if(in_array($game["ID"],$userPrefs))
				{
					if(!isset($output))
					{
						$output = str_replace(array("--GAME_ID--","--NAME--","--ICON--"),array($game["ID"],$game["name"],$game["icon"]),$checked_part);
					} else {
						$output .= str_replace(array("--GAME_ID--","--NAME--","--ICON--"),array($game["ID"],$game["name"],$game["icon"]),$checked_part);
					}
				} else {
					if(!isset($output))
					{
						$output = str_replace(array("--GAME_ID--","--NAME--","--ICON--"),array($game["ID"],$game["name"],$game["icon"]),$part);
					} else {
						$output .= str_replace(array("--GAME_ID--","--NAME--","--ICON--"),array($game["ID"],$game["name"],$game["icon"]),$part);
					}
				}
				
			}
		}

		return $output;
	}

/******************************* WOW-Server ************************************/

function selectWowAccount($con,$con_wow,$con_char,$player_id)
{
	$wow_account = getWowAccount($con,$player_id);

	if(empty($wow_account))
	{
		$tpl = new template();
		$tpl->load("wow_server/create_wow_account.html");
		$template = $tpl->r_display();

		return $template;
	} else {
		$wow_id = getWowId($con_wow,$wow_account);
		$wow_account_chars = getChars($con_char,$wow_id);

		if(empty($wow_account_chars))
		{
			$tpl = new template();
			$tpl->load("wow_server/character_table_empty.html");
			$template = $tpl->r_display();
			return $template;
		} else {
			$tpl = new template();
			$tpl->load("wow_server/characters_table.html");
			$output = "";

			foreach($wow_account_chars as $chars)
			{
				$race = defineRace($chars["race"]);
				$class = defineClass($chars["class"]);
				$loc = defineLocation($chars["map"]);
				$part = file_get_contents("template/part/characters_row.html");
				if (!isset($output))
				{
					$output = str_replace(array("--NAME--","--RACE--","--CLASS--","--LEVEL--","--LOCATION--"),array($chars["name"],$race,$class,$chars["level"],$loc),$part);
				} else {
					$output .= str_replace(array("--NAME--","--RACE--","--CLASS--","--LEVEL--","--LOCATION--"),array($chars["name"],$race,$class,$chars["level"],$loc),$part);
				}
			}
			$tpl->assign("characters",$output);
			$template = $tpl->r_display();
			return $template;
		}
	}
}

function defineRace($race_id)
{
	switch ($race_id) {
		case "1":
			$race = "Mensch";
		break;
		case "2":
			$race = "Ork";
		break;
		case "3":
			$race = "Zwerg";
		break;
		case "4":
			$race = "Nachtelf";
		break;
		case "5":
			$race = "Untote";
		break;
		case "6":
			$race = "Tauren";
		break;
		case "7":
			$race = "Gnom";
		break;
		case "8":
			$race = "Troll";
		break;
		case "9":
			$race = "Goblin";
		break;
		case "10":
			$race = "Blutelf";
		break;
		default:
			$race = "Draenei";
	}
	
	return $race;
}

function defineClass($class_id)
{
	switch ($class_id) {
		case "1":
			$class = "Krieger";
		break;
		case "2":
			$class = "Paladin";
		break;
		case "3":
			$class = "Jäger";
		break;
		case "4":
			$class = "Schurke";
		break;
		case "5":
			$class = "Priester";
		break;
		case "6":
			$class = "Todesritter";
		break;
		case "7":
			$class = "Schamane";
		break;
		case "8":
			$class = "Magier";
		break;
		case "9":
			$class = "Hexenmeister";
		break;
		case "11":
			$class = "Druide";
		break;
		default:
			$class = "Nicht implementiert.";
	}

	return $class;
}

function defineLocation($loc_id)
{
	switch ($loc_id) {
		case "0":
			$loc = "Nicht dem Server beigetreten.";
		break;
		case "571":
			$loc = "Dalaran";
		break;
		default:
			$loc = "Nicht implementiert.";
	}

	return $loc;
}

function displayServerStatus($con_wow)
{
	$realm_flag = getServerStatus($con_wow);

	if ($realm_flag == 0)
	{
		$server_status = "<span style='font-style:italic;color:green;'>Online</span>";
	} elseif ($realm_flag == 2) {
		$server_status = "<span style='font-italic;color:red;'>Offline</span>";
	}

	return $server_status;
}

/******************************* ACHIEVEMENTS ************************************/

function displayPlayerAchievements($con, $player_id)
{
	$achievement_id = getUserAchievements($con, $player_id);

	$ac = new Achievement();
	if (empty($achievement_id))
	{
		$output = "Du hast bisher keine Achievements erworben.";
	} else {
		foreach ($achievement_id as $id)
		{
			$achievement_details = getAchievementById($con, $id);

			foreach ($achievement_details as $achievement)
			{
				$ac->getDetails($achievement);
			
				if (!isset($output))
				{
					$output = $ac->displayAchievement();
				} else {
					$output .= $ac->displayAchievement();
				}
			}
		}
		return $output;
	}

}

function displayAvailableAchievements($con, $player_id)
{
	$basic_ac = getAvailableAchievements($con, $player_id);
	
	$ac = new Achievement();
	if(!empty($basic_ac))
	{
		foreach ($basic_ac as $basic)
		{
			$ac->getBasicDetails($basic);
			
			if(!isset($output))
			{
				$output = $ac->displayAchievement();
			} else {
				$output .= $ac->displayAchievement();
			}
		}
		return $output;
	}
}

/******************************* TOURNAMENTS ************************************/

function generateVoteOption($con)
{
	$games = getFullGameData($con);
	$selected = array();

	$part = file_get_contents("template/part/option.html");

	foreach ($games as $game)
	{
		if(!isset($output))
		{
			$output = str_replace(array("--VALUE--","--NAME--"),array($game["ID"],$game["name"]),$part);
		} else {
			$output .= str_replace(array("--VALUE--","--NAME--"),array($game["ID"],$game["name"]),$part);
		}
	}

	return $output;

}

function displayRunningVotes($con)
{
	$votes = getVotedTournaments($con);
	$part = file_get_contents("template/part/running_vote.html");

	foreach ($votes as $vote)
	{
		$game_info = getGameInfoById($con,$vote["game_id"]);
		$banner_url = $game_info["banner"];
		if($vote["vote_closed"] !== "1")
		{
			if(!isset($output))
			{
				$output = str_replace(array("--BANNER--","--PLAYER_COUNT--","--TIME_REMAINING--","--VOTE-ID--"),array($banner_url,$vote["vote_count"],$vote["endtime"],$vote["ID"]),$part);
			} else {
				$output .= str_replace(array("--BANNER--","--PLAYER_COUNT--","--TIME_REMAINING--","--VOTE-ID--"),array($banner_url,$vote["vote_count"],$vote["endtime"],$vote["ID"]),$part);
			}
		}
	}

	if(empty($output))
	{
		$output = "Es gibt gegenwärtig keine Abstimmungen.";
	}
	return $output;
}

function displayTournaments($con)
{
	$tournaments = getTournaments($con);
	$part = file_get_contents("template/part/overview_tournament.html");

	foreach ($tournaments as $tournament)
	{
		$game_info = getGameInfoById($con,$tournament["game_id"]);
		$banner = $game_info["banner"];
		$tm_period = getTournamentPeriod($con,$tournament["tm_period_id"]);

		if(empty($tournament["player_count"]))
		{
			$player_count = "0";
		} else {
			$player_count = $tournament["player_count"];
		}

		if(!isset($output))
		{
			$output = str_replace(array("--TM_ID--","--BANNER--","--TIME_FROM--","--PLAYER_COUNT--"),array($tournament["ID"],$banner,$tm_period["time_from"],$player_count),$part);
		} else {
			$output .= str_replace(array("--TM_ID--","--BANNER--","--TIME_FROM--","--PLAYER_COUNT--"),array($tournament["ID"],$banner,$tm_period["time_from"],$player_count),$part);
		}
	}

	if(empty($output))
	{
		$output = "Es gibt gegenwärtig keine Turniere.";
	}

	return $output;
}

function displayTournamentParticipants($con,$tm_id)
{
	$tm_player = getPlayerFromGamerslist($con,$tm_id);
	$banner = getTmBanner($con,$tm_id);

	$list = implode(", ",$tm_player);
	
	$part = file_get_contents("template/part/unlocked_tm.html");

	$output = str_replace(array("--BANNER--","--PLAYER_LIST--","--TM_ID--"),array($banner,$list,$tm_id),$part);

	return $output;
}

function displayTournamentLocked($con,$tm_id)
{
	$tm_player_pair = getTmPairs($con,$tm_id);

	$part = file_get_contents("template/part/locked_tm.html");
	$part_pair = file_get_contents("template/part/player_pair.html");
	foreach ($tm_player_pair as $pair)
	{
		$player_1 = $pair[0];
		$player_2 = $pair[1];
		if(!isset($pair_output))
		{
			$pair_output = str_replace(array("--PLAYER_1--","PLAYER_2--"),array($player_1,$player_2),$part_pair);
		} else {
			$pair_output .= str_replace(array("--PLAYER_1--","PLAYER_2--"),array($player_1,$player_2),$part_pair);
		}
	}

	$output = str_replace("--PLAYER-PAIR--",$pair_output,$part);

	return $output;
}

function displayTournamentTree($con)
{
	if(isset($_REQUEST["id"]))
	{
		$tm_id = $_REQUEST["id"];
		$tm_status = getTournamentStatus($con,$tm_id);

		if($tm_status !== "1")
		{
			$tournament = displayTournamentParticipants($con,$tm_id);
		} else {
			$tournament = displayTournamentLocked($con,$tm_id);
		}
	}

	if(!empty($tournament))
	{
		return $tournament;
	}
}

?>