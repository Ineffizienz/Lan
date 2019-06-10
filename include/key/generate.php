<?php
	
	// NOTES: rebuild reject-Function
	session_start();
	include(dirname(__FILE__,2) . "/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");

	$message = new message();

	$player_id = $_SESSION["player_id"];
	
	$raw_name = $_REQUEST["games"];

	if (empty($raw_name))
	{
		$not = "Wähle bitte ein Spiel aus.";
		echo $not;
	} else {
			
			$game_key = generateGameKey($con,$raw_name,$player_id);

			if(substr($game_key,0,3) == "ERR")
			{
				$message->getMessageCode($game_key);
				echo json_encode(array("message" => $message->displayMessage()));
			} else {
				echo json_encode(array("key" => $game_key));
			}
	}
?>