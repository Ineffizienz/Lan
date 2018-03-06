<?php
	include(dirname(__FILE__,3). "/include/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");
	include(CL . "progress_class.php");

	$team_id = $_REQUEST["team"];
	$user = $_REQUEST["user"];
	$message = new message();
	$achievement = new Progress();

	if(!empty($team_id))
	{
		$sql = "UPDATE player SET team_id = NULL WHERE ID = '$user'";

		if (mysqli_query($con,$sql) === TRUE)
		{
			$message->getMessageCode("SUC_LEAVE_TEAM");
			echo json_encode(array("message" => $message->displayMessage()));

		} else {

			$message->getMessageCode("ERR_LEAVE_TEAM");
			$achievement->getTrigger($con,IP,"Sir Brummel");
			echo json_encode(array("message" => $message->displayMessage(), "achievement" => $achievement->showAchievement()));

		}
	}
?>