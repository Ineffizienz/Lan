<?php
	include(dirname(__FILE__,4). "/include/init/constant.php");
	include(INC . "connect.php");
	include(INIT . "get_parameters.php");
	include(CL . "message_class.php");

	$message = new message();

	$ac_id = $_REQUEST["ac_id"];
	$given_param = array($_REQUEST["trigger"], $_REQUEST["category"], $_REQUEST["visib"]);
	$set_param = getParamByAcID($con,$ac_id);
	$set_param = array_shift($set_param);

	if (($_REQUEST["trigger"] !== $set_param["ac_trigger"]) && ($_REQUEST["category"] !== $set_param["ac_categorie"]) && ($_REQUEST["visib"] !== $set_param["ac_visibility"]))
	{
		$sql = "UPDATE ac SET ac_trigger = '$trigger' AND ac_category = '$category' AND ac_visibility = '$visib' WHERE ID = '$ac_id'";
		if(mysqli_query($con,$sql))
		{
			$message->getMessageCode("SUC_ADMIN_UPDATE_PARAM");
			echo $message->displayMessage();
		} else {
			$message->getMessageCode("ERR_ADMIN_DB");
			echo $message->displayMessage();
		}
	} else {
		foreach ($set_param as $key=>$value)
		{
			$current_param = array_shift($given_param);

			if ($current_param !== $value)
			{
				$sql = "UPDATE ac SET $key = '$current_param' WHERE ID = '$ac_id'";
				if(mysqli_query($con,$sql))
				{
					$message->getMessageCode("SUC_ADMIN_UPDATE_PARAM");
					echo $message->displayMessage();
				} else {
					$message->getMessageCode("ERR_ADMIN_DB");
					echo $message->displayMessage() . mysqli_error($con);
				}
			}
		}
	}


?>