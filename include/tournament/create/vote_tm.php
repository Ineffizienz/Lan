<?php
    session_start();
    include(dirname(__FILE__,3) . "/init/constant.php");
    include(INC . "connect.php");
    include(CL . "message_class.php");
    include(INC . "function.php");

    $player_id = $_SESSION["player_id"];
    $message = new message();

    if(isset($_REQUEST["game_id"]))
    {
        $game_id = $_REQUEST["game_id"];
        $votedGames = getVotedGames($con,$game_id);
        $tournamentVotes = getVotedGamesByPlayerId($con,$player_id);
        $start = date("Y-m-d H:i:s", strtotime("now"));
        $end = date("Y-m-d H:i:s", strtotime("+30 minutes"));

        if(empty($votedGames))
        {
            $sql = "INSERT INTO tm_vote (game_id, vote_count, starttime, endtime) VALUES ('$game_id','1','$start','$end')";
            var_dump($sql);
            if(mysqli_query($con,$sql))
            {
                $tm_vote_id = getTournamentVoteId($con,$game_id);
                $sql = "INSERT INTO tm_vote_player (tm_vote_id, player_id) VALUES ('$tm_vote_id','$player_id')";
                if(mysqli_query($con,$sql))
                {
                    $message->getMessageCode("SUC_TM_VOTED_FOR");
                    echo json_encode(array("message"=>$message->displayMessage()));
                } else {
                    $message->getMessageCode("ERR_DB");
                    echo json_encode(array("message"=>mysqli_error($con)));
                }
            } else {
                $message->getMessageCode("ERR_DB") . mysqli_error($con);
                echo json_encode(array("message"=>mysqli_error($con)));
            }
        } else {
            if(empty($tournamentVotes))
            {
                $voteCount = $votedGames["vote_count"] + 1;
                $tm_vote_id = $votedGames["ID"];
                $sql = "UPDATE tm_vote SET vote_count = '$voteCount' WHERE game_id = '$game_id'";
                if(mysqli_query($con,$sql))
                {
                    $sql = "INSERT INTO tm_vote_player (tm_vote_id, player_id) VALUES ('$tm_vote_id','$player_id')";
                    if(mysqli_query($con,$sql))
                    {
                        $message->getMessageCode("SUC_TM_VOTED_FOR");
                        echo json_encode(array("message"=>$message->displayMessage()));
                    } else {
                        $message->getMessageCode("ERR_DB") . mysqli_error($con);
                        echo json_encode(array("message"=>mysqli_error($con)));
                    }
                }
            } else {
                $message->getMessageCode("ERR_ALLREADY_VOTED");
                echo json_encode(array("message"=>$message->displayMessage()));
            }
        }
    } else {
        $message->getMessageCode("ERR_NO_GAME_TO_VOTE_FOR");
        echo json_encode(array("message"=>$message->displayMessage()));
    }
?>