<?php
include(dirname(__FILE__,4) . "/include/init/constant.php");
include(dirname(__FILE__,3) . "/include/admin_func.php");
include(INC . "connect.php");
include(CL . "message_class.php");


$message = new message();

if(isset($_REQUEST["account_id"]))
{
    $account_id = $_REQUEST["account_id"];
    $sql = "UPDATE account SET salt = NULL, verifier = NULL WHERE id = '$account_id'";
    if(mysqli_query($con,$sql))
    {
        $message->getMessageCode("SUC_ADMIN_PASSWORT_RESET");
        echo buildJSONOutput($message->displayMessage());
    } else {
        $message->getMessageCode("ERR_ADMIN_DB");
        echo buildJSONOutput($message->displayMessage());
    }
}

?>