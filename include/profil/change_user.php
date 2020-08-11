<?php
	include(dirname(__FILE__,3) . "/include/init/constant.php");
	require_once INC . 'session.php';
	include(INC . "connect.php");
	include(INC. "function.php");

	include(CL. "message_class.php");
	include(CL . "progress_class.php");
	include(CL . "player_class.php");

	$message = new message();
	$achievement = new Progress();
	$player = new Player($con, $_SESSION["player_id"]);

	if (isset($_REQUEST["new_username"]) && !empty($_REQUEST["new_username"]))
	{
			$new_username = $_REQUEST["new_username"];
			$ex_username = getAllUsername($con);

			if(in_array($new_username,$ex_username))
			{
				$message->getMessageCode("ERR_USER_NAME");
				$achievement->getTrigger($con,$player->id,"Sir Brummel");
				echo json_encode(array("message" => $message->displayMessage(),"achievement" => $achievement->showAchievement()));
			} else {					
				$message->getMessageCode($player->setNewUsername($new_username));
				echo json_encode(array("message"=>$message->displayMessage()));
			}

	} else {
		$achievement->getTrigger($con,$player->id,"Sir Brummel");
		$message->getMessageCode("ERR_NO_USER_NAME");
		echo json_encode(array("message" => $message->displayMessage(),"achievement" => $achievement->showAchievement()));

	}
?>