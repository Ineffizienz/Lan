<?php
	session_start();
	include(dirname(__FILE__,3) . "/include/init/constant.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");
	include(CL . "progress_class.php");
	include(INC . "function.php");
	
	$player_id = $_SESSION["player_id"];
	$n_player = countPlayer($con);
	$n_teams = countTeams($con);
	$message = new message();
	$achievement = new Progress();

	// vorhandene Team-IDs auslesen

	
	$team_ids = getAllTeams($con);

	if(count($team_ids) <= 1)
	{
		$message->getMessageCode("ERR_NO_TEAMS");
		$achievement->getTrigger($con,$player_id,"Sir Brummel");
		echo json_encode(array("message" => $message->displayMessage(), "achievement" => $achievement->showAchievement()));

	} else {
		// überprüfen, ob bereits eine Team-ID dem gegenwärtigen Spieler zugeordnet ist

		$my_team = getTeamId($con,$player_id);

		if (!($my_team == NULL))
		{
			$message->getMessageCode("ERR_STILL_MEMBER");
			$achievement->-getTrigger($con,$player_id,"Sir Brummel");
			echo json_encode(array("message" => $message->displayMessage(), "achievement" => $achievement->showAchievement()));

		} else {
			
			$key = array_rand($team_ids);
			$single_team = $team_ids[$key];

			$max_team = round($n_player / $n_teams);

			$n_teammember = countTeammember($con,$single_team["ID"]);

			if ($n_teammember == $max_team)
			{
				while ($n_teammember == $max_team)
				{
					$key = array_rand($team_ids);
					$single_team = $team_ids[$key];

					$n_teammember = countTeammember($con,$single_team["ID"]);
				}
			}
			
			$single_id = $single_team["ID"];
			// Updating players team_id and printing out the team_name
			$sql = "UPDATE player SET team_id = '$single_id' WHERE ID = '$player_id'";

			if(mysqli_query($con,$sql))
			{
				$team_name = getJoinedTeamName($con,$single_team["ID"]);

				$message->getMessageCode("SUC_JOIN_TEAM");
				echo json_encode(array("message" => $message->displayMessage()));
			} else {
				$message->getMessageCode("ERR_DB");
				echo json_encode(array("message" => $message->displayMessage()));
			}

		}
	}
	
?>