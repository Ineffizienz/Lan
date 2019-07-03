<?php
	
	session_start();
	include(dirname(__FILE__,3) . "/include/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");
	include(CL . "progress_class.php");

	$message = new message();
	$achievement = new Progress();
	$player_id = $_SESSION["player_id"];

	if (isset($_REQUEST["new_username"]) && !empty($_REQUEST["new_username"]))
	{
			$new_username = $_REQUEST["new_username"];
			$ex_username = getAllUsername($con);

			if(in_array($new_username,$ex_username))
			{
				$message->getMessageCode("ERR_USER_NAME");
				$achievement->getTrigger($con,$player_id,"Sir Brummel");
				echo json_encode(array("message" => $message->displayMessage(),"achievement" => $achievement->showAchievement()));
			} else {
				$sql = "UPDATE player SET name = '$new_username' WHERE ID = '$player_id'";
				if(mysqli_query($con,$sql))
				{
					$message->getMessageCode("SUC_CHANGE_USERNAME");
					echo json_encode(array("message"=>$message->displayMessage()));
				} else {
					$message->getMessageCode("ERR_CHANGE_USERNAME");
					echo json_encode(array("message"=>$message->displayMessage()));
				}
			}

	} else {
		$achievement->getTrigger($con,$player_id,"Sir Brummel");
		$message->getMessageCode("ERR_NO_USER_NAME");
		echo json_encode(array("message" => $message->displayMessage(),"achievement" => $achievement->showAchievement()));

	}
?>