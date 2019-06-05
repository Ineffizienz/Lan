<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,3). "/include/admin_func.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");

	$message = new message();

	if(empty($_REQUEST["ac_name"]))
	{
		$message->getMessageCode("ERR_ADMIN_MISSING_AC_NAME");
		echo buildJSONOutput($message->displayMessage());
	} else {
		
		if(empty($_REQUEST["ac_visible"]))
		{
			$visib = "Unsichtbar";
		} else {
			$visib = "Sichtbar";
		}
		
		if(isset($_FILES["file"]["size"]) && !empty($_FILES["file"]["size"]))
		{
			$result_validate = validateImageFile($_FILES["file"]["size"],pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION)); //validates the ImageFile for its size and Imagetype
			if($result_validate == "1")
			{
				move_uploaded_file($_FILES["file"]["tmp_name"], AC . $_FILES["file"]["name"]);
				$path = $_FILES["file"]["name"];
				
				$title = $_REQUEST["ac_name"];
				$categorie = $_REQUEST["ac_cat"];
				$trigger = $_REQUEST["ac_trigger"];
				$text = $_REQUEST["ac_message"];

				$sql = "INSERT INTO ac (title,image_url,message,ac_category,ac_trigger,ac_visibility) VALUES ('$title','$path','$text','$categorie','$trigger','$visib')";

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
						$message->getMessageCode("SUC_ADMIN_CREATE_AC");
						echo buildJSONOutput($message->displayMessage());
					} else {
						$message->getMessageCode("ERR_ADMIN_DB");
						echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
					}
				} else {
					$message->getMessageCode("ERR_ADMIN_DB");
					echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
				}
				
			} else {
				$message->getMessageCode($result_validate);
				echo buildJSONOutput($message->displayMessage());
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
					$message->getMessageCode("SUC_ADMIN_CREATE_AC");
					echo buildJSONOutput($message->displayMessage());
				} else {
					$message->getMessageCode("ERR_ADMIN_DB");
					echo buildJSONOutput($message->displayMessage());
				}
			} else {
				$message->getMessageCode("ERR_ADMIN_DB");
				echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
			}			
		}		

	}

?>