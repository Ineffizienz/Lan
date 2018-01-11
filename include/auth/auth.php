<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/include/init/constant.php");
	include(CL . "message_class.php");
	include("../connect.php");
	include("../function.php");


	$message = new message();
	$username = $_REQUEST["name"];

	if(empty($username))
	{
		$message->getMessageCode("ERR_EMPTY_NAME_INPUT");
		echo $message->displayMessage();
	} else {
		$username_exist = getAllUsername($con);
		if(in_array($username,$username_exist))
		{
			$message->getMessageCode("ERR_USER_NAME");
			echo $message->displayMessage();
		} else {
			$user_ip = IP;

			$sql = "UPDATE player SET name = '$username' WHERE ip = '$user_ip'";
			if (mysqli_query($con,$sql))
			{
				mysqli_query($con,"ALTER TABLE ac_player ADD $username INT(11) NULL");
			}
		}
	}
?>