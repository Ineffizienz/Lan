<?php
	session_start();
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
		$ticket_hash = sha1($_REQUEST["id"]); //TODO: maybe replace sha1 by something better, for example password_hash
		$result = mysqli_query($con, "SELECT ID, name FROM player WHERE ticket_id = '$ticket_hash';"); //todo: rename colum ticket_id to ticket_hash
		
		$err = mysqli_error($con);
		
		if(mysqli_num_rows($result) == 0)
		{
			$message->getMessageCode("ERR_VAL_FAILED");
			echo json_encode(array("message" => $message->displayMessage(), "step" => "0"));
			//TODO: better error message?: ticket id not found / wrong ticket id
		}
		elseif(mysqli_num_rows($result) > 1)
		{
			$message->getMessageCode("ERR_VAL_FAILED");
			echo json_encode(array("message" => $message->displayMessage(), "step" => "0"));
			//TODO: better error message?: multiple players with identical ticket id
		}
		else
		{
			$row = mysqli_fetch_assoc($result);
			$_SESSION["player_id"] = $row["ID"];
			
			if($row["name"] == "")
			{
				//TODO: player needs to select a name
			}
			else
			{
				$message->getMessageCode("SUC_VAL_APPROVED");
				echo json_encode(array("message" => $message->displayMessage(), "step" => "2"));
			}
		}
	}
?>