<?php
	session_start();
	include(dirname(__FILE__,2) . "/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");

	$teamname = $_REQUEST["name"];

	$team_id = getTeamIdByName($con,$teamname);
	$player_id = $_SESSION["player_id"];
	$message = new message();
	$achievement = new Progress();

	if (empty($team_id))
	{
		$message->getMessageCode("ERR_MISSING_TEAM");
		$achievement->getTrigger($con,$player_id,"Sir Brummel");
		echo json_encode(array("message" => $message->displayMessage(), "achievement" => $achievement->showAchievement()));

	} else {
		mysqli_query($con,"UPDATE player SET team_id = NULL WHERE team_id = '$team_id'");

		mysqli_query($con,"DELETE FROM tm_teamname WHERE ID = '$team_id'");

		$message->getMessageCode("SUC_DELETE_TEAM");
		echo json_encode(array("message" => $message->displayMessage()));

	}
?>