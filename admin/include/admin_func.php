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

	foreach ($achievements as $achievement)
	{
		$ac = new Achievement;
		
		$ac->getAdminDetails($achievement);
		
		if(!isset($output))
		{
			$output = $ac->displayAchievement();
		} else {
			$output .= $ac->displayAchievement();
		}
		
	}

	return $output;
}

function displayTeams($con)
{
	$all_teams = getAllTeams($con);

	if (empty($all_teams))
	{
		$output = "<p style='font-size:16pt;font-weight:bold;'>Keine Teams vorhanden</p>";
		return $output;
	} else {
		$output = "<select class='select' id='del_team'>";
		foreach ($all_teams as $team)
		{
			$single_team = file_get_contents(ROOT . "template/admin/single_team.html");
			$output .= str_replace(array("--Teamname--", "--ID--"), array($team["name"],$team["ID"]), $single_team);
		}
		$output .= "</select>";
		$output .= "<button id='b_del_team'>Team löschen</button>";

		return $output;
	}
}

function addUsername($con)
{
	$userlist = getBasicUserData($con);
	$ac_option = getAllAchievementByName($con);
	
	$selectable_option = buildOption($ac_option);
	$selectable_user = buildOption($userlist);

	$tm_part = file_get_contents("template/admin/part/ac_table_content.html");
	
	$output = str_replace(array("--USER--","--AC_NAME--"),array($selectable_user,$selectable_option),$tm_part);

	return $output;
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

function displayCategories($con)
{
	$ac_categories = getAchievementCategories($con);

	$categories = buildOption($ac_categories);

	return $categories;
}

function displayTrigger($con)
{
	$ac_trigger = getAchievementTrigger($con);

	$triggers = buildOption($ac_trigger);

	return $triggers;
}

function validateInput($new_game)
{
	if(isset($new_game) && ($new_game !== ""))
	{

		return true;

	} else {

		$message_code = "ERR_MISSING_GAME_NAME";
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

function createGame($con,$new_game,$new_raw_name)
{

	mysqli_query($con,"CREATE TABLE $new_raw_name (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, game_key VARCHAR(255) NULL, player_id INT(11) NULL)");
    mysqli_query($con,"INSERT INTO games (name,raw_name) VALUES ('$new_game','$new_raw_name')");

}

?>