<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,3) . "/include/admin_func.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");

	$message = new message();

	$game_id = $_REQUEST["game_id"];
	$tm_game = $_REQUEST["tm_game"];

	$sql = "UPDATE games SET tm_game = '$tm_game' WHERE ID = '$game_id'";
	if(mysqli_query($con,$sql))
	{
		$message->getMessageCode("SUC_ADMIN_GAME_IS_TOURNAMENT");
		echo buildJSONOutput($message->displayMessage());
	} else {
		$message->getMessageCode("ERR_ADMIN_DB");
		echo buildJSONOutput($message->displayMessage());
	}