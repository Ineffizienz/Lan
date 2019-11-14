<?php

	session_start();
	include(dirname(__FILE__,2) . "/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");

	$message = new message();

	$player_id = $_SESSION["player_id"];
	$game_id = $_REQUEST["game"];
	if(mysqli_query($con,"UPDATE gamekeys SET rejected = '1' WHERE (player_id = '$player_id') AND (game_id = '$game_id') LIMIT 1;"))
	{
		$new_key = generateGameKey($con, $player_id, $game_id);
		if(substr($new_key,0,3) == "ERR")
		{
			$message->getMessageCode($new_key);
			echo json_encode(array("message" => $message->displayMessage()));
		}
		else
		{
			echo json_encode(array("key" => $new_key));
		}
	}
	else
	{
		$message->getMessageCode("ERR_DB");
		echo json_encode(array("message" => $message->displayMessage()));
	}
?>