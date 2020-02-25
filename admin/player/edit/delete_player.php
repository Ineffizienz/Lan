<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(CL . "message_class.php");
	include(INC . "connect.php");
	include(INIT . "get_parameters.php");
	
	$message = new message();
	$player_id = $_REQUEST["player"];
	
	if(isset($player_id) && ($player_id !== ""))
	{
		$user_ids = getAllUserIDs($con);
		
		if(in_array($player_id,$user_ids))
		{
			$first_login = getFirstLoginById($con,$player_id);
			
			if ($first_login == "1")
			{
				$sql = "DELETE FROM player WHERE ID ='$player_id'";
				if(mysqli_query($con,$sql))
				{
					$message->getMessageCode("SUC_ADMIN_DELETE_USER");
					echo $message->displayMessage();
				} else {
					$message->getMessageCode("ERR_ADMIN_DB");
					echo $message->displayMessage();
					echo mysqli_error($con);
				}
			} else {
				$sql = "DELETE FROM status WHERE user_id = '$player_id'";
				if(mysqli_query($con,$sql))
				{
					if(getAllPlayerKeys($con,$player_id))
					{
						$sql = "UPDATE gamekeys SET player_id = NULL WHERE player_id = '$player_id'";
						if(!mysqli_query($con,$sql))
						{
							$message->getMessageCode("ERR_ADMIN_DB");
							echo $message->displayMessage();
							echo mysqli_error($con);
						}
					} 
					
					$player_achievements = getUserAchievements($con,$player_id);

					if(!empty($player_achievements))
					{
						$sql = "DELETE FROM ac_player WHERE player_id = '$player_id'";
						if(!mysqli_query($con,$sql))
						{
							$message->getMessageCode("ERR_ADMIN_DB");
							echo $message->displayMessage();
							echo mysqli_error($con);
						}
					}
					
					$sql = "DELETE FROM player WHERE ID = '$player_id'";
					if(mysqli_query($con,$sql))
					{
						$message->getMessageCode("SUC_ADMIN_DELETE_USER");
						echo $message->displayMessage();
					} else {
						$message->getMessageCode("ERR_ADMIN_DB");
						echo $message->displayMessage();
						echo mysqli_error($con);
					}
					
					
				} else {
					$message->getMessageCode("ERR_ADMIN_DB");
					echo $message->displayMessage();
					echo mysqli_error($con);
				}
			}
		} else {
			$message->getMessageCode("ERR_ADMIN_USER_DOES_NOT_EXISTS");
			echo $message->displayMessage();
		}
	}
?>