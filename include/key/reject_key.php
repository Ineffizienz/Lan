<?php
	include(dirname(__FILE__,2) . "/init/constant.php");
	require_once INC . 'session.php';
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");
	include(CL . "player_class.php");

	$message = new message();
	$player = new Player($con, $_SESSION["player_id"]);

	$game_id = $_REQUEST["game"];
	if($player->setRejectKey($game_id))
	{
		$new_key = generateGameKey($con, $player, $game_id);
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