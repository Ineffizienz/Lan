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
			move_uploaded_file($_FILES["file"]["tmp_name"], AC . $_FILES["file"]["name"]);
			$path = $_FILES["file"]["name"];				
		} else {
			$path = NULL;
		}

		$title = $_REQUEST["ac_name"];
		$categorie = $_REQUEST["ac_cat"];
		$trigger = $_REQUEST["ac_trigger"];
		$text = $_REQUEST["ac_message"];
		
		$message->getMessageCode($ac->setNewAchievement($title,$path,$text,$trigger,$category,$visibility));
		echo buildJSONOutput($message->displayMessage());

	}

?>