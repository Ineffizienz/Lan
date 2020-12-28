<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,3). "/include/admin_func.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");

	$message = new message();
	
	$game_id = $_REQUEST["game_id"];
	$g_name = $_REQUEST["game_name"];
	
	if(isset($g_name) && !empty($g_name))
	{
		$sql = "UPDATE games SET name = '$g_name' WHERE ID = '$game_id'";
		if(mysqli_query($con,$sql))
		{
			$message->getMessageCode("SUC_ADMIN_UPDATE_GAME_NAME");
			echo buildJSONOutput(array($message->displayMessage(),$_REQUEST["p_element"],$_REQUEST["c_element"]));
		} else {
			$message->getMessageCode("ERR_ADMIN_DB");
			echo buildJSONOutput($message->displayMessage());
		}
	} else {
		$message->getMessageCode("ERR_ADMIN_NO_GAME_NAME");
		echo buildJSONOutput($message->displayMessage());
	}
?>