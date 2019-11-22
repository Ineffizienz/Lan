<?php
    session_start();
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(INC. "connect.php");
    include(INC. "function.php");
    include(CL . "message_class.php");

    $message = new message();
    $player_id = $_SESSION["player_id"];
    $tm_id = $_REQUEST["tm_id"];

    $gamerslist_id = getGamerslistIdByPlayerId($con,$player_id);
    if(getPairIdByGamerslistId($con,$gamerslist_id,$tm_id))
    {
        $matches_id = getSingleMatchesIdFromPaarung($con,$gamerslist_id,$tm_id);
        $match_id = getMatchIdFromMatches($con,$matches_id);
        $result_1 = $_REQUEST["result_1"];
        $result_2 = $_REQUEST["result_2"];

        $sql = "UPDATE tm_match SET result_team1 = '$result_1', result_team2 = '$result_2' WHERE ID = '$match_id'";
        if(mysqli_query($con,$sql))
        {
            $pair_id = getSinglePairIdByMatches($con,$matches_id);

            $message->getMessageCode("SUC_ENTER_RESULT");
            echo json_encode($message->displayMessage());
        } else {
            $message->getMessageCode("ERR_DB");
            echo json_encode(array($message->displayMessage() . mysqli_error($con)));
        }
    } else {
        $message->getMessageCode("ERR_INCORRECT_MATCH");
        echo json_encode(array($message->displayMessage() . mysqli_error($con)));
    }

    /*
    $pair_ids = getPairIdsByTm($con,$tm_id);
    foreach ($pair_ids as $pair_id)
    {
        if(!getSuccessorIdByPair($con,$pair_id))
        {
            $result_pair = getResultPair($con,$gamerslist_id,$tm_id);
            
            $sql = INSERT INTO tm_paarung (team_1, team_2, tournament) VALUES (NULL, NULL, '$tm_id');
            if(mysqli_query($con,$sql))
            {
                $new_pair_id = getNewPairId($con);
                $sql = "UPDATE tm_paarung SET successor = '$new_pair_id' WHERE ID = '$pair_id';
                if(mysqli_query($con,$sql))
                {
                    $next_pair = $pair_id++;
                    $sql = "UPDATE tm_paarung SET successor = 'last_id' WHERE ID = '$next_pair';
                    if(mysqli_query($con,$sql))
                    {
                        $sql = "INSERT INTO tm_match(result_team1, result_team2) VALUES (NULL, NULL);
                        if(mysqli_query($con,$sql))
                        {
                            $new_match_id = getNewMatchId($con)
                            $sql = "INSERT INTO tm_matches (match_id) VALUES ('$new_match_id');
                            if(mysqli_query($con,$sql))
                            {
                                $new_matches_id = getNewMatchesId($con);
                            }
                        }
                    }
                }
            }
            
            if(!(result_pair = pair_id))
            {

            }
        }
    }
    if (current_id = value_id)
    SELECT ID FROM tm_matches ORDER BY ID DESC LIMIT 1;
    UPDATE tm_paarung SET matches_id = matches_id WHERE ID = 'last_id';
    */
?>