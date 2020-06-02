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

	/**
	 * 
	 * @param mysqli $con
	 * @param string $nick
	 * @param string $real_name
	 * @param int $player_id
	 * @return boolean true on success
	 */
	function initializePlayer(mysqli $con, string $nick, string $real_name, int $player_id)
	{
		$sql_user = "UPDATE player SET name='$nick', real_name='$real_name' WHERE ID='$player_id'";
		if (mysqli_query($con,$sql_user))
		{
			$sql_fl_check = "SELECT first_login FROM player WHERE ID='$player_id';";
			if(mysqli_fetch_assoc(mysqli_query($con, $sql_fl_check))['first_login'] != '0')
			{
				$sql_fl = "UPDATE player SET first_login = '0' WHERE ID='$player_id'";
				if(mysqli_query($con,$sql_fl))
				{
					$sql_status = "INSERT INTO status (user_id,status) VALUES ('$player_id','1')";
					if(mysqli_query($con,$sql_status))
					{
						return true;
					}
				}
			}
			else
				return true;
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
	
	function getUserRelatedStatusColor($con,$player_id)
	{
		$status = getStatus($con,$player_id);
		$status_color = getStatusColor($con,$status["id"]);

		$circle = "<div id='status_circle' style='background-color:" . $status_color . ";'>&nbsp;</div>";

		return $circle;
	}

	function getUserStatusOption($con,$player_id)
	{
		$user_status = getStatus($con,$player_id);
		$status_data = getStatusData($con, $user_status["id"]);

		$option = build_option_new($status_data);
		
		return "<option value='" . $user_status["id"] . "' selected>" . $user_status["status_name"] . $option;

	}

	function generateGameKey($con, int $player_id, int $game_id)
	{
		$key = getPlayerGameKey($con, $player_id, $game_id);

		if ($key === false)
		{
			$first_key = getGameKey($con, $game_id);

			if($first_key === false)
			{
				$message_code = "ERR_NO_KEY";
				return $message_code;
			} else {
				mysqli_query($con,"UPDATE gamekeys SET player_id = '$player_id' WHERE (gamekey = '$first_key') AND (game_id = '$game_id');");
				return $first_key;
			}
		} else {
			return $key;
		} 
	}

	function displayProfilImage(mysqli $con, $player_id): template
	{
		$image_path = getUserImage($con,$player_id);

		if (empty($image_path))
			return new template("part/empty_image.html");
		else {
			$tpl = new template("part/profil_image.html");
			return $tpl->assign('image_path', $image_path);
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
			return "<i>Du hast deine Präferenzen noch nicht festgelegt.</i>";
		} else {
			
			$tpl = new template("part/single_pref.html");
			$tpl->assign_array($player_pref);

			return $tpl->r_display();
		}
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
			$tpl->assign("player_wow_account",ucfirst(strtolower($wow_account)));
			$template = $tpl->r_display();
			return $template;
		} else {
			$tpl = new template();
			$tpl->load("wow_server/characters_table.html");
			$output = "";
			$character_list = array();

			### This Sub-Template defines each row for a character and moves it to an array
				$output = new template();
				$output->load("part/characters_row.html");

				foreach($wow_account_chars as $chars)
				{
					$race = defineRace($chars["race"]);
					$class = defineClass($chars["class"]);
					$loc = defineLocation($chars["map"]);

					$character = array("name" => $chars["name"], "race" => $race, "class" => $class, "level" => $chars["level"], "location" => $loc);
					array_push($character_list,$character);
				}
				$output->assign_array($character_list);
			
			$tpl->assign_subtemplate("characters",$output);
			$tpl->assign("player_wow_account",ucfirst(strtolower($wow_account)));
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
	$games = getMainGameData($con);
	
	$tpl = new template("part/option.html");
	$tpl->assign_array($games);

	return $tpl->r_display();

}

function displayRunningVotes($con)
{
	$votes = getVotedTournamentsUser($con);

	$tpl = new template("part/running_vote.html");
	$tpl->assign_array($votes);

	return $tpl->r_display();

	// Rework required
	/*if(empty($votes))
	{
		$output = "Es gibt gegenwärtig keine Abstimmungen.";
	}
	return $output;*/
}

function displayTournaments($con)
{
	$tpl = new template("part/overview_tournament.html");

	$tournaments = getTournamentsOverview($con);

	$tpl->assign_array($tournaments);

	return $tpl->r_display();

}

function displayTournamentParticipants($con,$tm_id)
{
	$tpl = new template("part/unlocked_tm.html");
	
	$tm_player = getPlayerFromGamerslist($con,$tm_id);
	$tm_banner = getTmBanner($con,$tm_id);

	$player_list = implode(", ",$tm_player);

	$tpl->assign("player_list",$player_list);
	$tpl->assign("tm_banner",$tm_banner);
	
	return $tpl->r_display();
}

function displayTournamentLocked($con,$tm_id)
{
	$stages = getStages($con,$tm_id);
	
	$part = file_get_contents("template/part/locked_tm.html");
	$part_stages = file_get_contents("template/part/tm_section.html");

	foreach ($stages as $stage)
	{
		$part_pair = file_get_contents("template/part/player_pair.html");
		$pairs_by_stages = getPairsByStages($con,$tm_id,$stage);

		$pair_output ="";
		foreach ($pairs_by_stages as $pair)
		{
			$pair_id = $pair["ID"];
			$player_1 = $pair["team_1"];
			$player_2 = $pair["team_2"];

			$successor = getSuccessorFromPair($con,$pair_id);

			$player_1 = getUsernameFromGamerslist($con,$player_1);
			if((getSuccessorCount($con,$successor) == 1) || (($stage == "1") && empty($player_2)))
			{
				$player_2 = "<i>Wildcard</i>";
			} else {
				$player_2 = getUsernameFromGamerslist($con,$player_2);
			}

			$matches_id = getSingleMatchesIdFromPaarung($con,$pair_id);
			$match_id = getMatchIdFromMatches($con,$matches_id);
			$result_p1 = getResultP1FromMatch($con,$match_id);
			$result_p2 = getResultP2FromMatch($con,$match_id);

			if($result_p1 == "")
			{
				$result_p1 = "";
			}

			if($result_p2 == "")
			{
				$result_p2 = "";
			}

			if(!isset($pair_output))
			{
				$pair_output = str_replace(array("--TM_ID--","--PAIR_ID--","--PLAYER_1--","--PLAYER_2--","--RESULT_P1--","--RESULT_P2--"),array($tm_id,$pair_id,$player_1,$player_2,$result_p1,$result_p2),$part_pair);
			} else {
				$pair_output .= str_replace(array("--TM_ID--","--PAIR_ID--","--PLAYER_1--","--PLAYER_2--","--RESULT_P1--","--RESULT_P2--"),array($tm_id,$pair_id,$player_1,$player_2,$result_p1,$result_p2),$part_pair);
			}
		}

		if(!isset($output_stage))
		{
			$output_stage = str_replace("--PLAYER_PAIR--",$pair_output,$part_stages);
		} else {
			$output_stage .= str_replace("--PLAYER_PAIR--",$pair_output,$part_stages);
		}
	}

	$tm_game = getSingleTournamentGame($con,$tm_id);
	$tm_banner = getGameBanner($con,$tm_game);

	$output = str_replace(array("--SECTION--","--BANNER--"),array($output_stage,$tm_banner),$part);

	return $output;
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

	return $tpl->r_display();;
}

function matchResultHandling($con,$pair_id,$matches_id,$match_id,$result_1,$result_2)
{
	$successor_id = getSuccessorFromPair($con,$pair_id);
	$successor_matches = getSingleMatchesIdFromPaarung($con,$successor_id);
	$successor_match = getMatchIdFromMatches($con,$successor_matches);
	$successor_result = getResultP1FromMatch($con,$successor_match);

	if(!empty($successor_result) || ($successor_result >= "0"))
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
				$sql = "UPDATE tm_match SET result_team1 = '$result_1', result_team2 = '$result_2' WHERE ID = '$match_id'";
				if(mysqli_query($con,$sql))
				{
					$match_lock = date("Y-m-d H:i:s", strtotime("+10 minutes"));
					$sql = "UPDATE tm_matches SET match_locked = '$match_lock' WHERE match_id = '$match_id'";
					if(mysqli_query($con,$sql))
					{
						$team_gamerslist = getGamerslistIdByPair($con,$pair_id);
						$team_1 = $team_gamerslist["team_1"];
						$team_2 = $team_gamerslist["team_2"];
						$second_pair = getSecondPairId($con,$pair_id,$successor_id);
						if($result_1 > $result_2)
						{
							if($pair_id < $second_pair)
							{
								$sql = "UPDATE tm_paarung SET team_1 = '$team_1' WHERE ID = '$successor_id'";
								if(mysqli_query($con,$sql))
								{
									if(getSuccessorCount($con,$successor_id) == 1)
									{
										$matches_id = getSingleMatchesIdFromPaarung($con,$successor_id);
										$sql = "UPDATE tm_paarung SET matches_id = NULL WHERE ID = '$successor_id'";
										if(mysqli_query($con,$sql))
										{
											$match_id = getMatchIdFromMatches($con,$matches_id);
											$sql = "DELETE FROM tm_matches WHERE ID = '$matches_id'";
											if(mysqli_query($con,$sql))
											{
												$sql = "DELETE FROM tm_match WHERE ID = '$match_id'";
												if(mysqli_query($con,$sql))
												{
													return "SUC_ENTER_RESULT";
												} else {
													return "ERR_DB";
												}
											} else {
												return "ERR_DB";
											}
										} else {
											return "ERR_DB";
										}
									} else {
										return "SUC_ENTER_RESULT";
									}
								} else {
									return "ERR_DB";
								}
							} else {
								$sql = "UPDATE tm_paarung SET team_2 = '$team_1' WHERE ID = '$successor_id'";
								if(mysqli_query($con,$sql))
								{
									if(getSuccessorCount($con,$successor_id) == 1)
									{
										$matches_id = getSingleMatchesIdFromPaarung($con,$successor_id);
										$sql = "UPDATE tm_paarung SET matches_id = NULL WHERE ID = '$successor_id'";
										if(mysqli_query($con,$sql))
										{
											$match_id = getMatchIdFromMatches($con,$matches_id);
											$sql = "DELETE FROM tm_matches WHERE ID = '$matches_id'";
											if(mysqli_query($con,$sql))
											{
												$sql = "DELETE FROM tm_match WHERE ID = '$match_id'";
												if(mysqli_query($con,$sql))
												{
													return "SUC_ENTER_RESULT";
												} else {
													return "ERR_DB";
												}
											} else {
												return "ERR_DB";
											}
										} else {
											return "ERR_DB";
										}
									} else {
										return "SUC_ENTER_RESULT";
									} 
								}
							}       	                    
						} else {
							if($pair_id < $second_pair)
							{
								$sql = "UPDATE tm_paarung SET team_1 = '$team_2' WHERE ID = '$successor_id'";
								if(mysqli_query($con,$sql))
								{
									if(getSuccessorCount($con,$successor_id) == 1)
									{
										$matches_id = getSingleMatchesIdFromPaarung($con,$successor_id);
										$sql = "UPDATE tm_paarung SET matches_id = NULL WHERE ID = '$successor_id'";
										if(mysqli_query($con,$sql))
										{
											$match_id = getMatchIdFromMatches($con,$matches_id);
											$sql = "DELETE FROM tm_matches WHERE ID = '$matches_id'";
											if(mysqli_query($con,$sql))
											{
												$sql = "DELETE FROM tm_match WHERE ID = '$match_id'";
												if(mysqli_query($con,$sql))
												{
													return "SUC_ENTER_RESULT";
												} else {
													return "ERR_DB";
												}
											} else {
												return "ERR_DB";
											}
										} else {
											return "ERR_DB";
										}
									} else {
										return "SUC_ENTER_RESULT";
									}
								} else {
									return "ERR_DB";
								}
							} else {
								$sql = "UPDATE tm_paarung SET team_2 = '$team_2' WHERE ID = '$successor_id'";
								if(mysqli_query($con,$sql))
								{
									if(getSuccessorCount($con,$successor_id) == 1)
									{
										$matches_id = getSingleMatchesIdFromPaarung($con,$successor_id);
										$sql = "UPDATE tm_paarung SET matches_id = NULL WHERE ID = '$successor_id'";
										if(mysqli_query($con,$sql))
										{
											$match_id = getMatchIdFromMatches($con,$matches_id);
											$sql = "DELETE FROM tm_matches WHERE ID = '$matches_id'";
											if(mysqli_query($con,$sql))
											{
												$sql = "DELETE FROM tm_match WHERE ID = '$match_id'";
												if(mysqli_query($con,$sql))
												{
													return "SUC_ENTER_RESULT";
												} else {
													return "ERR_DB";
												}
											} else {
												return "ERR_DB";
											}
										} else {
											return "ERR_DB";
										}
									} else {
										return "SUC_ENTER_RESULT";
									}
								} else {
									return "ERR_DB";
								}
							}
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

?>