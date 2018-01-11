<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/include/init/constant.php");
	include(INC . "connect.php");
	include(INC . "function.php");

	$ip = IP;

	if(isset($_REQUEST["status"]))
	{
		$status = $_REQUEST["status"];

		$user_id = getUserId($con,$ip);

		mysqli_query($con,"UPDATE status SET status = '$status' WHERE user_id = '$user_id'");

		$s_color = getStatusColor($con,$status);

		echo $s_color;
	}
?>