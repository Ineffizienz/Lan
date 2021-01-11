<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,3) . "/include/admin_func.php");
	include(CL . "message_class.php");
	include(CL . "player_class.php");
	include(INC . "connect.php");
	
	$message = new message();
	$player_id = $_REQUEST["player"];
	
	if(isset($player_id) && ($player_id !== ""))
	{
		$user_ids = getAllUserIDs($con);
		
		if(in_array($player_id,$user_ids))
		{
			$player = new Player($con,$player_id);
			$sql = "UPDATE gamekeys SET player_id = NULL WHERE player_id = " . $player->getPlayerId() . "";
			
			if(mysqli_query($con,$sql))
			{
				$message->getMessageCode($player->removePlayerFromSystem());
				echo buildJSONOutput(array($message->displayMessage(),$_REQUEST["p_element"],$_REQUEST["c_element"],0));
			} else {
				$message->getMessageCode("ERR_ADMIN_DB");
				echo buildJSONOutput($message->displayMessage());
			}
			
		} else {
			$message->getMessageCode("ERR_ADMIN_USER_DOES_NOT_EXISTS");
			echo buildJSONOutput($message->displayMessage());
		}
	}
?>