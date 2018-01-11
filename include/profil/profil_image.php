<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/include/init/constant.php");
	include(CL . "message_class.php");
	include(CL . "progress_class.php");
	include(INC . "connect.php");
	include(INC . "function.php");

	$message = new message();
	$achievement = new Progress();

	if($_FILES["file"]["size"] > 500000)
	{
		$message->getMessageCode("ERR_FILE_TO_HUGE");
		$achievement->getTrigger($con,IP,"Sir Brummel");
		echo json_encode(array("message" => $message->displayMessage(), "achievement" => $achievement->showAchievement()));

	} elseif ($_FILES["file"]["size"] < 5) {
		
		$message->getMessageCode("ERR_FILE_TO_SMALL");
		echo json_encode(array("message" => $message->displayMessage()));

	} else {
		$extension = pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION);

		if (($extension !== "jpg") && ($extension !== "png") && ($extension !== "gif") && ($extension !== "jpeg"))
		{
			$message->getMessageCode("ERR_NO_IMAGE_TYPE");
			$achievement->getTrigger($con,IP,"Sir Brummel");
			echo json_encode(array("message" => $message->displayMessage(), "achievement" => $achievement->showAchievement()));
		} else {
			
			$username = getSingleUsername($con,IP);

			$root_path = ROOT . "user/" . $username;

			if(file_exists($root_path))
			{

				$result = mysqli_query($con,"SELECT profil_image FROM player WHERE name = '$username'");
				while($row=mysqli_fetch_array($result))
				{
					$existing_file = $row["profil_image"];
				}

				if(file_exists(ROOT . $existing_file))
				{
					unlink(ROOT . $existing_file);
				}
				
				move_uploaded_file($_FILES["file"]["tmp_name"], $root_path . "/profil_image." . $extension);

				$path = "user/" . $username . "/profil_image." . $extension;
				$sql = "UPDATE player SET profil_image = '$path' WHERE name = '$username'";

				if(mysqli_query($con,$sql))
				{
					$message->getMessageCode("SUC_UPLOADED_IMAGE");
					echo json_encode(array("message" => $message->displayMessage(), "image" => $path));
				}

			} else {
				mkdir($root_path);

				move_uploaded_file($_FILES["file"]["tmp_name"], $root_path . "/profil_image." . $extension);

				$path = "user/" . $username . "/profil_image." . $extension;

				$sql = "UPDATE player SET profil_image = '$path' WHERE name = '$username'";

				if(mysqli_query($con,$sql))
				{
					move_uploaded_file($_FILES["file"]["tmp_name"], $path);
					$message->getMessageCode("SUC_UPLOADED_IMAGE");
					echo json_encode(array("message" => $message->displayMessage()));
				} else {
					$message->getMessageCode("ERR_DB");
					echo json_encode(array("message" => $message->displayMessage()));
				}
			}

		}
	}
?>