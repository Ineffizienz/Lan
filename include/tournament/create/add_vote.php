<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
	require_once INC . 'session.php';
    include(CL . "message_class.php");
    include(CL . "player_class.php");
    include(INC . "connect.php");
    include(INC . "function.php");

    $running_votes = getVoteIds($con);
    $vote_id = $_REQUEST["vote_id"];
    $message = new message();
    $player = new Player($con,$_SESSION["player_id"]);

    if(in_array($vote_id,$running_votes))
    {
        if(getPlayerVotes($con,$player->getPlayerId(),$vote_id))
        {
            $message->getMessageCode("ERR_ALLREADY_VOTED");
            echo json_encode(array("message"=>$message->displayMessage()));
        } else {
            $tm_vote = getVoteById($con,$vote_id);
            $tm_vote_count = $tm_vote["vote_count"] + 1;

            $sql = "INSERT INTO tm_vote_player (tm_vote_id, player_id) VALUES ('$vote_id','$player->getPlayerId()')";
            if(mysqli_query($con,$sql))
            {
                $sql = "UPDATE tm_vote SET vote_count = '$tm_vote_count' WHERE ID = '$vote_id'";
                if(mysqli_query($con,$sql))
                {
                    $message->getMessageCode("SUC_TM_VOTED_FOR");
                    echo json_encode(array("message"=>$message->displayMessage(),"vote_id" => $vote_id));
                } else { 
                    $message->getMessageCode("ERR_DB");
                    echo json_encode(array("message"=>$message->displayMessage()));
                }
            } else {
                $message->getMessageCode("ERR_DB");
                echo json_encode(array("message"=>$message->displayMessage()));
            }
        }

    } else {
        $message->getMessageCode("ERR_VOTE_DELETED");
        echo json_encode(array("message"=>$message->displayMessage()));
    }
?>