<?php
	
include(dirname(__FILE__,4) . "/include/init/constant.php");
include(dirname(__FILE__,3) . "/include/admin_func.php");
include(CL . "message_class.php");
include(INC . "connect.php");

$message = new message();

if (empty($_REQUEST["teamId"]))
{
	$message->getMessageCode("ERR_ADMIN_NO_TEAM_SELECTED");
	echo buildJSONOutput($message->displayMessage());
} else {
	$id = $_REQUEST["teamId"];
	$sql = "UPDATE player SET team_id = NULL WHERE team_id = '$id'";
	if(mysqli_query($con,$sql))
	{
		$sql = "DELETE FROM tm_teamname WHERE ID = '$id'";
		if(mysqli_query($con,$sql))
		{
			$message->getMessageCode("SUC_ADMIN_DELETE_TEAM");
			echo buildJSONOutput($message->displayMessage());
		} else {
			$message->getMessageCode("ERR_ADMIN_DELETE_TEAM");
			echo buildJSONOutput($message->displayMessage());
		}
	} else {
		$message->getMessageCode("ERR_ADMIN_UPDATE_TEAMID");
		echo buildJSONOutput($message->displayMessage());
	}
}
?>