<?php
include(dirname(__FILE__,3) . "/include/init/constant.php");
include(dirname(__FILE__,2) . "/include/admin_func.php");
include(INC . "connect.php");
include(CL . "message_class.php");

$message = new message();

if(isset($_REQUEST["region_id"]))
{
    $region_id = $_REQUEST["region_id"];
    $sql = "DELETE FROM wow_region WHERE region_id = '$region_id'";

    if(mysqli_query($con,$sql))
    {
        $message->getMessageCode("SUC_ADMIN_DELETE_REGION");
        echo buildJSONOutput(array($message->displayMessage(),$_REQUEST["p_element"],$_REQUEST["c_element"],0));
    } else {
        $message->getMessageCode("ERR_ADMIN_DB");
        echo buildJSONOutput($message->displayMessage());
    }

} else {
    $message->getMessageCode("ERR_ADMIN_REGION_DOES_NOT_EXISTS");
    echo buildJSONOutput($message->displayMessage());
}
?>