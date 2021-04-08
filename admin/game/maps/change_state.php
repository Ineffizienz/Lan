<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(dirname(__FILE__,3) . "/include/admin_func.php");
    include(INC . "connect.php");
    include(CL . "message_class.php");

    $message = new message();

    if(isset($_REQUEST["map_state"]))
    {
        $map_id = $_REQUEST["map_id"];
        
        if(empty($_REQUEST["map_state"]))
        {
            $map_state = 0;
        } else {
            $map_state = 1;
        }

        $sql = "UPDATE game_maps SET selectable = '$map_state' WHERE ID = '$map_id'";
        if(mysqli_query($con,$sql))
        {
            $message->getMessageCode("SUC_ADMIN_CHANGE_STATE");
            echo buildJSONOutput($message->displayMessage());
        } else {
            $message->getMessageCode("ERR_ADMIN_DB");
            echo buildJSONOutput($message->displayMessage());
        }
    } else {
        $message->getMessageCode("ERR_ADMIN_NO_STATE");
        echo buildJSONOutput($message->displayMessage());
    }
?>