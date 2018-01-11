<?php
	
	// NOTES: rebuild reject-Function
	include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/include/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");

	$message = new message();

	$player_id = getPlayerID($con,IP);

	$raw_name = getSingleRawName($con,$_REQUEST["games"]);

	if (empty($raw_name))
	{
		$not = "Wähle bitte ein Spiel aus.";
		echo $not;
	} else {
			
			$game_key = generateGameKey($con,$raw_name,$player_id);

			if(substr($game_key,0,3) == "ERR")
			{
				$message->getMessageCode($game_key);
				echo $message->displayMessage();
			} else {
				echo $game_key;
			}
	}
?>