<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");

	$message = new message();
	
	$game_id = $_REQUEST["game_id"];
	$s_name = $_REQUEST["short_name"];
	
	if(isset($s_name) && !empty($s_name))
	{
		$sql = "UPDATE games SET short_title = '$s_name' WHERE ID = '$game_id'";
		if(mysqli_query($con,$sql))
		{
			$message->getMessageCode("SUC_ADMIN_UPDATE_GAME_NAME");
			echo json_encode(array("message" => $message->displayMessage(), "new_value" => $s_name));
		} else {
			$message->getMessageCode("ERR_ADMIN_DB");
			echo json_encode(array("message" => $message->displayMessage()));
		}
	} else {
		$message->getMessageCode("ERR_ADMIN_NO_GAME_NAME");
		echo json_encode(array("message" => $message->displayMessage()));
	}
?>