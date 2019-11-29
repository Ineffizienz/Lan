<?php
    session_start();
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(INC. "connect.php");
    include(INC. "function.php");
    include(CL . "message_class.php");

    $message = new message();
    $player_id = $_SESSION["player_id"];
    $tm_id = $_REQUEST["tm_id"];
    $pair_id = $_REQUEST["pair_id"];

    $gamerslist_id = getGamerslistIdByPlayerId($con,$player_id,$tm_id);
    if(getGamerslistIdFromPair($con,$gamerslist_id,$pair_id))
    {
        $matches_id = getSingleMatchesIdFromPaarung($con,$pair_id);
        $match_id = getMatchIdFromMatches($con,$matches_id);
        $result_1 = $_REQUEST["result_1"];
        $result_2 = $_REQUEST["result_2"];
        
        if(empty($result_1) || empty($result_2))
        {
            $message->getMessageCode("ERR_NO_RESULT");
            echo json_encode(array("message"=>$message->displayMessage()));
        } else {
            if($result_1 == $result_2)
            {
                $message->getMessageCode("ERR_NO_DRAW");
                echo json_encode(array("message"=>$message->displayMessage()));
            } else {
                $sql = "UPDATE tm_match SET result_team1 = '$result_1', result_team2 = '$result_2' WHERE ID = '$match_id'";
                if(mysqli_query($con,$sql))
                {
                    $team_gamerslist = getGamerslistIdByPair($con,$pair_id);
                    $team_1 = $team_gamerslist["team_1"];
                    $team_2 = $team_gamerslist["team_2"];
                    $successor_id = getSuccessorFromPair($con,$pair_id);
                    if($result_1 > $result_2)
                    {
                        if(empty(getSuccessorTeams($con,$pair_id)))
                        {
                            $sql = "UPDATE tm_paarung SET team_2 = '$team_1' WHERE ID = '$successor_id'";
                            if(mysqli_query($con,$sql))
                            {
                                $message->getMessageCode("SUC_ENTER_RESULT");
                                echo json_encode(array("message"=>$message->displayMessage()));
                            } else {
                                $message->getMessageCode("ERR_DB");
                                echo json_encode(array("message"=>$message->displayMessage()));
                            }
                        } else {
                            $sql = "UPDATE tm_paarung SET team_1 = '$team_1' WHERE ID ='$successor_id'";
                            if(mysqli_query($con,$sql))
                            {  
                                $message->getMessageCode("SUC_ENTER_RESULT");
                                echo json_encode(array("message"=>$message->displayMessage()));
                            } else {
                                $message->getMessageCode("ERR_DB");
                                echo json_encode(array("message"=>$message->displayMessage()));
                            }
                        }
                    } else {
                        if(empty(getSuccessorTeams($con,$pair_id)))
                        {
                            $sql = "UPDATE tm_paarung SET team_2 = '$team_2' WHERE ID = '$successor_id'";
                            if(mysqli_query($con,$sql))
                            {
                                $message->getMessageCode("SUC_ENTER_RESULT");
                                echo json_encode(array("message"=>$message->displayMessage()));
                            } else {
                                $message->getMessageCode("ERR_DB");
                                echo json_encode(array("message"=>$message->displayMessage()));
                            }
                        } else {
                            $sql = "UPDATE tm_paarung SET team_1 = '$team_2' WHERE ID ='$successor_id'";
                            if(mysqli_query($con,$sql))
                            {
                                $message->getMessageCode("SUC_ENTER_RESULT");
                                echo json_encode(array("message"=>$message->displayMessage()));
                            } else {
                                $message->getMessageCode("ERR_DB");
                                echo json_encode(array("message"=>$message->displayMessage()));
                            }
                        }
                    }
                } else {
                    $message->getMessageCode("ERR_DB");
                    echo json_encode(array("message"=>$message->displayMessage() . mysqli_error($con)));
                }
            }
        }       
    } else {
        $message->getMessageCode("ERR_INCORRECT_MATCH");
        echo json_encode(array("message"=>$message->displayMessage() . mysqli_error($con)));
    }
?>