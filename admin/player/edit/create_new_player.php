<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/include/connect.php");
	include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/include/function.php");
	include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/class/message_class.php");

	$message = new message();
	$last_ip = getLastIp($con);
	$c_name = $_REQUEST["cover"];

	if(!empty($c_name))
	{
		if (empty($last_ip))
		{
			$new_ip = "192.168.0.89";
		} else {
			for ($i=1;$i<=$count;$i++)
			{
				$ip = explode(".",$last_ip);

				$last_number = $ip[3]+1;

				$new_ip = substr_replace($last_ip, $last_number, 10);
			}			
		}
		if (isset($new_ip))
		{
			$sql = "INSERT INTO player (name,ip,team_id,team_captain,ticket_id,ticket_active,first_login) VALUES ('$c_name','$new_ip',NULL,NULL,NULL,NULL,'1')";
			if(mysqli_query($con,$sql))
			{
				$message->getMessageCode("SUC_NEW_PLAYER");
				echo $message->displayMessage();
			} else {
				echo mysqli_error($con);
			}	
		} else {
			$message->getMessageCode("ERR_INTERN_#1");
			echo $message->displayMessage();
		}
	} else {
		$message->getMessageCode("ERR_NO_COVERNAME_ADMIN");
		echo $message->displayMessage();
	}

?>