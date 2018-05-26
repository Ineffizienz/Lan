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
		$output_option = "<option name='" . $selected["id"] . "' selected>" . $selected["name"];

		$part = file_get_contents("template/part/option.html");

		foreach ($optionArr as $option)
		{
			if($option["id"] !== $selected["id"])
			{
				$output_option .= str_replace(array("--VALUE--","--NAME--"),array($option["id"],utf8_encode($option["name"])),$part);	
			}
		}
		
		return $output_option;	
	}

	function initializePlayer($con,$username,$ip)
	{
		$user_id = getUserId($con,$ip);

		$sql_user = "UPDATE player SET name='$username' WHERE ip='$ip'";
		if (mysqli_query($con,$sql_user))
		{
			$sql_fl = "UPDATE player SET first_login = '0' WHERE ID='$user_id'";
			if(mysqli_query($con,$sql_fl))
			{
				$sql_status = "INSERT INTO status (user_id,status) VALUES ('$user_id','1')";
				if(mysqli_query($con,$sql_status))
				{
					$sql_ac = "ALTER TABLE ac_player ADD `$user_id` INT(11) NULL";
					if(mysqli_query($con,$sql_ac))
					{
						$param = "1";
					} else {
						$param = "0";
					}
				} else {
					$param = "0";
				}
			} else {
				$param = "0";
			}
		} else {
			$param = "0";
		}

		return $param;
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
	
	function ownTeam($con,$ip)
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
	function teamMembers($con,$ip) //Gibt die eigenen Teammitglieder aus
	{
		$team_id = getTeamId($con,$ip);  // beziehen der eigenen Team-ID
		$team_members = getTeamMembers($con,$ip,$team_id);

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
	function getUserRelatedStatusColor($con,$ip)
	{
		$user_id = getUserId($con,$ip);
		$status = getStatus($con,$user_id);
		$status_color = getStatusColor($con,$status);

		$circle = "<div id='status_circle' style='background-color:" . $status_color . ";'>&nbsp;</div>";

		return $circle;
	}

	function getUserStatusOption($con,$ip)
	{
		$user_id = getUserId($con,$ip);
		$status_data = getStatusData($con);
		$user_status = getStatus($con,$user_id);
		$status_name = getStatusName($con,$user_status);

		
		$selected = array("id"=>$user_status,"name"=>utf8_encode($status_name));
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

	function displayProfilImage($con,$ip)
	{

		$profil_image = getUserImage($con,$ip);

		if (empty($profil_image))
		{
			return build_content("part/empty_image.html");
		} else {
			$image_template = file_get_contents("template/part/profil_image.html");

			$output = str_replace("--IMAGE_PATH--",$profil_image,$image_template);

			return $output;
		}
		
	}
	
	function displaySinglePlayerTeam($con,$ip)
	{
		$team_id = getTeamId($con,$ip);
		
		if(empty($team_id))
		{
			$teamname = getSinglePlayerTeam($con,$ip);
			return $teamname;
		} else {
			$user_id = getUserId($con,$ip);
			$teamname = getSinglePlayerTeam($con,$ip);
			$player_team = file_get_contents("template/part/player_team.html");
			$output = str_replace(array("--TEAM--","--TEAMID--","--ID--"), array($teamname,$team_id,$user_id), $player_team);
			
			return $output;
		}
	}

	function displayCaptain($con,$ip)
	{
		$self_captain = getCaptainStatus($con,$ip);

		if(empty($self_captain))
		{
			$team_id = getTeamId($con,$ip);
			$team_captain = getTeamCaptain($con,$team_id);

			if(empty($team_captain))
			{
				$output = "<i>Kein Teamchef bestimmt.</i>";
			} else {
				$output = $team_captain;
			}
		}

		return $output;
	}

	function displayPlayerTeamMember($con,$ip)
	{
		$team_id = getTeamId($con,$ip);

		$team_member = getTeamMembers($con,$ip,$team_id);

		if(empty($team_member))
		{
			$output = "<i>Du hast gegenwärtig keine anderen Teammitglieder.</i>";
		} else {
			$output = implode(", ",$team_member);
		}

		return $output;
	}

	function displayPlayerPrefs($con,$ip)
	{
		$player_pref = getSinglePlayerPref($con,$ip);

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
					$output = str_replace(array("--GAME_ID--","--ICON--","--PREF--"), array($pref,$gameInfo[0]["icon"],$gameInfo[0]["name"]), $part);
				} else {
					$output .= str_replace(array("--GAME_ID--","--ICON--","--PREF--"), array($pref,$gameInfo[0]["icon"],$gameInfo[0]["name"]), $part);
				}
				
			}
		}

		return $output;
	}
	
	function createCheckbox($con,$ip)
	{
		$games = getGameData($con);

		if(empty($games))
		{
			$output = "<i>Keine Spiele vorhanden</i>";
		} else {

			$userPrefs = getSinglePlayerPref($con,$ip);

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

/******************************* ACHIEVEMENTS ************************************/

function displayPlayerAchievements($con,$ip)
{
	$user_id = getUserId($con,$ip);

	$achievement_id = getUserAchievements($con,$user_id);

	$ac = new Achievement();
	if (empty($achievement_id))
	{
		$output = "Du hast bisher keine Achievements erworben.";
	} else {
		foreach ($achievement_id as $id)
		{
			$achievement_details = getAchievementById($con,$id);

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

function displayAvailableAchievements($con,$ip)
{
	$basic_ac = getAvailableAchievements($con,$ip);
	
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

?>