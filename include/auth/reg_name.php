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
	$name = trim($_POST["username"]);
	if($name == "")
	{
		$message->getMessageCode("ERR_NO_USER_NAME");
		return array(false, $message);
	}
	
	if(mysqli_num_rows(mysqli_query($con, "SELECT ID FROM player WHERE name = '$name';")))
	{
		//user with this name already exists
		$message->getMessageCode("ERR_USER_NAME");
		return array(false, $message);
	}
	
	if (initializePlayer($con, $name, $_SESSION["player_id"]))
	{
		$message->getMessageCode("SUC_REG_NAME");
		return array(true, $message);

	} else {
		$message->getMessageCode("ERR_INITIAL_PLAYER");
		return array(false, $message);
	}
}