<?php
include(dirname(__FILE__,4) . "/include/init/constant.php");
require_once INC . 'session.php';
include(INC . "connect.php");
include(INC . "function.php");
include(CL . "message_class.php");

$player_id = $_SESSION["player_id"];
$message = new message();

if(getJointPlayer($con,$_REQUEST["tm_id"],$player_id))
{
    $message->getMessageCode("ERR_ALLREADY_JOINT_TM");
    echo json_encode(array("message"=>$message->displayMessage()));
} else {
    $tm_id = $_REQUEST["tm_id"];

    $player_count = getPlayerCountTm($con,$tm_id);

    $end_register = strtotime(getTournamentEndRegister($con,$tm_id));

    if(time() <= $end_register)
    {
        $sql = "INSERT INTO tm_gamerslist (tm_id,player_id) VALUES ('$tm_id','$player_id')";
        if(mysqli_query($con,$sql))
        {
            $player_count++;
            $sql = "UPDATE tm SET player_count = '$player_count' WHERE ID = '$tm_id'";
            if(mysqli_query($con,$sql))
            {
                $message->getMessageCode("SUC_JOIN_TM");
                echo json_encode(array("message"=>$message->displayMessage()));
            } else {
                $message->getMessageCode("ERR_DB");
                echo json_encode(array("message"=>$message->displayMessage() . mysqli_error($con)));
            }
        } else {
            $message->getMessageCode("ERR_DB");
            echo json_encode(array("message"=>$message->displayMessage() . mysqli_error($con)));
        }
    } else {
        $message->getMessageCode("ERR_TM_TIME_UP");
        echo json_encode(array("message"=>$message->displayMessage()));
    }
}

?>