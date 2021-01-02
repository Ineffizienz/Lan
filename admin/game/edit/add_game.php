<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,3). "/include/admin_func.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");

	$message = new message();
	
	if(empty($_REQUEST["game"]))
	{
		$message->getMessageCode("ERR_ADMIN_NO_GAME");
		echo buildJSONOutput($message->displayMessage());
	} else {
		$result = mysqli_query($con,"SELECT name FROM games");
		while($row=mysqli_fetch_array($result))
		{
			$exist_games[] = $row["name"];
		}

		if(in_array($_REQUEST["game"],$exist_games))
		{
			$message->getMessageCode("ERR_ADMIN_GAME_EXISTS");
			echo buildJSONOutput($message->displayMessage());
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
				move_uploaded_file($_FILES["game_icon"]["tmp_name"], ICON . $_FILES["game_icon"]["name"]);
				$path_icon = $_FILES["game_icon"]["name"];
			} else {
				$path_icon = NULL;
			}

			if(isset($_FILES["game_banner"]["size"]) && !empty($_FILES["game_banner"]["size"]))
			{
				move_uploaded_file($_FILES["game_banner"]["tmp_name"], BANNER . $_FILES["game_banner"]["name"]);
				$path_banner = $_FILES["game_banner"]["name"];
			} else {
				$path_banner = NULL;
			}
			
			$sql = "INSERT INTO games (name,raw_name,icon,banner,has_table) VALUES ('$new_game','$new_raw_name','$path_icon','$path_banner','$has_table')";
			if(mysqli_query($con,$sql))
			{
				$message->getMessageCode("SUC_ADMIN_CREATE_NEW_GAME");
				echo buildJSONOutput(array($message->displayMessage(),$_REQUEST["p_element"],$_REQUEST["c_element"],0));
			} else {
				$message->getMessageCode("ERR_ADMIN_DB");
				echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
			}
		}
	}
?>