<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(CL . "message_class.php");
	include(INC . "connect.php");

	$message = new message();

	if (empty($_REQUEST["id"]))
	{
		$message->getMessageCode("ERR_NO_TEAM_SELECTED");
		echo $message->displayMessage();
	} else {
		$id = $_REQUEST["id"];
		$sql = "UPDATE player SET team_id = NULL WHERE team_id = '$id'";
		if(mysqli_query($con,$sql))
		{
			$sql = "DELETE FROM tm_teamname WHERE ID = '$id'";
			if(mysqli_query($con,$sql))
			{
				$message->getMessageCode("SUC_DELETE_TEAM");
				echo $message->displayMessage();
			}
		}
	}
?>