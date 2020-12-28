<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,3). "/include/admin_func.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");
	
	$message = new message();
	
	$game_id = $_REQUEST["game_id"];
	$n_rawname = $_REQUEST["n_raw"];

	$has_table = getHasTableByGameID($con,$game_id);
	
	if(empty($has_table))
	{
		$sql = "UPDATE games SET raw_name = '$n_rawname' WHERE ID = '$game_id'";
		if(mysqli_query($con,$sql))
		{
			$message->getMessageCode("SUC_ADMIN_UPDATE_RAWNAME");
			echo buildJSONOutput($message->displayMessage());
		} else {
			$message->getMessageCode("ERR_ADMIN_DB");
			echo buildJSONOutput($message->displayMessage());
		}
	} else {
		$old_rawname = getRawNameByID($con,$game_id);
		$sql = "UPDATE games SET raw_name = '$n_rawname' WHERE ID = '$game_id'";
		if(mysqli_query($con,$sql))
		{
			$message->getMessageCode("SUC_ADMIN_UPDATE_RAWNAME");
			echo buildJSONOutput($message->displayMessage());
		} else {
			$message->getMessageCode("ERR_ADMIN_DB");
			echo buildJSONOutput($message->displayMessage());
		}
	}
?>