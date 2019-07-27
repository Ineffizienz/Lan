<?php
    session_start();
    include(dirname(__FILE__,2) . "/init/constant.php");
    include(INC . "connect.php");
    include(CL . "message_class.php");
    include(INC . "function.php");

    $player_id = $_SESSION["player_id"];
    $message = new message();

    if(isset($_REQUEST["game_id"]))
    {
        $game_id = $_REQUEST["game_id"];
        $votedGames = getVotedGamesByPlayerId($con,$player_id);
        $start = date("Y-m-d H:i:s", strtotime("now"));
        $end = date("Y-m-d H:i:s", strtotime("+30 minutes"));

        if(empty($votedGames))
        {
            $sql = "INSERT INTO tm_vote ('game_id','player_id','starttime','endtime') VALUES ('$game_id','$player_id','$start','$end')";
            if(mysqli_query($con,$sql))
            {
                $message->getMessageCode("SUC_VOTED_FOR_TM");
                echo json_encode("message"=>$message->displayMessage());
            } else {
                $message->getMessageCode("ERR_DB");
                echo json_encode("message"=>$message->displayMessage());
            }
        } else {
            if(in_array($game_id,$votedGames))
            {
                $message->getMessageCode("ERR_ALLREADY_VOTED");
                echo json_encode("message"=>message->displayMessage());
            } else {
                $sql = "INSERT INTO tm_vote ('game_id','player_id','starttime','endtime') VALUES ('$game_id','$player_id','$start','$end')";
                if(mysqli_query($con,$sql))
                {
                    $message->getMessageCode("SUC_VOTED_FOR_TM");
                    echo json_encode("message"=>$message->displayMessage());
                } else {
                    $message->getMessageCode("ERR_DB");
                    echo json_encode("message"=>$message->displayMessage());
                }
            }
        }
    } else {
        $message->getMessageCode("ERR_NO_GAME_TO_VOTE_FOR");
        echo json_encode("message"=>$message->displayMessage());
    }
?>