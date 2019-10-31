<?php

function buildContent($file) // liest HTML-Fragmente ein und fügt sie an der entsprechenden Stelle ein
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

function buildOption($optionArr)
{
	foreach ($optionArr as $option)
	{
		// get first key = $id, get new first key = $name --> Wie heißt die Funktion dafür?
		$part = file_get_contents("template/admin/part/option.html");

		if(array_key_exists("title",$option))
		{
			$key = "title";
		} elseif (array_key_exists("name",$option)) {
			$key = "name";
		}

		if(!isset($output_option))
		{
			$output_option = str_replace(array("--VALUE--","--NAME--"),array($option["ID"],$option[$key]),$part);
		} else {
			$output_option .= str_replace(array("--VALUE--","--NAME--"),array($option["ID"],$option[$key]),$part);
		}
	}

	return $output_option;
}

function buildJSONOutput($elements)
{
	if(is_array($elements))
	{
		$jsonOutput = json_encode(array("message" => $elements[0], "parent_element" => $elements[1], "child_element" => $elements[2]));
	} else {
		$jsonOutput = json_encode(array("message" => $elements));
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

	$achievements = getAllAchievements($con);

	$all_categories = getAchievementCategories($con);
	$all_trigger = getAchievementTrigger($con);
	//$all_visib = buildVisibilityOption($con);

	foreach ($achievements as $achievement)
	{
		$ac = new Achievement;
		
		$ac->getAdminDetails($con,$achievement,$all_categories,$all_trigger);

		if(!isset($output))
		{
			$output = $ac->displayAchievement();
		} else {
			$output .= $ac->displayAchievement();
		}
		
	}

	return $output;
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
	$userlist = getBasicUserData($con);
	$ac_option = getAllAchievementByName($con);
	
	$selectable_option = buildOption($ac_option);
	$selectable_user = buildOption($userlist);

	$tm_part = file_get_contents("template/admin/part/ac_table_content.html");
	
	$output = str_replace(array("--USER--","--AC_NAME--"),array($selectable_user,$selectable_option),$tm_part);

	return $output;
}

function displayAcCategories($con)
{
	$categories  = getAchievementCategories($con);

	$selectable_categories = buildOption($categories);

	return $selectable_categories;
}

function displayAcTrigger($con)
{
	$trigger = getAchievementTrigger($con);

	$selectable_trigger = buildOption($trigger);

	return $selectable_trigger;
}

function displayTicketStatus($con)
{
	$ticket_status = getUserTicketRelation($con);
	
	foreach ($ticket_status as $status)
	{
		if (empty($status["ticket_active"]))
		{
			$param = "Inaktiv";
		} else {
			$param = "Aktiv";
		}
		
		$table_template = file_get_contents("template/part/basic_table.html");
		
		if(!isset($output))
		{
			$output = str_replace(array("--VALUE_1--","--VALUE_2--"),array($status["name"],$param),$table_template);
		} else {
			$output .= str_replace(array("--VALUE_1--","--VALUE_2--"),array($status["name"],$param),$table_template);
		}
	}
	
	return $output;
}

function displaySingleGame($con)
{
	$game_data = getGameData($con);

	foreach ($game_data as $game)
	{
		$singleGame_template = file_get_contents("template/admin/part/game_table.html");

		if($game["has_table"] == "1")
		{
			$selected_option = array(array("ID"=>"1","name"=>"Ja"),array("ID"=>"0","name"=>"Nein"));
		} else {
			$selected_option = array(array("ID"=>"0","name"=>"Nein"),array("ID"=>"1","name"=>"Ja"));
		}

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
			$addon = buildOption(array(array("ID"=>"NULL","name"=>"Keine Angaben"),array("ID"=>"1","name"=>"Ja"),array("ID"=>"0","name"=>"Nein")));
		} else {
			$addon = buildOption(array(array("ID"=>"1","name"=>"Ja"),array("ID"=>"0","name"=>"Nein")));
		}

		if(!isset($output))
		{
			$output = str_replace(array("--ID--","--NAME--","--RAW_NAME--","--ADDON--","--ICON--","--BANNER--","--HAS_TABLE--"), array($game["ID"],$game["name"],$game["raw_name"],$addon,$icon,$banner,$has_table),$singleGame_template);
		} else {
			$output .= str_replace(array("--ID--","--NAME--","--RAW_NAME--","--ADDON--","--ICON--","--BANNER--","--HAS_TABLE--"), array($game["ID"],$game["name"],$game["raw_name"],$addon,$icon,$banner,$has_table),$singleGame_template);
		}
	}

	return $output;
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
	$result = mysqli_query($con, "SELECT ID FROM gamekeys WHERE (gamekeys.game_id = '$game_id') AND (gamekey = $key);");
	if(mysql_num_rows($result) > 0)
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

function validateImageFile($filesize,$filetype)
{
	if(isset($filesize) && ($filesize != 0))
	{
		if($filesize > 5000000)
		{
			return "ERR_ADMIN_FILE_TO_HUGE";
		} else {
			if(($filetype !== "jpg") && ($filetype !== "png") && ($filetype !== "jpeg") && ($filetype !== "gif"))
			{
				return "ERR_ADMIN_NO_IMAGE_TYPE";
			} else {
				return "1";
			}
		}
	} else {
		return "ERR_ADMIN_NO_IMAGE";
	}
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

/*function displayTournaments($con)
{
	$tournaments = getTournaments($con);

	foreach ($tournaments as $tournament)
	{
		$game_name = getGameInfoById($con,$tournament["game"]);

		$part = file_get_contents(TMP . "admin/part/tm_table.html");

		if(empty($tournament["player_count"]))
		{
			$player_count = 0;
		} else {
			$player_count = $tournament["player_count"];
		}

		$game_mode = translateGameMode($tournament["mode"]);
		$game_mode_details = translateGameModeDetails($tournament["mode_details"]);

		if(strtotime($tournament["starttime"]) < time())
		{
			$startbutton = "<button class='start_tm' name='" . $tournament["ID"] . "' disabled>Turnier starten</button>";
		} else {
			$startbutton = "<button class='start_tm' name='" . $tournament["ID"] . "'>Turnier starten</button>";
		}

		if(!isset($output))
		{
			$output = str_replace(array("--ID--","--GAME--","--MODE--","--MODE_DETAILS--","--TIME--","--PARTICIPANTS--","--STARTBUTTON--"),array($tournament["ID"],$game_name[0]["name"],$game_mode,$game_mode_details,$tournament["starttime"],$player_count,$startbutton),$part);
		} else {
			$output .= str_replace(array("--ID--","--GAME--","--MODE--","--MODE_DETAILS--","--TIME--","--PARTICIPANTS--","--STARTBUTTON--"),array($tournament["ID"],$game_name[0]["name"],$game_mode,$game_mode_details,$tournament["starttime"],$player_count,$startbutton),$part);
		}
	}

	return $output;
}*/

function displayVotedTournaments($con)
{
	$voted_tm = getVotedTournaments($con);

	$part = file_get_contents(TMP . "admin/part/voted_tm_tpl.html");

	foreach ($voted_tm as $tournament)
	{
		$game_name = getGameInfoById($con,$tournament["game_id"]);

		if($tournament["vote_closed"] == "0")
		{
			$closed = "Nein";
		} else {
			$closed = "Ja";
		}

		if(!isset($output))
		{
			$output = str_replace(array("--GAME_ID--","--GAME_NAME--","--STARTTIME--","--ENDTIME--","--VOTES--","--CLOSED--","--VOTE_ID--"),array($tournament["game_id"],$game_name["name"],$tournament["starttime"],$tournament["endtime"],$tournament["vote_count"],$closed,$tournament["ID"]),$part);
		} else {
			$output .= str_replace(array("--GAME_ID--","--GAME_NAME--","--STARTTIME--","--ENDTIME--","--VOTES--","--CLOSED--","--VOTE_ID--"),array($tournament["game_id"],$game_name["name"],$tournament["starttime"],$tournament["endtime"],$tournament["vote_count"],$closed,$tournament["ID"]),$part);
		}
	}

	return $output;

}

function displayDefineTmPopup($con)
{
	$part = file_get_contents(TMP . "admin/part/popup/define_tm_popup.html");

	return $part;
}

?>