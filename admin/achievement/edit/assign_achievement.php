<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,3) . "/include/admin_func.php");
	include(CL . "message_class.php");
	include(INC . "connect.php");
	include(INIT . "get_parameters.php");

	$message = new message();

	if(isset($_REQUEST["ac_id"]))
	{
		if(isset($_REQUEST["u_id"]))
		{
			$ac_id = $_REQUEST["ac_id"];
			$user_id = $_REQUEST["u_id"];

			$user_ac = getUserAchievements($con,$user_id);

			if (in_array($ac_id,$user_ac))
			{
				$message->getMessageCode("ERR_ADMIN_AC_ALLREADY_GIVEN");
				echo buildJSONOutput($message->displayMessage());
			} else {
				$sql = "INSERT ac_player (player_id,ac_id) VALUES ('$user_id','$ac_id')";
				if(mysqli_query($con,$sql))
				{
					$message->getMessageCode("SUC_ADMIN_ASSIGN_AC");
					echo buildJSONOutput($message->displayMessage());
				} else {
					$message->getMessageCode("ERR_ADMIN_DB");
					echo buildJSONOutput($message->displayMessage());
				}
			}

		} else {
			$message->getMessageCode("ERR_ADMIN_MISSING_PLAYER");
			echo buildJSONOutput($message->displayMessage());
		}
	} else {
		$message->getMessageCode("ERR_ADMIN_MISSING_AC");
		echo buildJSONOutput($message->displayMessage());
	}
?>