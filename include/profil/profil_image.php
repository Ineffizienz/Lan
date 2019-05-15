<?php
	session_start();
	include(dirname(__FILE__,3) . "/include/init/constant.php");
	include(CL . "message_class.php");
	include(CL . "progress_class.php");
	include(INC . "connect.php");
	include(INC . "function.php");

	$message = new message();
	$achievement = new Progress();
	$player_id = $_SESSION["player_id"];
	
	$resultValidateImage = validateImage($_FILES["file"]["size"],pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));
	if($resultValidateImage == "1")
	{
	
		$username = getSingleUsername($con,$player_id);
		$extension = pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION);
		$root_path = ROOT . "/user/" . $username;

		if(file_exists($root_path))
		{

			$result = mysqli_query($con,"SELECT profil_image FROM player WHERE ID = '$player_id'");
			while($row=mysqli_fetch_array($result))
			{
				$existing_file = $row["profil_image"];
			}

			if(file_exists($root_path . $existing_file))
			{
				unlink($root_path . $existing_file);
			}
			
			move_uploaded_file($_FILES["file"]["tmp_name"], $root_path . "/profil_image." . $extension);

			$path = "user/" . $username . "/profil_image." . $extension;
			$sql = "UPDATE player SET profil_image = '$path' WHERE ID = '$player_id'";

			if(mysqli_query($con,$sql))
			{
				$message->getMessageCode("SUC_UPLOADED_IMAGE");
				echo json_encode(array("message" => $message->displayMessage(), "image" => $path));
			}

		} else {
			mkdir($root_path);

			move_uploaded_file($_FILES["file"]["tmp_name"], $root_path . "/profil_image." . $extension);

			$path = "user/" . $username . "/profil_image." . $extension;

			$sql = "UPDATE player SET profil_image = '$path' WHERE ID = '$player_id'";

			if(mysqli_query($con,$sql))
			{
				$message->getMessageCode("SUC_UPLOADED_IMAGE");
				echo json_encode(array("message" => $message->displayMessage()));
			} else {
				$message->getMessageCode("ERR_DB");
				echo json_encode(array("message" => $message->displayMessage()));
			}
		}
		
	} else {
		$message->getMessageCode($resultValidateImage);
		$achievement->getTrigger($con,IP,"Sir Brummel");
		echo json_encode(array("message" => $message->displayMessage(), "achievement" => $achievement->showAchievement()));
	}
?>