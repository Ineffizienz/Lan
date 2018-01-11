<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/include/init/constant.php");
	include(CL . "message_class.php");
	include(INC . "connect.php");

	$message = new message();

	if (empty($_REQUEST["id"]))
	{
		$message->getMessageCode("ERR_NO_TEAM_SELECTED");
		echo $message->displayMessage();
	} else {
		$id = $_REQUEST["id"];
		mysqli_query($con,"UPDATE player SET team_id = '0' WHERE team_id = '$id'");
		mysqli_query($con,"DELETE FROM tm_teamname WHERE ID = '$id'");
	}
?>