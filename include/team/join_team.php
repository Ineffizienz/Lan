<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/include/init/constant.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");
	include(CL . "progress_class.php");
	include(INC . "function.php");
	
	$ip = IP;
	$n_player = countPlayer($con);
	$n_teams = countTeams($con);
	$message = new message();
	$achievement = new Progress();

	// vorhandene Team-IDs auslesen

	$team_ids = getTeams($con);

	if(count($team_ids) <= 1)
	{
		$message->getMessageCode("ERR_NO_TEAMS");
		$achievement->getTrigger($con,IP,"Sir Brummel");
		echo json_encode(array("message" => $message->displayMessage(), "achievement" => $achievement->showAchievement()));

	} else {
		// überprüfen, ob bereits eine Team-ID dem gegenwärtigen Spieler zugeordnet ist

		$my_team = getTeamId($con,$ip);

		if (!($my_team == NULL))
		{
			$message->getMessageCode("ERR_STILL_MEMBER");
			$achievement->getTrigger($con,IP,"Sir Brummel");
			echo json_encode(array("message" => $message->displayMessage(), "achievement" => $achievement->showAchievement()));

		} else {
			
			$key = array_rand($team_ids);
			$single_team = $team_ids[$key];

			$max_team = round($n_player / $n_teams);

			$n_teammember = countTeammember($con,$single_team);

			if ($n_teammember == $max_team)
			{
				while ($n_teammember == $max_team)
				{
					$key = array_rand($team_ids);
					$single_team = $team_ids[$key];

					$n_teammember = countTeammember($con,$single_team);
				}
			}

			// Updating players team_id and printing out the team_name
			mysqli_query($con,"UPDATE player SET team_id = '$single_team' WHERE ip = '$ip'");

			$team_name = getJoinedTeamName($con,$single_team);

			$message->getMessageCode("SUC_JOIN_TEAM");
			echo json_encode(array("message" => $message->displayMessage()));

		}
	}
	
?>