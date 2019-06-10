<?php

	session_start();
	include(dirname(__FILE__,2) . "/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");

	$message = new message();

	$player_id = $_SESSION["player_id"];
	$raw_name = getSingleRawName($con,$_REQUEST["games"]);
	$old_key = getOldGameKey($con,$player_id,$raw_name);
	$new_key = getNewGameKey($con,$raw_name);

	$sql = "INSERT INTO rejected_key (game_key,game,player_id) VALUES ('$old_key','$raw_name','$player_id')";
	if(mysqli_query($con,$sql))
	{
		if(empty($new_key))
		{
			$sql = "UPDATE $raw_name SET game_key = NULL WHERE player_id = '$player_id'";
			if(mysqli_query($con,$sql))
			{
				$message->getMessageCode("ERR_NO_KEY");
				echo json_encode(array("message" => $message->displayMessage()));
			} else {
				$message->getMessageCode("ERR_DB");
				echo json_encode(array("message" => $message->displayMessage()));
			}
		} else {
			$sql = "DELETE FROM $raw_name WHERE player_id = '$player_id' AND game_key = '$old_key'";
			if (mysqli_query($con,$sql))
			{
				$sql = "UPDATE $raw_name SET player_id = '$player_id' WHERE game_key = '$new_key'";
				if (mysqli_query($con,$sql))
				{
					echo json_encode(array("key" => $new_key));
				} else {
					$message->getMessageCode("ERR_DB");
					echo json_encode(array("message" => $message->displayMessage()));
				}
			} else {
				$message->getMessageCode("ERR_DB");
				echo json_encode(array("message" => $message->displayMessage()));
			}
			
		}
	} else {
		$message->getMessageCode("ERR_DB");
		echo json_encode(array("message" => $message->displayMessage()));
	}

?>