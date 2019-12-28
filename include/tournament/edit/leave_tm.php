<?php
    session_start();
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(INC . "connect.php");
    include(INC . "function.php");
    include(CL . "message_class.php");

    $message = new message();

    $player_id = $_SESSION["player_id"];
    $tm_id = $_REQUEST["tm_id"];

    if(getSinglePlayerIDFromGamerslist($con,$tm_id,$player_id))
    {
        $player_count = getPlayerCountTm($con,$tm_id);
        $player_count = $player_count-1;
        $sql = "UPDATE tm SET player_count = $player_count WHERE ID = '$tm_id'";
        if(mysqli_query($con,$sql))
        {
            $sql = "DELETE FROM tm_gamerslist WHERE tm_id = '$tm_id' AND player_id = '$player_id'";
            if(mysqli_query($con,$sql))
            {
                $message->getMessageCode("SUC_LEAVE_TOURNAMENT");
                echo json_encode(array("message"=>$message->displayMessage()));
            } else {
                $message->getMessageCode("ERR_DB");
                echo json_encode(array("message"=>$message->displayMessage() . mysqli_error($con)));
            }
        } else {
            $message->getMessageCode("ERR_DB");
            echo json_encode(array("message"=>$message->displayMessage()));
        }
    } else {
        $message->getMessageCode("ERR_LEAVE_TOURNAMENT");
        echo json_encode(array("message"=>$message->displayMessage()));
    }
?>