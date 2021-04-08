<?php

include(dirname(__FILE__,4) . "/include/init/constant.php");
include(dirname(__FILE__,3) . "/include/admin_func.php");
include(INC . "connect.php");
include(CL . "message_class.php");

$message = new message();

if(isset($_REQUEST["map_id"]))
{
    $map_id = $_REQUEST["map_id"];

    $map_path = getMapImageById($con,$map_id);

    if(file_exists(MAP . $map_path))
    {
        unlink(MAP . $map_path);
    }

    $sql = "DELETE FROM game_maps WHERE ID = '$map_id'";
    if(mysqli_query($con,$sql))
    {
        $message->getMessageCode("SUC_ADMIN_DELETE_MAP");
        echo buildJSONOutput($message->displayMessage());
    } else {
        $message->getMessageCode("ERR_ADMIN_DB");
        echo buildJSONOutput($message->displayMessage());
    }
} else {
    $message->getMessageCode("ERR_ADMIN_NO_MAP_ID");
    echo buildJSONOutput($message->displayMessage());
}

?>