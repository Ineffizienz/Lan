<?php

require_once dirname(__DIR__).'../../include/init/get_parameters.php';

function buildOption($optionArr)
{
	$tpl = new template("admin/part/option.html");
	$tpl->assign_array($optionArr);

	return $tpl->r_display();
}

function buildJSONOutput($elements)
{
	if(is_array($elements))
	{
		$jsonOutput = json_encode(array("message" => array("messageText" => $elements[0]), "reloadProp" => array("parent_element" => $elements[1], "child_element" => $elements[2], "new_item" => $elements[3])));
	} else {
		$jsonOutput = json_encode(array("message" => array("messageText" => $elements)));
	}

	return $jsonOutput;
}

function translateGameMode($mode)
{
	switch ($mode) {
		case "1":
			$gameMode = "Mann gegen Mann";
			break;
		case "2":
			$gameMode = "2 gegen 2";
			break;
		case "3":
			$gameMode = "Teams";
			break;
	}

	return $gameMode;
}

function translateGameModeDetails($mode_details)
{
	switch ($mode_details) {
		case "1":
			$gameModeDetails = "Keine Details";
			break;
		case "2":
			$gameModeDetails = "zufällige Teams";
			break;
	}

	return $gameModeDetails;
}

function displayAchievements($con)
{
	$output = new template("admin/part/ac_list.html");
	$ac = new Achievement($con);
	$ac_array = array();
	$achievements = getAllAchievements($con);

	foreach ($achievements as $achievement)
	{
		$cat_array = array("category"=>buildOption($ac->getAcCategories()));
		$trig_array = array("trigger"=>buildOption($ac->getAcTrigger()));
		array_push($ac_array,array_merge($ac->getAdminAchievement($achievement),$cat_array,$trig_array));
	}

	$output->assign_array($ac_array);

	return $output->r_display();
}

function displayTeams($con) // Teamverwaltung --> Team löschen
{
	$all_teams = getAllTeams($con);

	if (empty($all_teams))
	{
		$output = "<p style='font-size:16pt;font-weight:bold;'>Keine Teams vorhanden</p>";
		return $output;
	} else {

		$output = buildOption($all_teams);

		return $output;
	}
}

function addUsername($con) // Achievementverwaltung --> Achievements zuweisen
{
	$ac = new Achievement($con);
	$output = new template("admin/part/ac_table_content.html");
	
	$userlist = getBasicUserData($con);

	$output->assign("user",buildOption($userlist));
	$output->assign("ac_name",buildOption($ac->getAllAchievementByName()));

	return $output->r_display();
}

function displayAcCategories($con)
{
	$ac = new Achievement($con);

	$selectable_categories = buildOption($ac->getAcCategories());

	return $selectable_categories;
}

function displayAcTrigger($con)
{
	$ac = new Achievement($con);

	$selectable_trigger = buildOption($ac->getAcTrigger());

	return $selectable_trigger;
}

function displayTicketStatus($con)
{
	$output = new template("admin/part/ticket_status_table.html");
	$player_ids = getAllUserIDs($con);
	$ticket_data = array();
	
	foreach ($player_ids as $id)
	{
		$player = new Player($con,$id);

		array_push($ticket_data,array("username" => $player->getPlayerUsername(),"ticket_active" => $player->getPlayerTicketActive()));
	}

	$output->assign_array($ticket_data);
	
	return $output->r_display();
}

function displaySingleGame($con)
{
	$output = new template("admin/part/game_table.html");
	$game_output = array();

	$game_data = getGameData($con);

	foreach ($game_data as $game)
	{
		if($game["has_table"] == "1")
		{
			$selected_option = array(array("id"=>"1","name"=>"Ja"),array("id"=>"0","name"=>"Nein"));
		} else {
			$selected_option = array(array("id"=>"0","name"=>"Nein"),array("id"=>"1","name"=>"Ja"));
		}

		$t_name = str_replace(" ", "", $game["name"]);
		$has_table = buildOption($selected_option);

		if(empty($game["icon"]))
		{
			$icon = "No Icon";
		} else {
			$icon = "<img src='images/game_icon/" . $game["icon"] . "' height='64'>";
		}

		if (empty($game["banner"]))
		{
			$banner = "No Banner";
		} else {
			$banner = "<img src='images/banner/" . $game["banner"] . "' height='64'>";
		}

		if(empty($game["addon"]))
		{
			$addon = buildOption(array(array("id"=>"NULL","name"=>"Keine Angaben"),array("id"=>"1","name"=>"Ja"),array("id"=>"0","name"=>"Nein")));
		} else {
			$addon = buildOption(array(array("id"=>"1","name"=>"Ja"),array("id"=>"0","name"=>"Nein")));
		}
		
		$transfer = array("id"=>$game["ID"],"name"=>$game["name"],"trimed_name"=>$t_name,"raw_name"=>$game["raw_name"],"addon"=>$addon,"icon"=>$icon,"banner"=>$banner,"has_table"=>$has_table);
		array_push($game_output,$transfer);

	}
	
	$output->assign_array($game_output);

	return $output->r_display();
}

