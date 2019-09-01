<?php
    session_start();
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(CL . "message_class.php");
    include(INC . "connect.php");
    include(INIT . "get_parameters.php");
    include(INC . "function.php");

    $running_votes = getVoteIds($con);
    $player_id = $_SESSION["player_id"];

    if(in_array($_REQUEST["vote_id"],$running_votes))
    {
        if(getPlayerVotes($con,$vote_id))
        {
            $message->getMessageCode("ERR_ALLREADY_VOTED");
            echo json_encode("message"=>$message->displayMessage());
        } else {
            // TODO
        }

    } else {
        $message->getMessageCode("ERR_VOTE_DELETED");
        echo json_encode("message"=>$message->displayMessage());
    }
?>