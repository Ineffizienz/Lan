<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
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
				$message->getMessageCode("ERR_ADMIN_AC_ALLREADY_GIVEN");
				echo $message->displayMessage();
				echo $user_id;
			} else {
				$sql = "UPDATE ac_player SET `$user_id` = '1' WHERE ac_id = '$ac_id'";
				if(mysqli_query($con,$sql))
				{
					$message->getMessageCode("SUC_ADMIN_ASSIGN_AC");
					echo $message->displayMessage();
				} else {
					$message->getMessageCode("ERR_ADMIN_DB");
					echo $message->displayMessage();
					echo mysqli_error($con);
				}
			}

		} else {
			$message->getMessageCode("ERR_ADMIN_MISSING_PLAYER");
			echo $message->displayMessage();
		}
	} else {
		$message->getMessageCode("ERR_ADMIN_MISSING_AC");
		echo $message->displayMessage();
	}
?>