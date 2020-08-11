<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(CL . "message_class.php");
	include(CL . "player_class.php");
	include(INC . "connect.php");
	include(INIT . "get_parameters.php");
	
	$message = new message();
	$player_id = $_REQUEST["player"];
	
	if(isset($player_id) && ($player_id !== ""))
	{
		$user_ids = getAllUserIDs($con);
		
		if(in_array($player_id,$user_ids))
		{
			$player = new Player($con,$player_id);
			$message->getMessageCode($player->removePlayerFromSystem());
			echo $message->displayMessage();
		} else {
			$message->getMessageCode("ERR_ADMIN_USER_DOES_NOT_EXISTS");
			echo $message->displayMessage();
		}
	}
?>