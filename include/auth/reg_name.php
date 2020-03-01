<?php
/**
 * Checks, whether the name given via post is valid and if so writes it to the database
 * 
 * @return returns an array consisting of a bool for success and a message to display
 */
function reg_name(mysqli $con, $message)
{
	if(!isset($_POST["username"]))
	{
		//form not submitted -> no error message, just display from (handled by calling code)
		return array(false, $message);
	}	
	$nick = trim($_POST["username"]);
	$real_name = trim($_POST["real_name"]);
	if($nick == "" || $real_name == "")
	{
		$message->getMessageCode("ERR_NO_USER_NAME");
		return array(false, $message);
	}
	
	if(checkPlayernameExists($con, $nick) != $_SESSION["player_id"])
	{
		//another user with this name already exists
		$message->getMessageCode("ERR_USER_NAME");
		return array(false, $message);
	}
	
	if (initializePlayer($con, $nick, $real_name, $_SESSION["player_id"]))
	{
		$message->getMessageCode("SUC_REG_NAME");
		return array(true, $message);

	} else {
		$message->getMessageCode("ERR_INITIAL_PLAYER");
		return array(false, $message);
	}
}