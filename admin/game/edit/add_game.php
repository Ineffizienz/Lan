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
			
			if(isset($_FILES["game_icon"]["size"]) && !empty($_FILES["game_icon"]["size"]))
			{
				$result_validate = validateImageFile($_FILES["game_icon"]["size"],pathinfo($_FILES["game_icon"]["name"],PATHINFO_EXTENSION)); //validates the ImageFile for its size and Imagetype
				if($result_validate == "1")
				{
					if(isset($_FILES["game_banner"]["size"]) && !empty($_FILES["game_banner"]["size"]))
					{
						$result_validate_banner = validateImageFile($_FILES["game_banner"]["size"],pathinfo($_FILES["game_banner"]["name"],PATHINFO_EXTENSION));
						if($result_validate_banner == "1")
						{
							move_uploaded_file($_FILES["game_icon"]["tmp_name"], ICON . $_FILES["game_icon"]["name"]);
							move_uploaded_file($_FILES["game_banner"]["tmp_name"], BANNER . $_FILES["game_banner"]["name"]);
							$path_icon = $_FILES["game_icon"]["name"];
							$path_banner = $_FILES["game_banner"]["name"];

							$sql = "INSERT INTO games (name,raw_name,icon,banner,has_table) VALUES ('$new_game','$new_raw_name','$path_icon','$path_banner','$has_table')";
							if(mysqli_query($con,$sql))
							{
								$message->getMessageCode("SUC_ADMIN_CREATE_NEW_GAME");
								echo $message->displayMessage();
							} else {
								$message->getMessageCode("ERR_ADMIN_DB");
								echo $message->displayMessage() . mysqli_error($con);
							}
						} else {
							$message->getMessageCode($result_validate);
							echo $message->displayMessage();
						}
					} else {
						move_uploaded_file($_FILES["game_icon"]["tmp_name"], ICON . $_FILES["game_icon"]["name"]);
						$path_icon = $_FILES["game_icon"]["name"];

						$sql = "INSERT INTO games (name,raw_name,icon,has_table) VALUES ('$new_game','$new_raw_name','$path_icon','$has_table')";
						if(mysqli_query($con,$sql))
						{
							$message->getMessageCode("SUC_ADMIN_CREATE_NEW_GAME");
							echo $message->displayMessage();
						} else {
							$message->getMessageCode("ERR_ADMIN_DB");
							echo $message->displayMessage() . mysqli_error($con);
						}
					}
				} else {
					$message->getMessageCode($result_validate);
					echo $message->displayMessage();
				}	
			} else {
				if(isset($_FILES["game_banner"]["size"]) && !empty($_FILES["game_banner"]["size"]))
				{
					$result_validate_banner = validateImageFile($_FILES["game_banner"]["size"],pathinfo($_FILES["game_banner"]["name"],PATHINFO_EXTENSION));
					if($result_validate_banner == "1")
					{
						move_uploaded_file($_FILES["game_banner"]["tmp_name"], BANNER . $_FILES["game_banner"]["name"]);
						$path_banner = $_FILES["game_banner"]["name"];

						$sql = "INSERT INTO games (name,raw_name,banner,has_table) VALUES ('$new_game','$new_raw_name','$path_banner','$has_table')";
						if(mysqli_query($con,$sql))
						{
							$message->getMessageCode("SUC_ADMIN_CREATE_NEW_GAME");
							echo $message->displayMessage();
						} else {
							$message->getMessageCode("ERR_ADMIN_DB");
							echo $message->displayMessage() . mysqli_error($con);
						}
					} else {
						$message->getMessageCode($result_validate);
						echo $message->displayMessage();
					}
				} else {
					$sql = "INSERT INTO games (name,raw_name,has_table) VALUES ('$new_game','$new_raw_name','$has_table')";
					if(mysqli_query($con,$sql))
					{
						$message->getMessageCode("SUC_ADMIN_CREATE_NEW_GAME");
						echo $message->displayMessage();
					} else {
						$message->getMessageCode("ERR_ADMIN_DB");
						echo $message->displayMessage() . mysqli_error($con);
					}
				}
			}
		}
	}
?>