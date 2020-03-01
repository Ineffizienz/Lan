<?php
	include(dirname(__FILE__,3) . "/include/init/constant.php");
	require_once INC . 'session.php';
	include(INC . "connect.php");
	include(INC . "function.php");

	$player_id = $_SESSION["player_id"];

	if(isset($_REQUEST["status"]))
	{
		$status = $_REQUEST["status"];

		mysqli_query($con,"UPDATE status SET status = '$status' WHERE user_id = '$player_id'");

		$s_color = getStatusColor($con,$status);

		echo json_encode(array("color" => $s_color));
	}
?>