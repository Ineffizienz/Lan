<?php
	
	session_start();
	include(dirname(__FILE__,3) . "/include/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");

	$message = new message();
	$player_id = $_SESSION["player_id"];

	if (isset($_REQUEST["user"]) && !empty($_REQUEST["user"]))
	{
			$new_username = $_REQUEST["user"];
			$ex_username = getAllUsername($con);

			if(in_array($new_username,$ex_username))
			{
				$message->getMessageCode("ERR_USER_NAME");
				echo json_encode(array("message"=>$message->displayMessage()));
			} else {
				$sql = "UPDATE player SET name = '$new_username' WHERE ID = '$player_id'";
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