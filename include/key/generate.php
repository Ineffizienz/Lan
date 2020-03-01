<?php
	
	// NOTES: rebuild reject-Function
	include(dirname(__FILE__,2) . "/init/constant.php");
	require_once INC . 'session.php';
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");

	$message = new message();

	$player_id = $_SESSION["player_id"];
	
	$game_id = $_REQUEST["game"];

	if (empty($game_id))
	{
		$not = "Wähle bitte ein Spiel aus.";
		echo $not;
	} else {
			
			$game_key = generateGameKey($con, $player_id, $game_id);

			if(substr($game_key,0,3) == "ERR")
			{
				$message->getMessageCode($game_key);
				echo json_encode(array("message" => $message->displayMessage()));
			} else {
				echo json_encode(array("key" => $game_key));
			}
	}
?>