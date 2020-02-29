<?php

require_once dirname(__FILE__,3) . "/include/init/constant.php";
require_once CL.'message_class.php';
require_once INC.'connect.php';

/**
 * Checks, whether the ticket_id given via post exists in the database. If it does $_SESSION["player_id"] is set accordingly
 * 
 * @return returns an array consisting of a bool for success and a message to display
 */
function validate_ticket(mysqli $con)
{
	$message = new message();
	if(!isset($_POST["ticket_id"]))
	{
		return array(false, $message);
	}	
	$ticket_id = trim($_POST["ticket_id"]);
	if($ticket_id == "")
	{
		$message->getMessageCode("ERR_TICKET_MISSING");
		return array(false, $message);
	}

	$ticket_hash = sha1($ticket_id); //TODO: maybe replace sha1 by something better, for example password_hash
	$result = mysqli_query($con, "SELECT ID FROM player WHERE ticket_id = '$ticket_hash';"); //todo: rename colum ticket_id to ticket_hash

	if(mysqli_num_rows($result) == 0)
	{
		$message->getMessageCode("ERR_VAL_FAILED");
		//TODO: better error message?: ticket id not found / wrong ticket id
		return array(false, $message);
	}
	elseif(mysqli_num_rows($result) > 1)
	{
		$message->getMessageCode("ERR_VAL_FAILED");
		//TODO: better error message?: multiple players with identical ticket id
		return array(false, $message);
	}
	else
	{
		$row = mysqli_fetch_assoc($result);
		$_SESSION["player_id"] = (int)$row["ID"];

		$sql = "UPDATE player SET ticket_active = '1' WHERE ID = '" . addslashes($_SESSION['player_id']) . "'";
		if(mysqli_query($con,$sql))
		{
			$message->getMessageCode("SUC_VAL_APPROVED");
			return array(true, $message);
		}
	}
}

list($success, $msg) = validate_ticket($con);
echo json_encode(array("message" => $msg->displayMessage()));
