<?php

include(dirname(__FILE__,4) . "/include/init/constant.php");
include(INC . "connect.php");
include(CL . "message_class.php");

$message = new message();

if (isset($_REQUEST["game"]))
{
    if (empty($_REQUEST["game"]))
    {
        $message->getMessageCode("ERR_INTERN_#2");
        echo json_encode(array("message" => $message->displayMessage()));
    } else {
        $tm_game = $_REQUEST["game"];
        $sql = "INSERT INTO tm (game) VALUES ('$tm_game')";
        /*if(mysqli_query($con,$sql))
        {
            $message->getMessageCode("SUC_ADMIN_CREATE_TM");
            echo json_encode(array("message" => $message->displayMessage()));
        } else {
            $message->getMessageCode("ERR_ADMIN_CREATE_TM");
            echo json_encode(array("message" => $message->displayMessage() . mysqli_error($con)));
        }*/
        if(!empty($sql))
        {
            $message->getMessageCode("SUC_ADMIN_INTERN_#1");
            echo json_encode(array("message" => $message->displayMessage()));
        }
    }

} else {
    $message->getMessageCode("ERR_ADMIN_NO_GAME_SELECTED");
    echo json_encode(array("message" => $message->displayMessage()));
}

?>