<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/Lan_Git/include/init/constant.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");

	$message = new message();

	if(empty($_REQUEST["ac_name"]))
	{
		$message->getMessageCode("ERR_MISSING_AC_NAME");
		echo $message->displayMessage();
	} else {
		
		if(empty($_REQUEST["ac_visible"]))
		{
			$visib = "0";
		} else {
			$visib = "1";
		}

		if(isset($_FILES["file"]["size"]) && ($_FILES["file"]["size"] != 0))
		{
			if ($_FILES["file"]["size"] > 500000)
			{
				$message->getMessageCode("ERR_FILE_SIZE");
				echo $message->displayMessage();
			} else {
				$extension = pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION);
				if (($extension !== "jpg") && ($extension !== "gif") && ($extension !== "png") && ($extension !== "jpeg"))
				{
					$message->getMessageCode("ERR_NO_IMAGE");
					echo $message->displayMessage();
				} else {
					move_uploaded_file($_FILES["file"]["tmp_name"], dirname(__FILE__,4) . "/images/achievements/" . $_FILES["file"]["name"]);
					$path = $_FILES["file"]["name"];

					$title = $_REQUEST["ac_name"];
					$categorie = $_REQUEST["ac_cat"];
					$trigger = $_REQUEST["ac_trigger"];
					$text = $_REQUEST["ac_message"];

					$sql = "INSERT INTO ac (title,image_url,message,ac_trigger,ac_categorie,ac_visibility) VALUES ('$title','$path','$text','$trigger','$categorie','$visib')";

					if(mysqli_query($con,$sql))
					{
						$result = mysqli_query($con, "SELECT ID FROM ac WHERE title = '$title'");
						while($row=mysqli_fetch_array($result))
						{
							$new_ac = $row["ID"];
						}

						$sql = "INSERT INTO ac_player (ac_id) VALUES ('$new_ac')";
						if(mysqli_query($con,$sql))
						{
							$message->getMessageCode("SUC_CREATE_AC");
							echo $message->displayMessage();
						} else {
							echo mysqli_error($con);
							$message->getMessageCode("ERR_DB");
							echo $message->displayMessage();
						}
						
					} else {
						echo mysqli_error($con);
						$message->getMessageCode("ERR_DB");
						echo $message->displayMessage();
					}
				}
			}
		} else {

			$title = $_REQUEST["ac_name"];
			$categorie = $_REQUEST["ac_cat"];
			$trigger = $_REQUEST["ac_trigger"];
			$text = $_REQUEST["ac_message"];
			
			$sql = "INSERT INTO ac (title,image_url,message,ac_trigger,ac_categorie,ac_visibility) VALUES ('$title',NULL,'$text','$trigger','$categorie','$visib')";

			if(mysqli_query($con,$sql))
			{
				$result = mysqli_query($con, "SELECT ID FROM ac WHERE title = '$title'");
				while($row=mysqli_fetch_array($result))
				{
					$new_ac = $row["ID"];
				}

				$sql = "INSERT INTO ac_player (ac_id) VALUES ('$new_ac')";
				if(mysqli_query($con,$sql))
				{
					$message->getMessageCode("SUC_CREATE_AC");
					echo $message->displayMessage();
				} else {
					echo mysqli_error($con);
					$message->getMessageCode("ERR_DB");
					echo $message->displayMessage();
				}
			} else {
				$message->getMessageCode("ERR_DB");
				echo mysqli_error($con);
				echo $message->displayMessage();
			}				
			
		}		

	}

?>