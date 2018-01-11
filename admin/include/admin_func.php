<?php

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
		if (empty($ticket_status["ticket_active"]))
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
	print_r($ac_categories);

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