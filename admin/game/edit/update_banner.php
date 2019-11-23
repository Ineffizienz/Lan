<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,3). "/include/admin_func.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");
	
	$message = new message();

	$game_id = $_REQUEST["game_id"];
	
	$existing_banner = getGameBanner($con,$game_id);
	
	if(!empty($existing_banner))
	{
		if(file_exists(BANNER . $existing_banner))
		{
			unlink(BANNER . $existing_banner);
			$sql = "UPDATE games SET banner = NULL WHERE ID = '$game_id'";
			if(!mysqli_query($con,$sql))
			{
				echo mysqli_error($con);
			}
		} else {
			$sql = "UPDATE games SET banner = NULL WHERE ID = '$game_id'";
			if(!mysqli_query($con))
			{
				echo mysqli_error($con);
			}
		}
	}
	
	$result_validate = validateImageFile($_FILES["file"]["size"],pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION)); //validates the ImageFile for its size and Imagetype
	if($result_validate == "1")
	{
		move_uploaded_file($_FILES["file"]["tmp_name"], BANNER . $_FILES["file"]["name"]);
		$path = $_FILES["file"]["name"];
		
		$sql = "UPDATE games SET banner = '$path' WHERE ID = '$game_id'";
		if(mysqli_query($con,$sql))
		{
			$message->getMessageCode("SUC_ADMIN_BANNER_CHANGED");
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