<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,3) . "/include/admin_func.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");

	$message = new message();

	$game_id = $_REQUEST["game_id"];
	$has_table = $_REQUEST["has_table"];

	if($has_table == 0)
	{
		$new_value = "NULL";
	} else {
		$new_value = $has_table;
	}

	$sql = "UPDATE games SET has_table = '$new_value' WHERE ID = '$game_id'";
	if(mysqli_query($con,$sql))
	{
		$message->getMessageCode("SUC_ADMIN_GAME_HAS_TABLE");
		echo buildJSONOutput($message->displayMessage());
	} else {
		$message->getMessageCode("ERR_ADMIN_DB");
		echo buildJSONOutput($message->displayMessage());
	}