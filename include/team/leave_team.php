<?php
	include(dirname(__FILE__,3). "/include/init/constant.php");
	require_once INC . 'session.php';
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");
	include(CL . "progress_class.php");

	$player_id = $_SESSION["player_id"];
	$team_id = $_REQUEST["team"];
	$message = new message();
	$achievement = new Progress();

	if(!empty($team_id))
	{
		$sql = "UPDATE player SET team_id = NULL WHERE ID = '$player_id'";

		if (mysqli_query($con,$sql) === TRUE)
		{
			$message->getMessageCode("SUC_LEAVE_TEAM");
			echo json_encode(array("message" => $message->displayMessage()));

		} else {

			$message->getMessageCode("ERR_LEAVE_TEAM");
			$achievement->getTrigger($con,$player_id,"Sir Brummel");
			echo json_encode(array("message" => $message->displayMessage(), "achievement" => $achievement->showAchievement()));

		}
	}
?>