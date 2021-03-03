<?php

	/*
	********************* GET-PARAMETERS ****************************
	*/

	include("init/get_parameters.php");

	/*
	***************************** Build-Functions **********************************
	*/

	function build_option_new(array $options)
	{
		$tpl = new template("part/option.html");
		$tpl->assign_array($options);

		return $tpl->r_display();
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

		$option = build_option_new($gameinfo);
		
		return "<option value='default' selected>Bitte wähle ein Spiel aus". $option;
	}
	function members($con) // gibt die vorhanden Teams aus (Teamname + Spieler)
	{
		$teams = getAllTeamsWithMember($con);

		$tpl = new template("template/part/");
		$tpl->load("team.html");
		$tpl->assign_array($teams);

		return $tpl->r_display();
	}
	
	function getUserRelatedStatusColor($con,$player)
	{
		$status_color = getStatusColor($con,$player->getPlayerStatusId());

		$circle = "<div id='status_circle' style='background-color:" . $status_color . ";'>&nbsp;</div>";

		return $circle;
	}

	function getUserStatusOption($con,$player)
	{
		$status_data = getStatusData($con, $player->getPlayerStatusId());

		$option = build_option_new($status_data);
		
		return "<option value='" . $player->getPlayerStatusId() . "' selected>" . $player->getPlayerStatusName() . $option;

	}

	function generateGameKey($con, $player, int $game_id)
	{
		$key = getPlayerGameKey($con, $player->getPlayerId(), $game_id);

		if ($key === false)
		{
			$first_key = getGameKey($con, $game_id);

			if($first_key === false)
			{
				$message_code = "ERR_NO_KEY";
				return $message_code;
			} else {
				$sql = "UPDATE gamekeys SET player_id = '$player->getPlayerId()' WHERE gamekey = '$first_key' AND game_id = '$game_id'";
				if(mysqli_query($con,$sql))
				{
					return $first_key;
				}
			}
		} else {
			return $key;
		} 
	}

	function displayProfilImage(mysqli $con, $player): template
	{
		if (empty($player->image))
			return new template("part/empty_image.html");
		else {
			$tpl = new template("part/profil_image.html");
			return $tpl->assign('image_path', $player->image);
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

	function displayPlayerPrefs($con, $player)
	{
		$tpl = new template("part/single_pref.html");
		$tpl->assign_array($player->getPlayerPreferences());

		return $tpl->r_display();
	}
	
	function createCheckbox($con, $player)
	{
		$games = getGameData($con);

		if(empty($games))
		{
			$output = "<i>Keine Spiele vorhanden</i>";
			return $output;
		} else {
			$options = array();
			$checkbox = new template("part/checkbox_container.html");
			
			foreach ($games as $game)
			{
				if(in_array($game["ID"],array_column($player->getPlayerPreferences(),"ID")))
				{
					$checkbox_checked = new template("part/checkbox_checked.html");
					$checkbox_checked->assign("game_id",$game["ID"]);
					$checkbox_checked->assign("name",$game["name"]);
					$is_userpref = array("game_id" => $game["ID"],"name" => $game["name"],"icon" => $game["icon"],"checkbox" => $checkbox_checked->r_display());
					array_push($options,$is_userpref);
				} else {
					$checkbox_unchecked = new template("part/checkbox_unchecked.html");
					$checkbox_unchecked->assign("game_id",$game["ID"]);
					$checkbox_unchecked->assign("name",$game["name"]);
					$no_userpref = array("game_id" => $game["ID"],"name" => $game["name"],"icon" => $game["icon"],"checkbox" => $checkbox_unchecked->r_display());
					array_push($options,$no_userpref);
				}	
			}
			return $checkbox->assign_array($options);
		}
	}

/******************************* WOW-Server ************************************/

function selectWowAccount($con,$con_wow,$con_char,$player)
{
	if(empty($player->getPlayerWowAccount()))
	{
		$tpl = new template();
		$tpl->load("wow_server/create_wow_account.html");
		$template = $tpl->r_display();

		return $template;
	} else {
		$wow_id = getWowId($con_wow,$player->getPlayerWowAccount());
		$wow_account_chars = getChars($con_char,$wow_id);

		if(empty($wow_account_chars))
		{
			$tpl = new template();
			$tpl->load("wow_server/character_table_empty.html");
			$tpl->assign("player_wow_account",ucfirst(strtolower($player->getPlayerWowAccount())));
			$template = $tpl->r_display();
			return $template;
		} else {
			$tpl = new template();
			$tpl->load("wow_server/characters_table.html");
			$character_list = array();

			### This Sub-Template defines each row for a character and moves it to an array
				$output = new template();
				$output->load("part/characters_row.html");

				foreach($wow_account_chars as $chars)
				{
					$race = defineRace($chars["race"]);
					$class = defineClass($chars["class"]);
					if($chars["map"] == 0)
					{
						$loc = "Nicht dem Server beigetreten.";
					} else {
						$loc = getWoWRegionById($con,$chars["map"]);
						if(empty($loc))
						{
							$loc = "Region wurde noch nicht implementiert.";
						}
					}

					$character = array("name" => $chars["name"], "race" => $race, "class" => $class, "level" => $chars["level"], "location" => $loc);
					array_push($character_list,$character);
				}
				$output->assign_array($character_list);
			
			$tpl->assign_subtemplate("characters",$output);
			$tpl->assign("player_wow_account",ucfirst(strtolower($player->getPlayerWowAccount())));
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

function displayPlayerAchievements($con, $player)
{
	$tpl = new template("part/single_achievement.html");
	$ac = new Achievement($con);
	$player_achievements = array();
	
	foreach ($player->getPlayerAchievements() as $id)
	{
		array_push($player_achievements,$ac->getPlayerAchievement($id));
	}

	$tpl->assign_array($player_achievements);
	return $tpl->r_display();

}

function displayAvailableAchievements($con, $player)
{
	$tpl = new template("part/ac_small.html");
	$ac = new Achievement($con);
	$achievements = array();
	$basic_ac = getAvailableAchievements($con, $player->getPlayerId());
	
	if(!empty($basic_ac))
	{
		foreach ($basic_ac as $basic)
		{
			array_push($achievements,$ac->getAvailableAchievement($basic));
		}
		
		$tpl->assign_array($achievements);
		return $tpl->r_display();
	}
}

/******************************* TOURNAMENTS ************************************/

function generateVoteOption($con)
{
	$games = getMainGameData($con);
	
	$tpl = new template("part/option.html");
	$tpl->assign_array($games);

	return $tpl->r_display();

}

function displayRunningVotes($con)
{
	$tpl = new template("part/running_vote.html");

	$votes = getVotedTournamentsUser($con);

	if(empty($votes))
	{
		return "Es gibt gegenwärtig keine Abstimmungen.";
	} else {
		$tpl->assign_array($votes);
		return $tpl->r_display();
	}
}

function displayTournaments($con)
{
	$tpl = new template("tournament/overview_tournament.html");

	$tournaments = getTournamentsOverview($con);

	$tpl->assign_array($tournaments);

	return $tpl->r_display();

}

function displayTournamentParticipants($con,$tm_id)
{
	$tpl = new template("tournament/unlocked_tm.html");
	
	$tm_player = getPlayerFromGamerslist($con,$tm_id);
	$tm_banner = getTournamentBanner($con,$tm_id);
	$tm_register = getTournamentEndRegister($con,$tm_id);

	if(empty($tm_player))
	{
		$player_list = "Es sind keine Spieler registriert.";
	} else {
		$player_list = implode(", ",$tm_player);
	}

	$tpl->assign("tm_id",$tm_id);
	$tpl->assign("player_list",$player_list);
	$tpl->assign("tm_banner",$tm_banner);
	$tpl->assign("end_register",$tm_register);
	
	return $tpl->r_display();
}

function displayTournamentLocked($con,$tm_id)
{
	$tournament_array = array();
	$stages = getStages($con,$tm_id);
	$part = new template("tournament/locked_tm.html");
	$part_stages = new template("tournament/tm_section.html");

	foreach ($stages as $stage)
	{
		$stage_array = array();
		$pairs_by_stages = getPairsByStages($con,$tm_id,$stage);
		$part_pair = new template("tournament/player_pair.html");

		foreach ($pairs_by_stages as $pair)
		{
			$pair_id = $pair["ID"];
			$player_1 = $pair["team_1"];
			$player_2 = $pair["team_2"];

			$successor = getSuccessorFromPair($con,$pair_id);

			$player_1 = getUsernameFromGamerslist($con,$player_1);
			if($player_2 == "-1")
			{
				$player_2 = "<i>Wildcard</i>";
			} else {
				$player_2 = getUsernameFromGamerslist($con,$player_2);
			}

			$match_result = getResultFromMatch($con,$pair_id);
			if(empty($match_result["result_team1"]) && empty($match_result["result_team2"]))
			{
				$result_team1 = "0";
				$result_team2 = "0";
			} else {
				$result_team1 = $match_result["result_team1"];
				$result_team2 = $match_result["result_team2"];
			}

			$pair_array = array("tm_id" => $tm_id, "pair_id" => $pair_id, "player_1" => $player_1, "player_2" => $player_2, "result_p1" => $result_team1, "result_p2" => $result_team2);
			array_push($stage_array,$pair_array);
		}
		$part_pair->assign_array($stage_array);
		$step = array("player_pair" => $part_pair->r_display());
		array_push($tournament_array,$step);

	}
	
	$tm_banner = getTournamentBanner($con,$tm_id);

	$part_stages->assign_array($tournament_array);
	$part->assign("banner",$tm_banner);
	$part->assign("section",$part_stages->r_display());

	return $part->r_display();
}

function displayTournamentTree($con):string
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
	} else {
		return "";
	}
}

function displayResultPopup()
{
	$tpl = new template("part/popup/result_popup.html");

	return $tpl->r_display();
}

function matchResultHandling($con,$pair_id,$result_1,$result_2)
{
	$successor_id = getSuccessorFromPair($con,$pair_id);
	$successor_result = getResultFromMatch($con,$pair_id);

	if(!empty($successor_result["result_team1"]) || ($successor_result["result_team1"] >= "0"))
	{
		return "ERR_MATCH_LOCKED";
	} else {
		if(($result_1 == "") || ($result_2 == ""))
		{
			return "ERR_NO_RESULT";
		} else {
			if($result_1 == $result_2)
			{
				return "ERR_NO_DRAW";
			} else {
				$sql = "UPDATE tm_paarung SET result_team1 = '$result_1', result_team2 = '$result_2' WHERE ID = '$pair_id'";
				if(mysqli_query($con,$sql))
				{
					$match_lock = date("Y-m-d H:i:s", strtotime("+10 minutes"));
					$sql = "UPDATE tm_paarung SET match_locked = '$match_lock' WHERE ID = '$pair_id'";
					if(mysqli_query($con,$sql))
					{
						$team_gamerslist = getGamerslistIdByPair($con,$pair_id);
						$team_1 = $team_gamerslist["team_1"];
						$team_2 = $team_gamerslist["team_2"];
						$second_pair = getSecondPairId($con,$pair_id,$successor_id);
						if($result_1 > $result_2)
						{
							return buildNextMatchUp($con,$pair_id,$second_pair,$successor_id,$team_1);  	                    
						} else {
							return buildNextMatchUp($con,$pair_id,$second_pair,$successor_id,$team_2);
						} 
					} else {
						return "ERR_DB";
					}
				} else {
					return "ERR_DB";
				}
			}
		}
	}
}

function buildNextMatchUp($con,$pair_id,$second_pair,$successor_id,$team)
{
	if($pair_id < $second_pair)
	{
		$sql = "UPDATE tm_paarung SET team_1 = '$team' WHERE ID = '$successor_id'";
		if(mysqli_query($con,$sql))
		{
			return "SUC_ENTER_RESULT";
		} else {
			return "ERR_DB";
		}
	} else {
		$sql = "UPDATE tm_paarung SET team_2 = '$team' WHERE ID = '$successor_id'";
		if(mysqli_query($con,$sql))
		{
			return "SUC_ENTER_RESULT";
		} else {
			return "ERR_DB";
		}
	}
}

?>