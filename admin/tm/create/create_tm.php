<?php
/* Notizen
        - Vote nach Start des Turniers bereinigen!
        - Remove max_player from script
*/
include(dirname(__FILE__,4) . "/include/init/constant.php");
include(dirname(__FILE__,3) . "/include/admin_func.php");
include(INC . "connect.php");
include(CL . "message_class.php");

$message = new message();

if(isset($_REQUEST["game_id"]))
{
    if(!isset($_REQUEST["vote_id"]))
    {
        $vote_id = 0;
    } else {
        $vote_id = $_REQUEST["vote_id"];
    }

    $message_text = setUpNewTournament($con,$vote_id,$_REQUEST["game_id"],$_REQUEST["tm_time_from"],$_REQUEST["tm_time_to"],$_REQUEST["mode"],$_REQUEST["mode_details"]);
    
    $message->getMessageCode($message_text);
    echo buildJSONOutput(array($message->displayMessage(),"#tm_maintain","#tm_list"));
} else {
    $message->getMessageCode("ERR_ADMIN_EMPTY_PARAM");
    echo buildJSONOutput($message->displayMessage());
}

?>