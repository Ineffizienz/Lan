<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/include/init/constant.php");
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
					$message->getMessageCode("SUC_DELETE_USER");
					echo $message->displayMessage();
				} else {
					$message->getMessageCode("ERR_DB");
					echo $message->displayMessage();
					echo mysqli_error($con);
				}
			} else {
				$sql = "DELETE FROM status WHERE user_id = '$player_id'";
				if(mysqli_query($con,$sql))
				{
					$raw_name = getRawName($con);
					foreach ($raw_name as $game)
					{
						$result = mysqli_query($con,"SELECT player_id FROM $game WHERE player_id = '$player_id'");
						while($row=mysqli_fetch_array($result))
						{
							$used_key = $row["player_id"];
						}
	
						if(!empty($used_key))
						{
							if(mysqli_query($con,"UPDATE $game SET player_id = NULL WHERE player_id = '$player_id'"));
						}
					}
					
					$username = getUsernameById($con,$player_id);
					
					$sql = "ALTER TABLE ac_player DROP COLUMN $username"; // Check if user has ac_player entry!
					if(mysqli_query($con,$sql))
					{
						$sql = "DELETE FROM player WHERE ID = '$player_id'";
						if(mysqli_query($con,$sql))
						{
							$message->getMessageCode("SUC_DELETE_USER");
							echo $message->displayMessage();
						} else {
							$message->getMessageCode("ERR_DB");
							echo $message->displayMessage();
							echo mysqli_error($con);
						}
					} else {
						$message->getMessageCode("ERR_DB");
						echo $message->displayMessage();
						echo mysqli_error($con);
					}
				} else {
					$message->getMessageCode("ERR_DB");
					echo $message->displayMessage();
					echo mysqli_error($con);
				}
			}
		} else {
			$message->getMessageCode("ERR_USER_DOES_NOT_EXISTS");
			echo $message->displayMessage();
		}
	}
?>