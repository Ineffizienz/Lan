<?php
include(dirname(__FILE__,4) . "/include/init/constant.php");
include(dirname(__FILE__,3) . "/include/admin_func.php");
include("../general/wow_handling.php");
include(INC . "connect.php");
include(CL . "message_class.php");

$message = new message();

if(isset($_REQUEST["char_name"]))
{
    $guid = getGUIDFromCharacters($con_char,$_REQUEST["char_name"]);

    $del_char = deleteWoWCharacter($con_wow,$con_char,$_REQUEST["account_id"],$guid);

    $message->getMessageCode($del_char);
    echo buildJSONOutput($message->displayMessage());

} else {
    $message->getMessageCode("ERR_ADMIN_USAGE");
    echo buildJSONOutput($message->displayMessage());
}

?>