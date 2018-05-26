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

function displayAchievements($con)
{

	$achievements = getAllAchievements($con);

	$all_categories = getAchievementCategories($con);
	$all_trigger = getAchievementTrigger($con);
	$all_visib = buildVisibilityOption($con);

	foreach ($achievements as $achievement)
	{
		$ac = new Achievement;
		
		$ac->getAdminDetails($achievement,$all_categories,$all_trigger,$all_visib);

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

		if(!isset($output))
		{
			$output = str_replace(array("--ID--","--NAME--","--RAW_NAME--","--ICON--","--HAS_TABLE--"), array($game["ID"],$game["name"],$game["raw_name"],$icon,$has_table),$singleGame_template);
		} else {
			$output .= str_replace(array("--ID--","--NAME--","--RAW_NAME--","--ICON--","--HAS_TABLE--"), array($game["ID"],$game["name"],$game["raw_name"],$icon,$has_table),$singleGame_template);
		}
	}

	return $output;
}

function buildVisibilityOption($con)
{
	$ac_visib = getAchievementVisibility($con);

	$visib_name = array();

	foreach ($ac_visib as $visib_id)
	{
		if(!empty($visib_id))
		{
			if($visib_id == "1")
			{
				$combine = array("ID" => $visib_id, "name" => "Sichtbar");
			} else {
				$combine = array("ID" => $visib_id, "name" => "Unsichtbar");
			}
		}

		if(!in_array($combine,$visib_name))
		{
			array_push($visib_name,$combine);
		}
	}

	return $visib_name;
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

function verifyKey($con,$raw_name,$key)
{
		
	$all_keys = getAllKeys($con,$raw_name);
	if(in_array($key,$all_keys))
	{
		$message_code = "ERR_KEY_EXISTS";
		return $message_code;
	} else {
		return true;
	}

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
		if($filesize > 500000)
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

	mysqli_query($con,"CREATE TABLE $new_raw_name (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, game_key VARCHAR(255) NULL, player_id INT(11) NULL)");
    mysqli_query($con,"INSERT INTO games (name,raw_name) VALUES ('$new_game','$new_raw_name')");

}

?>