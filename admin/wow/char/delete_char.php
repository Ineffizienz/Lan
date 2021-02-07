<?php
include(dirname(__FILE__,4) . "/include/init/constant.php");
include(dirname(__FILE__,3) . "/include/admin_func.php");
include("../general/wow_handling.php");
include(INC . "connect.php");
include(CL . "message_class.php");

$message = new message();

if(isset($_REQUEST["char_name"]))
{
    $char_name = $_REQUEST["char_name"];
    $guid = getGUIDFromCharacters($con_char,$char_name);
    $account_id = getAccountIDByGUID($con_char,$guid);

    $del_char = deleteWoWCharacter($con_wow,$con_char,$account_id,$guid);

    $message->getMessageCode($del_char);
    echo buildJSONOutput($message->displayMessage());

} else {
    $message->getMessageCode("ERR_ADMIN_USAGE");
    echo buildJSONOutput($message->displayMessage());
}

?>