function validateInput($new_game)
{
	if(isset($new_game) && ($new_game !== ""))
	{

		return true;

	} else {

		$message_code = "ERR_ADMIN_MISSING_GAME_NAME";
		return $message_code;

	}
}

function verifyKey($con, int $game_id, string $key)
{
	$result = mysqli_query($con, "SELECT ID FROM gamekeys WHERE (game_id = '$game_id') AND (gamekey = '$key');");
	if(mysqli_num_rows($result) > 0)
		return "ERR_KEY_EXISTS";
	else
		return true;
}

function verifyGame($con,$new_game,$new_raw_name)
{
	$games = getGames($con);
	$raw_name = getRawName($con);

	if (in_array($new_game, $games) && in_array($new_raw_name,$raw_name))
	{
		return true;
	} else {
		return false;
	}
}

function emptyText($data)
{
	if (empty($data) || ($data == "") || ($data == 0))
	{
		$text = "-";
	} else {
		$text = $data;
	}

	return $text;
}

function createGame($con,$new_game,$new_raw_name)
{
    mysqli_query($con,"INSERT INTO games (name,raw_name) VALUES ('$new_game','$new_raw_name');");
}

function displayTmGames($con)
{
	$games = getFullGameData($con);

	if (empty($games))
	{
		$output = "<option>Keine Spiele vorhanden";
	} else {
		$output = buildOption($games);
	}

	return $output;
}

function displayTournaments($con)
{
	$tournaments = getTournaments($con);
	$tournament_array = array();

	foreach ($tournaments as $tournament)
	{
		$tpl = new template("admin/part/tm_table.html");
		$game_name = getGameInfoById($con,$tournament["game_id"]);
		$tm_period = getTournamentPeriod($con,$tournament["tm_period_id"]);

		$game_mode = translateGameMode($tournament["mode"]);
		$game_mode_details = translateGameModeDetails($tournament["mode_details"]);

		if(strtotime($tm_period["time_from"]) < time())
		{
			$startbutton = "<button class='start_tm' name='" . $tournament["ID"] . "' disabled>Turnier starten</button>";
		} else {
			$startbutton = "<button class='start_tm' name='" . $tournament["ID"] . "'>Turnier starten</button>";
		}

		$line = array("id"=>$tournament["ID"],"game"=>$game_name["name"],"mode"=>$game_mode,"mode_details"=>$game_mode_details,"time_from"=>$tm_period["time_from"],"time_to"=>$tm_period["time_to"],"participants"=>$tournament["player_count"],"startbutton"=>$startbutton);
		array_push($tournament_array,$line);
	}
	$tpl->assign_array($tournament_array);

	return $tpl->r_display();
}

function displayVotedTournaments($con)
{
	$voted_tm = getVotedTournaments($con);
	$votes = array();

	foreach ($voted_tm as $tournament)
	{
		$tpl = new template("admin/part/voted_tm_tpl.html");

		if($tournament["vote_closed"] == "0")
		{
			$closed = "Nein";
		} else {
			$closed = "Ja";
		}

		$vote = array("game_id"=>$tournament["game_id"],"game_name"=>$tournament["name"],"starttime"=>$tournament["starttime"],"endtime"=>$tournament["endtime"],"votes"=>$tournament["vote_count"],"closed"=>$closed,"vote_id"=>$tournament["ID"]);
		array_push($votes,$vote);
	}

	if(empty($votes))
	{
		return "Es sind bisher keine Votes vorhanden.";
	} else {
		$tpl->assign_array($votes);
		return $tpl->r_display();
	}
}

function displayDefineTmPopup($con)
{
	$part = file_get_contents(TMP . "admin/part/popup/define_tm_popup.html");

	return $part;
}

