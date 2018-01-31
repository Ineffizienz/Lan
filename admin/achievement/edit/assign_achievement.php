<?php
	include($_SERVER["DOCUMENT_ROOT"]. "/Lan_Git/include/init/constant.php");
	include(CL . "message_class.php");
	include(INC . "connect.php");
	include(INIT . "get_parameters.php");

	$message = new message();

	if(isset($_REQUEST["ac_id"]))
	{
		if(isset($_REQUEST["u_name"]))
		{
			$ac_id = $_REQUEST["ac_id"];
			$user_id = $_REQUEST["u_name"];

			$user_ac = getUserAchievements($con,$user_id);

			if (in_array($ac_id,$user_ac))
			{
				$message->getMessageCode("ERR_AC_ALLREADY_GIVEN_ADMIN");
				echo $message->displayMessage();
				echo $user_id;
			} else {
				$sql = "UPDATE ac_player SET `$user_id` = '1' WHERE ac_id = '$ac_id'";
				if(mysqli_query($con,$sql))
				{
					$message->getMessageCode("SUC_ASSIGN_AC_ADMIN");
					echo $message->displayMessage();
				} else {
					$message->getMessageCode("ERR_DB_ADMIN");
					echo $message->displayMessage();
					echo mysqli_error($con);
				}
			}

		} else {
			$message->getMessageCode("ERR_MISSING_PLAYER_ADMIN");
			echo $message->displayMessage();
		}
	} else {
		$message->getMessageCode("ERR_MISSING_AC_ADMIN");
		echo $message->displayMessage();
	}
?>