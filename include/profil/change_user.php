<?php
	include(dirname(__FILE__,3) . "/include/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");

	$message = new message();

	if (isset($_REQUEST["user"]) && !empty($_REQUEST["user"]))
	{
			$ip = IP;
			$new_username = $_REQUEST["user"];
			$ex_username = getAllUsername($con);

			if(in_array($new_username,$ex_username))
			{
				$message->getMessageCode("ERR_USER_NAME");
				echo json_encode(array("message"=>$message->displayMessage()));
			} else {
				$sql = "UPDATE player SET name = '$new_username' WHERE IP = '$ip'";
				if(mysqli_query($con,$sql))
				{
					$message->getMessageCode("SUC_CHANGE_USERNAME");
					echo json_encode(array("message"=>$message->displayMessage()));
				} else {
					$message->getMessageCode("ERR_CHANGE_USERNAME");
					echo json_encode(array("message"=>$message->displayMessage()));
				}
			}

	} else {

		$message->getMessageCode("ERR_NO_USER_NAME");
		echo json_encode(array("message"=>$message->displayMessage()));

	}
?>