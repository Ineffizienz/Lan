<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/include/init/constant.php");
	include(INC . "connect.php");
	include(INIT . "get_parameters.php");
	include(CL . "message_class.php");

	$message = new message();
	$ac_id = $_REQUEST["ac_id"];
	$visib = $_REQUEST["visib"];

	$sql = "UPDATE ac SET ac_visibility = '$visib' WHERE ID = '$ac_id'";
	if(mysqli_query($con,$sql))
	{
		$message->getMessageCode("SUC_CHANGE_VISIBILITY");
		echo $message->displayMessage();
	} else {
		$message->getMessageCode("ERR_DB");
		echo $message->displayMessage();
	}
?>