<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/include/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "message_class.php");

	$message = new message();
	$ip = IP;
	if(empty($_REQUEST["id"]))
	{
		$message->getMessageCode("ERR_TICKET_MISSING");
		echo json_encode(array("message" => $message->displayMessage(), "step" => "0"));
	} else {
		$result = mysqli_query($con,"SELECT ticket_id FROM player WHERE ip = '$ip'");
		while($row=mysqli_fetch_array($result))
		{
			$user_ticket = $row["ticket_id"];
		}
		
		$given_ticket = $_REQUEST["id"];
		
		if(sha1($given_ticket) == $user_ticket)
		{
			mysqli_query($con,"UPDATE player SET ticket_active = '1' WHERE ip = '$ip'");
			$message->getMessageCode("SUC_VAL_APPROVED");
			echo json_encode(array("message" => $message->displayMessage(), "step" => "2"));
			
		} else {
			$message->getMessageCode("ERR_VAL_FAILED");
			echo json_encode(array("message" => $message->displayMessage(), "step" => "0"));
		}
	}
?>