<?php

include(dirname(__FILE__,3) . "/include/init/constant.php");
include(INC . "connect.php");
include(CL . "message_class.php");
include("new_reg.php");

$message = new message();

if(isset($_REQUEST["new_password"]))
{
    $new_set = GetSRP6RegistrationData($_REQUEST["account_name"],$_REQUEST["new_password"]);
    $account_name = strtoupper($_REQUEST["account_name"]);

    $sql = "UPDATE auth.account SET salt = '$new_set[0]', verifier = '$new_set[1]' WHERE username = '$account_name'";
    if(mysqli_query($con_wow,$sql))
    {
        $message->getMessageCode("SUC_SET_NEW_PASSWORD");
        echo json_encode(array($message->displayMessage()));
    } else {
        $message->getMessageCode("ERR_DB");
        echo json_encode(array($message->displayMessage().mysli_error($con_wow)));
    }
} else {
    $message->getMessageCode("ERR_MISSING_PASSWORD");
    echo json_encode(array($message->displayMessage()));
}

?>