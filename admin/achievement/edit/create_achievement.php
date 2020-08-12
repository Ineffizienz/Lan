<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,3). "/include/admin_func.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");
	include(CL . "achievement_class.php");

	$message = new message();
	$ac = new Achievement($con);

	if(empty($_REQUEST["ac_name"]))
	{
		$message->getMessageCode("ERR_ADMIN_MISSING_AC_NAME");
		echo buildJSONOutput($message->displayMessage());
	} else {
		
		if(empty($_REQUEST["ac_visible"]))
		{
			$visibility = "Unsichtbar";
		} else {
			$visibility = "Sichtbar";
		}
		
		if(isset($_FILES["file"]["size"]) && !empty($_FILES["file"]["size"]))
		{
			$result_validate = validateImageFile($_FILES["file"]["size"],pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION)); //validates the ImageFile for its size and Imagetype
			if($result_validate == "1")
			{
				move_uploaded_file($_FILES["file"]["tmp_name"], AC . $_FILES["file"]["name"]);
				$path = $_FILES["file"]["name"];
				
				$title = $_REQUEST["ac_name"];
				$category = $_REQUEST["ac_cat"];
				$trigger = $_REQUEST["ac_trigger"];
				$text = $_REQUEST["ac_message"];

				$message->getMessageCode($ac->setNewAchievement($title,$path,$text,$category,$trigger,$visiblity));
				echo buildJSONOutput($message->displayMessage());
				
			} else {
				$message->getMessageCode($result_validate);
				echo buildJSONOutput($message->displayMessage());
			}	
		} else {

			$title = $_REQUEST["ac_name"];
			$categorie = $_REQUEST["ac_cat"];
			$trigger = $_REQUEST["ac_trigger"];
			$text = $_REQUEST["ac_message"];
			
			$message->getMessageCode($ac->setNewAchievement($title,NULL,$text,$trigger,$category,$visibility));
			echo buildJSONOutput($message->displayMessage());		
		}		

	}

?>