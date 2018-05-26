<?php
	include(dirname(__FILE__,3) . "/include/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");

	$ip = IP;

	if(isset($_REQUEST["status"]))
	{
		$status = $_REQUEST["status"];

		$user_id = getUserId($con,$ip);

		mysqli_query($con,"UPDATE status SET status = '$status' WHERE user_id = '$user_id'");

		$s_color = getStatusColor($con,$status);

		echo json_encode(array("color" => $s_color));
	}
?>