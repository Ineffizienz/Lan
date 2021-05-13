<?php
include(dirname(__FILE__,3) . "/include/init/constant.php");
require_once INC . 'session.php';
include(INC . "connect.php");
include(INIT . "get_parameters.php");

$event_result = array();

switch ($_REQUEST["func_name"]) {
   case "getPlayerID":
        $event_result["result"] = getGamerslistIdBySinglePlayerId($con,$_SESSION["player_id"]);
    break;
}

echo json_encode($event_result);
?>