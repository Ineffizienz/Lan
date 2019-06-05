<?php
	include(dirname(__FILE__,4). "/include/init/constant.php");
	include(dirname(__FILE__,3) . "/include/admin_func.php");
	include(INC . "connect.php");
	include(INIT . "get_parameters.php");
	include(CL . "message_class.php");

	$message = new message();

	$ac_id = $_REQUEST["ac_id"];

	$param = $_REQUEST["param"];
	$set_param = getParamByAcID($con,$ac_id);
	$set_param = array_shift($set_param);

	if(array_key_exists($param,$set_param) && ($_REQUEST["param_val"] !== $set_param[$param]))
	{
		$param_val = $_REQUEST["param_val"];
		$sql = "UPDATE ac SET $param = '$param_val' WHERE ID = '$ac_id'";
		if(mysqli_query($con,$sql))
		{
			$message->getMessageCode("SUC_ADMIN_UPDATE_PARAM");
			echo buildJSONOutput($message->displayMessage());
		} else {
			$message->getMessageCode("ERR_ADMIN_DB");
			echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
		}
	} else {
		echo "Failed.";
	}


?>