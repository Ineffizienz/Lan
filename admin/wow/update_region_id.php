<?php
include(dirname(__FILE__,3) . "/include/init/constant.php");
include(dirname(__FILE__,2) . "/include/admin_func.php");
include(INC . "connect.php");
include(CL . "message_class.php");

$message = new message();


if(isset($_REQUEST["new_region_id"]))
{
    $region_id = $_REQUEST["region_id"];
    $new_region_id = $_REQUEST["new_region_id"];
    $sql = "UPDATE wow_region SET region_id = '$new_region_id' WHERE region_id = '$region_id'";
    
    if(mysqli_query($con,$sql))
    {
        $message->getMessageCode("SUC_ADMIN_UPDATE_RI");
        echo buildJSONOutput(array($message->displayMessage(),$_REQUEST["p_element"],$_REQUEST["c_element"],$new_region_id));
    } else {
        $message->getMessageCode("ERR_ADMIN_DB");
        echo buildJSONOutput($message->displayMessage());
    }
} else {
    $message->getMessageCode("ERR_ADMIN_NO_NEW_RI");
    echo buildJSONOutput($message->displayMessage());
}
?>