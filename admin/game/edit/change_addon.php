<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(dirname(__FILE__,3) . "/include/admin_func.php");
    include(INC . "connect.php");
    include(CL . "message_class.php");

    $message = new message();
    
    if(isset($_REQUEST["game_id"]) && isset($_REQUEST["addon"]))
    {
        $game_id = $_REQUEST["game_id"];
        $addon = $_REQUEST["addon"];

        $sql = "UPDATE games SET addon = '$addon' WHERE ID = '$game_id'";
        if(mysqli_query($con,$sql))
        {
            $message->getMessageCode("SUC_ADMIN_UPDATE_ADDON");
            echo buildJSONOutput($message->displayMessage());
        } else {
            $message->getMessageCode("ERR_ADMIN_INTERN_#4");
            echo buildJSONOutput($message->displayMessage());
        }

    } else {
        $message->getMessageCode("ERR_ADMIN_INTERN_#2");
        echo buildJSONOutput($message->displayMessage());
    }
?>