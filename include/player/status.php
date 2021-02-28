<?php
	include(dirname(__FILE__,3) . "/include/init/constant.php");
	require_once INC . 'session.php';
	include(INC . "connect.php");
	include(INC . "function.php");
	include(CL . "player_class.php");

	$player = new Player($con,$_SESSION["player_id"]);

	if(isset($_REQUEST["status"]))
	{
		$status = $_REQUEST["status"];

		$player->setNewStatus($status);

		$s_color = getStatusColor($con,$status);

		echo json_encode(array("color" => $s_color));
	}
?>