<?php
session_start();
include(dirname(__FILE__,4) . "/include/init/constant.php");
include(CL . "message_class.php");

$player_id = $_SESSION["player_id"];
$joint_player = getJointPlayer($con,$_REQUEST["tm_id"],$player_id);
?>