function handlingWildcard($con,$tm_id,$pair_count,$stage,$next_stage)
{
	$wildcard_player = getTournamentWildcardPlayer($con,$tm_id,$stage);

	if(!empty($wildcard_player))
	{
		if(!($pair_count <= 2))
		{
			$last_stage_pair = getTournamentLastPairFromStage($con,$tm_id,$next_stage);
			
			if(!(($pair_count % 2) == 0))
			{
				$sql = "UPDATE tm_paarung SET team_2 = '-1' WHERE ID = '$last_stage_pair'";
				if(mysqli_query($con,$sql))
				{
					$sql = "UPDATE tm_paarung SET team_1 = '$wildcard_player' WHERE ID = '$last_stage_pair'";
					mysqli_query($con,$sql);
					return true;
				}
			} else {
				$sql = "UPDATE tm_paarung SET team_2 = '$wildcard_player' WHERE ID = '$last_stage_pair'";
				mysqli_query($con,$sql);
				return false;
			}
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function setUpNewTournament($con,$vote_id,$game_id,$tm_from,$tm_to,$mode,$mode_details)
{
	
	$tm_from = date("Y-m-d H:i:s", strtotime($tm_from));
	$tm_to = date("Y-m-d H:i:s", strtotime($tm_to));
	
	$sql = "INSERT INTO tm_period (time_from,time_to) VALUES ('$tm_from','$tm_to')";
	if(mysqli_query($con,$sql))
	{
		$tm_period_id = getTournamentPeriodId($con);
		$end_register = date("Y-m-d H:i:s", strtotime("+30 minutes"));
		
		if($vote_id == '0')
		{
			$sql = "INSERT INTO tm (game_id,mode,mode_details,player_count,tm_period_id,tm_end_register,tm_locked,lan_id) VALUES ('$game_id','$mode','$mode_details','0','$tm_period_id','$end_register','0','0')";
			if(mysqli_query($con,$sql))
			{
				return "SUC_ADMIN_CREATE_TM";
			} else {
				return "ERR_ADMIN_DB";
			}
		} else {
			$vote_count = getVotedPlayers($con,$vote_id);

			$sql = "INSERT INTO tm (game_id,mode,mode_details,player_count,tm_period_id,tm_end_register,tm_locked,lan_id) VALUES ('$game_id','$mode','$mode_details','$vote_count','$tm_period_id','$end_register','0','0')";
            if(mysqli_query($con,$sql))
            {
                $tm_id = getLastTmId($con);
                $player_ids = getPlayerIdsFromVote($con,$vote_id);
                
                foreach ($player_ids as $player_id)
                {
					$sql = "INSERT INTO tm_gamerslist (tm_id, player_id) VALUES ('$tm_id','$player_id')";
					if(!mysqli_query($con,$sql))
					{
						return "ERR_ADMIN_DB";
					}
                }

                $sql = "DELETE FROM tm_vote_player WHERE tm_vote_id = '$vote_id'";
                if(mysqli_query($con,$sql))
                {
                    $sql = "DELETE FROM tm_vote WHERE ID = '$vote_id'";
                    if(mysqli_query($con,$sql))
                    {
                        $sql = "CREATE EVENT IF NOT EXISTS start_tm" . $tm_id . " ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 30 MINUTE ENABLE DO UPDATE tm SET tm_locked = 1 WHERE ID = '$tm_id'";
                        if(mysqli_query($con,$sql))
                        {
                            return "SUC_ADMIN_CREATE_TM_FROM_VOTE";
                        } else {
                            return "ERR_ADMIN_DB";
                        }
                    } else {
                        return "ERR_ADMIN_DB";
                    }
                } else {
                    return "ERR_ADMIN_DB";
                }
            } else {
                return "ERR_ADMIN_DB";
            }
		}
	}
}

function archivTmPaarung($con,$tm_id)
{
	$pairs = getAllPairsFromTournament($con,$tm_id);

	foreach ($pairs as $pair)
	{
		$pair_id = $pair["ID"];
		$team_1 = $pair["team_1"];
		$team_2 = $pair["team_2"];
		$stage = $pair["stage"];
		$successor = $pair["successor"];
		$result_team1 = $pair["result_team1"];
		$result_team2 = $pair["result_team2"];

		$sql = "INSERT INTO archiv_tm_paarung VALUES ('$pair_id','$team_1','$team_2','$stage','$tm_id','$successor','$result_team1','$result_team2')";
		if(!(mysqli_query($con,$sql)))
		{
			break;
			return false;
		} else {
			$sql = "DELETE FROM tm_paarung WHERE ID = '$pair_id'";
			if(!(mysqli_query($con,$sql)))
			{
				break;
				return false;
			}
		}
	}

	return true;
}

function archivTmGamerslist($con,$tm_id)
{
	$gamerslist_data = getAllGamerslistDataFromTournament($con,$tm_id);

	foreach ($gamerslist_data as $player)
	{
		$gl_id = $player["ID"];
		$player_id = $player["player_id"];

		$sql = "INSERT INTO archiv_tm_gamerslist VALUES ('$gl_id','$tm_id','$player_id')";
		if(!(mysqli_query($con,$sql)))
		{
			break;
			return false;
		} else {
			$sql = "DELETE FROM tm_gamerslist WHERE ID = '$gl_id'";
			if(!(mysqli_query($con,$sql)))
			{
				break;
				return false;
			}
		}
		
	}

	return true;
}

function archivTmPeriod($con,$period_id)
{
	$period = getTournamentRawPeriod($con,$period_id);

	$time_from = $period["time_from"];
	$time_to = $period["time_to"];

	$sql = "INSERT INTO archiv_tm_period VALUES ('$period_id','$time_from','$time_to')";
	if(!(mysqli_query($con,$sql)))
	{
		return false;
	} else {
		$sql = "DELETE FROM tm_period WHERE ID = '$period_id'";
		if(!(mysqli_query($con,$sql)))
		{
			return false;
		}
	}

	return true;
}

function displayLans($con)
{
	$lans = getLans($con);
	$lan_array = array();

	foreach ($lans as $lan)
	{
		$tpl = new template("admin/part/lan_table.html");

		$single_lan = array("id"=>$lan["ID"],"lan_title"=>$lan["title"],"lan_from"=>$lan["date_from"],"lan_to"=>$lan["date_to"]);
		array_push($lan_array,$single_lan);
	}

	if(empty($lan_array))
	{
		return "Es sind bisher keine Lans angelegt worden.";
	} else {
		$tpl->assign_array($lan_array);
		return $tpl->r_display();
	}
}

?>