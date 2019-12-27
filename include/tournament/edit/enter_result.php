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
        
        if(empty($match_id))
        {
            $message->getMessageCode("ERR_NO_MATCH");
            echo json_encode(array("message"=>$message->displayMessage()));
        } else {
            $lock_time = getMatchLockTime($con,$match_id);
            $current_time = date("Y-m-d H:i:s", strtotime("now"));

            if(!(empty($lock_time)) && (strtotime($current_time) > strtotime($lock_time)))
            {
                $message->getMessageCode("ERR_MATCH_LOCKED");
                echo json_encode(array("message"=>$message->displayMessage()));
            } elseif (empty($lock_time) || ($lock_time > $current_time)) {
                if(($result_1 == "") || ($result_2 == ""))
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
                            $match_lock = date("Y-m-d H:i:s", strtotime("+10 minutes"));
                            $sql = "UPDATE tm_matches SET match_locked = '$match_lock' WHERE match_id = '$match_id'";
                            if(mysqli_query($con,$sql))
                            {
                                $team_gamerslist = getGamerslistIdByPair($con,$pair_id);
                                $team_1 = $team_gamerslist["team_1"];
                                $team_2 = $team_gamerslist["team_2"];
                                $successor_id = getSuccessorFromPair($con,$pair_id);
                                $second_pair = getSecondPairId($con,$pair_id,$successor_id);
                                if($result_1 > $result_2)
                                {
                                    if($pair_id < $second_pair)
                                    {
                                        $sql = "UPDATE tm_paarung SET team_1 = '$team_1' WHERE ID = '$successor_id'";
                                        if(mysqli_query($con,$sql))
                                        {
                                            if(getSuccessorCount($con,$successor_id) == 1)
                                            {
                                                $matches_id = getSingleMatchesIdFromPaarung($con,$successor_id);
                                                $sql = "UPDATE tm_paarung SET matches_id = NULL WHERE ID = '$successor_id'";
                                                if(mysqli_query($con,$sql))
                                                {
                                                    $match_id = getMatchIdFromMatches($con,$matches_id);
                                                    $sql = "DELETE FROM tm_matches WHERE ID = '$matches_id'";
                                                    if(mysqli_query($con,$sql))
                                                    {
                                                        $sql = "DELETE FROM tm_match WHERE ID = '$match_id'";
                                                        if(mysqli_query($con,$sql))
                                                        {
                                                            $message->getMessageCode("SUC_ENTER_RESULT");
                                                            echo json_encode(array("message"=>$message->displayMessage()));
                                                        } else {
                                                            $message->getMessageCode("ERR_DB");
                                                            echo json_encode(array("message"=>$message->displayMessage()));
                                                        }
                                                    } else {
                                                        $message->getMessageCode("ERR_DB");
                                                        echo json_encode(array("message"=>$message->displayMessage()));
                                                    }
                                                } else {
                                                    $message->getMessageCode("ERR_DB");
                                                    echo json_encode(array("message"=>$message->displayMessage()));
                                                }
                                            } else {
                                                $message->getMessageCode("SUC_ENTER_RESULT");
                                                echo json_encode(array("message"=>$message->displayMessage()));
                                            }
                                        } else {
                                            $message->getMessageCode("ERR_DB");
                                            echo json_encode(array("message"=>$message->displayMessage()));
                                        }
                                    } else {
                                        $sql = "UPDATE tm_paarung SET team_2 = '$team_1' WHERE ID = '$successor_id'";
                                        if(mysqli_query($con,$sql))
                                        {
                                            if(getSuccessorCount($con,$successor_id) == 1)
                                            {
                                                $matches_id = getSingleMatchesIdFromPaarung($con,$successor_id);
                                                $sql = "UPDATE tm_paarung SET matches_id = NULL WHERE ID = '$successor_id'";
                                                if(mysqli_query($con,$sql))
                                                {
                                                    $match_id = getMatchIdFromMatches($con,$matches_id);
                                                    $sql = "DELETE FROM tm_matches WHERE ID = '$matches_id'";
                                                    if(mysqli_query($con,$sql))
                                                    {
                                                        $sql = "DELETE FROM tm_match WHERE ID = '$match_id'";
                                                        if(mysqli_query($con,$sql))
                                                        {
                                                            $message->getMessageCode("SUC_ENTER_RESULT");
                                                            echo json_encode(array("message"=>$message->displayMessage()));
                                                        } else {
                                                            $message->getMessageCode("ERR_DB");
                                                            echo json_encode(array("message"=>$message->displayMessage()));
                                                        }
                                                    } else {
                                                        $message->getMessageCode("ERR_DB");
                                                        echo json_encode(array("message"=>$message->displayMessage()));
                                                    }
                                                } else {
                                                    $message->getMessageCode("ERR_DB");
                                                    echo json_encode(array("message"=>$message->displayMessage()));
                                                }
                                            } else {
                                                $message->getMessageCode("SUC_ENTER_RESULT");
                                                echo json_encode(array("message"=>$message->displayMessage()));
                                            } 
                                        }
                                    }       	                    
                                } else {
                                    if($pair_id < $second_pair)
                                    {
                                        $sql = "UPDATE tm_paarung SET team_1 = '$team_2' WHERE ID = '$successor_id'";
                                        if(mysqli_query($con,$sql))
                                        {
                                            if(getSuccessorCount($con,$successor_id) == 1)
                                            {
                                                $matches_id = getSingleMatchesIdFromPaarung($con,$successor_id);
                                                $sql = "UPDATE tm_paarung SET matches_id = NULL WHERE ID = '$successor_id'";
                                                if(mysqli_query($con,$sql))
                                                {
                                                    $match_id = getMatchIdFromMatches($con,$matches_id);
                                                    $sql = "DELETE FROM tm_matches WHERE ID = '$matches_id'";
                                                    if(mysqli_query($con,$sql))
                                                    {
                                                        $sql = "DELETE FROM tm_match WHERE ID = '$match_id'";
                                                        if(mysqli_query($con,$sql))
                                                        {
                                                            $message->getMessageCode("SUC_ENTER_RESULT");
                                                            echo json_encode(array("message"=>$message->displayMessage()));
                                                        } else {
                                                            $message->getMessageCode("ERR_DB");
                                                            echo json_encode(array("message"=>$message->displayMessage()));
                                                        }
                                                    } else {
                                                        $message->getMessageCode("ERR_DB");
                                                        echo json_encode(array("message"=>$message->displayMessage()));
                                                    }
                                                } else {
                                                    $message->getMessageCode("ERR_DB");
                                                    echo json_encode(array("message"=>$message->displayMessage()));
                                                }
                                            } else {
                                                $message->getMessageCode("SUC_ENTER_RESULT");
                                                echo json_encode(array("message"=>$message->displayMessage()));
                                            }
                                        } else {
                                            $message->getMessageCode("ERR_DB");
                                            echo json_encode(array("message"=>$message->displayMessage()));
                                        }
                                    } else {
                                        $sql = "UPDATE tm_paarung SET team_2 = '$team_2' WHERE ID = '$successor_id'";
                                        if(mysqli_query($con,$sql))
                                        {
                                            if(getSuccessorCount($con,$successor_id) == 1)
                                            {
                                                $matches_id = getSingleMatchesIdFromPaarung($con,$successor_id);
                                                $sql = "UPDATE tm_paarung SET matches_id = NULL WHERE ID = '$successor_id'";
                                                if(mysqli_query($con,$sql))
                                                {
                                                    $match_id = getMatchIdFromMatches($con,$matches_id);
                                                    $sql = "DELETE FROM tm_matches WHERE ID = '$matches_id'";
                                                    if(mysqli_query($con,$sql))
                                                    {
                                                        $sql = "DELETE FROM tm_match WHERE ID = '$match_id'";
                                                        if(mysqli_query($con,$sql))
                                                        {
                                                            $message->getMessageCode("SUC_ENTER_RESULT");
                                                            echo json_encode(array("message"=>$message->displayMessage()));
                                                        } else {
                                                            $message->getMessageCode("ERR_DB");
                                                            echo json_encode(array("message"=>$message->displayMessage()));
                                                        }
                                                    } else {
                                                        $message->getMessageCode("ERR_DB");
                                                        echo json_encode(array("message"=>$message->displayMessage()));
                                                    }
                                                } else {
                                                    $message->getMessageCode("ERR_DB");
                                                    echo json_encode(array("message"=>$message->displayMessage()));
                                                }
                                            } else {
                                                $message->getMessageCode("SUC_ENTER_RESULT");
                                                echo json_encode(array("message"=>$message->displayMessage()));
                                            }
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
                        } else {
                            $message->getMessageCode("ERR_DB");
                            echo json_encode(array("message"=>$message->displayMessage() . mysqli_error($con)));
                        }
                    }
                }
            }
        }
               
    } else {
        $message->getMessageCode("ERR_INCORRECT_MATCH");
        echo json_encode(array("message"=>$message->displayMessage() . mysqli_error($con)));
    }
?>