<?php
	include(dirname(__FILE__,4) . "/include/init/constant.php");
	include(dirname(__FILE__,4) . "/include/init/get_parameters.php");
	include(dirname(__FILE__,3) . "/include/admin_func.php");
	include(INC . "connect.php");
	include(CL . "message_class.php");
	include(CL . "player_class.php");

	$message = new message();
	$player = new Player($con,0);
	$last_ip = getLastIp($con);
	$c_name = $_REQUEST["c_name"];

	if(!empty($c_name))
	{
		if (empty($last_ip))
		{
			$new_ip = "192.168.0.89";
		} else {
			$ip = explode(".",$last_ip);

			$last_number = $ip[3]+1;

			$new_ip = substr_replace($last_ip, $last_number, 10);		
		}
		if (isset($new_ip))
		{
			$message->getMessageCode($player->setNewPlayer($c_name,$new_ip));
			echo buildJSONOutput(array($message->displayMessage(),$_REQUEST["p_element"],$_REQUEST["c_element"]));	
		} else {
			$message->getMessageCode("ERR_ADMIN_INTERN_#1");
			echo buildJSONOutput($message->displayMessage());
		}
	} else {
		$message->getMessageCode("ERR_ADMIN_NO_COVERNAME");
		echo buildJSONOutput($message->displayMessage());
	}

?>