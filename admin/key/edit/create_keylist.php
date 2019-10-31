<?php

//NOTES:
/*
	- success Message/handling missing
	- delete File missing
	- close file connection missing 
*/

include(dirname(__FILE__,4) . "/include/init/constant.php");
include(dirname(__FILE__,3) . "/include/admin_func.php");
include(INC . "connect.php");
include(CL . "message_class.php");

// validate if Game exists allready in DB


$message = new message();

$response = validateInput($_REQUEST["game"]);

if ($response === TRUE)
{
	$new_game = $_REQUEST["game"];
	if($_FILES["file"]["size"] == 0)
	{
		$message->getMessageCode("ERR_ADMIN_FILE_TO_HUGE");
		echo buildJSONOutput($message->displayMessage());      
	} 
	else
	{
		move_uploaded_file($_FILES["file"]["tmp_name"], KEY_FOLDER . $_FILES["file"]["name"]);
		$new_raw_name = rtrim($_FILES["file"]["name"],".txt");
		$key_list = file(KEY_FOLDER . $_FILES["file"]["name"]);

		if(!verifyGame($con,$new_game,$new_raw_name))
			createGame($con,$new_game,$new_raw_name);
		
		$result_game_id = mysqli_query($con, "SELECT ID FROM games WHERE (raw_name = $new_raw_name) LIMT 1;");
		$game_id = mysqli_fetch_array($result)["ID"];
		foreach ($key_list as $key)
		{
			$key = trim(strtoupper($key));
			$key_response = verifyKey($con, $game_id, $key);
			if ($key_response === true)
			{
				$sql = "INSERT INTO gamekeys (gamekey, game_id) VALUES ('$key', '$game_id')";
				if(mysqli_query($con,$sql))
				{
					$message->getMessageCode("SUC_ADDED_GAMEKEY");
					echo buildJSONOutput($message->displayMessage());
				}
			}
			else
			{
				$message->getMessageCode($key_response);
				echo buildJSONOutput($message->displayMessage());
			}
		}
	}
} else {
	$message->getMessageCode($response);
	echo buildJSONOutput($message->displayMessage());
}
?>