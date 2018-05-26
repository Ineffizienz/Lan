<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,3). "/include/admin_func.php");
	include(INC . "connect.php");
	include(INIT . "get_parameters.php");
	include(CL . "message_class.php");

	$message = new message();
	
	if(empty($_REQUEST["game"]))
	{
		$message->getMessageCode("ERR_ADMIN_NO_GAME");
		echo $message->displayMessage();
	} else {
		$result = mysqli_query($con,"SELECT name FROM games");
		while($row=mysqli_fetch_array($result))
		{
			$exist_games[] = $row["name"];
		}

		if(in_array($_REQUEST["game"],$exist_games))
		{
			$message->getMessageCode("ERR_ADMIN_GAME_EXISTS");
			echo $message->displayMessage();
		} else {
			$new_game = $_REQUEST["game"];
			$new_raw_name = strtolower(str_replace(" ","_",$new_game));
			
			if(empty($_REQUEST["raw_name"]))
			{
				$has_table = "NULL";
			} else {
				$has_table = "1";
			}
			
			if(isset($_FILES["file"]["size"] && !empty($_FILES["file"]["size"])))
			{
				$result_validate = validateImageFile($_FILES["file"]["size"],pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION)); //validates the ImageFile for its size and Imagetype
				if($result_validate == "1")
				{
					move_uploaded_file($_FILES["file"]["tmp_name"], ICON . $_FILES["file"]["name"]);
					$path = $_FILES["file"]["name"];
					
					$sql = "INSERT INTO games (name,raw_name,icon,has_table) VALUES ('$new_game','$new_raw_name','$path','$has_table'";
					if(mysqli_query($con,$sql))
					{
						$message->getMessageCode("SUC_ADMIN_CREATE_NEW_GAME");
						echo $message->displayMessage();
					} else {
						$message->getMessageCode("ERR_ADMIN_DB");
						echo $message->displayMessage();
					}
					
				} else {
					$message->getMessageCode($result_validate)
					echo $message->displayMessage();
				}	
			} else {
				$sql = "INSERT INTO games (name,raw_name,icon,has_table) VALUES ('$new_game','$new_raw_name',NULL,$has_table)";
				if(mysqli_query($con,$sql))
				{
					$message->getMessageCode("SUC_ADMIN_CREATE_NEW_GAME");
					echo $message->displayMessage();
				} else {
					$message->getMessageCode("ERR_ADMIN_DB");
					echo $message->displayMessage();
				}
			}
		}
	}
?>