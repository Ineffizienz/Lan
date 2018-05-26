<?php
	include(dirname(__FILE__,3) . "/include/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");
	
	$username = getSingleUsername($con,IP);
	$first_login = getFirstLoginByIp($con,IP);
	$activeTicket = getTicketStatus($con,IP);
	$message = new message();

	if ($first_login == "1")
	{
		if(!isset($_POST["name"]) || ($_POST["name"] == ""))
		{
			$message->getMessageCode("ERR_NO_USER_NAME");
			echo json_encode(array("message" => $message->displayMessage(), "step" => "0"));

		} else {
			
			$e_user = getAllUsername($con);

			if (in_array($_POST["name"],$e_user))
			{
				$message->getMessageCode("ERR_USER_NAME");
				echo json_encode(array("message" => $message->displayMessage(), "step" => "0"));


			} else {
				$username = $_POST["name"];

				$result = initializePlayer($con,$username,IP);

				if ($result == "1")
				{
					$message->getMessageCode("SUC_REG_NAME");
					echo json_encode(array("message" => $message->displayMessage(), "step" => "1"));

				} else {
					$message->getMessageCode("ERR_INITIAL_PLAYER");
					echo json_encode(array("message" => $message->displayMessage(), "step" => "0"));
				}
				
			}
		}
	}
?>