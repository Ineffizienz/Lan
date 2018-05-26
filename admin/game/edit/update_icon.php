<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,3). "/include/admin_func.php");
	include(INIT . "get_parameters.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");
	
	$message = new message();

	$game_id = $_REQUEST["game_id"];
	
	$existing_icon = getGameIcon($con,$game_id);
	
	if(!empty($existing_icon))
	{
		if(file_exists(ICON . $existing_icon))
		{
			unlink(ICON . $existing_icon);
			$sql = "UPDATE games SET icon = NULL WHERE ID = '$game_id'";
			if(!mysqli_query($con,$sql))
			{
				echo mysqli_error($con);
			}
		} else {
			$sql = "UPDATE games SET icon = NULL WHERE ID = '$game_id'";
			if(!mysqli_query($con))
			{
				echo mysqli_error($con);
			}
		}
	}
	
	$result_validate = validateImageFile($_FILES["file"]["size"],pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION)); //validates the ImageFile for its size and Imagetype
	if($result_validate == "1")
	{
		move_uploaded_file($_FILES["file"]["tmp_name"], ICON . $_FILES["file"]["name"]);
		$path = $_FILES["file"]["name"];
		
		$sql = "UPDATE games SET icon = '$path' WHERE ID = '$game_id'";
		if(mysqli_query($con,$sql))
		{
			$messageText = "SUC_ADMIN_ICON_CHANGED";
			echo substr($messageText,10);
			$message->getMessageCode("SUC_ADMIN_ICON_CHANGED");
			echo $message->displayMessage();
		} else {
			$message->getMessageCode("ERR_ADMIN_DB");
			echo $message->displayMessage();
		}
		
	} else {
		$message->getMessageCode($result_validate);
		echo $message->displayMessage();
	}
?>