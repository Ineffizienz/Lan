<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(dirname(__FILE__,3) . "/include/admin_func.php");
    include(CL . "message_class.php");
    include(INC . "connect.php");

    $message = new message();
    $tm_id = $_REQUEST["tm_id"];

    $existing_tm = getTmById($con,$tm_id);

    if(empty($existing_tm))
    {
        $message->getMessageCode("ERR_ADMIN_TM_DOES_NOT_EXISTS");
        echo buildJSONOutput($message->displayMessage());
    } else {
        $tm_locked = getTournamentStatus($con,$tm_id);

        if($tm_locked == "1")
        {
            $message->getMessageCode("ERR_ADMIN_TM_CANNOT_BE_STARTED");
            echo buildJSONOutput($message->displayMessage());
        } else {
            $sql = "UPDATE tm SET tm_locked = '1' WHERE ID = '$tm_id'";
            if(mysqli_query($con,$sql))
            {
                $pair_count = getPairCount($con,$tm_id);

                if($pair_count == 0)
                {
                    $message->getMessageCode("ERR_ADMIN_MISSING_PAIR");
                    echo buildJSONOutput($message->displayMessage());
                } else {
                    $stage_count = 1;
                    while(($pair_count/2) > 1)
                    {
                        if(($pair_count % 2) == 1) // Wenn es weniger Spieler als Paarungen gibt, dann wird trotzdem eine Paarung für einen Wildcard-Spieler erstellt.
                        {
                            $rounded_count = round(($pair_count/2), 0, PHP_ROUND_HALF_UP);
                        } else {
                            $rounded_count = $pair_count/2;
                        }

                        for ($i=1;$i<=$rounded_count;$i++)
                        {
                            $next_stage = $stage_count + 1;
                            $sql = "INSERT INTO tm_paarung (team_1, team_2, tournament, stage) VALUES ('0', '0', '$tm_id', '$next_stage')";
                            if(mysqli_query($con,$sql))
                            {
                                $last_pair_id = getLastPairId($con,$tm_id);
                                $first_pair_id = getFirstPairId($con,$tm_id,$stage_count);
                                
                                $match_count = 1;
                                foreach ($first_pair_id as $pair_id)
                                {
                                    if($match_count > 1)
                                    {
                                        $sql = "UPDATE tm_paarung SET successor = '$last_pair_id' WHERE ID = '$pair_id'";
                                        mysqli_query($con,$sql);
                                    } else {
                                        $sql = "INSERT INTO tm_match (result_team1, result_team2) VALUES (NULL, NULL)";
                                        if (mysqli_query($con,$sql))
                                        {
                                            $new_match_id = getNewMatchId($con);
                                            $sql = "INSERT INTO tm_matches (match_id) VALUES ('$new_match_id')";
                                            if(mysqli_query($con,$sql))
                                            {
                                                $new_matches_id = getNewMatchesId($con);
                                                $sql = "UPDATE tm_paarung SET matches_id = '$new_matches_id' WHERE (ID = '$last_pair_id') AND (successor IS NULL)";
                                                if(mysqli_query($con,$sql))
                                                {
                                                    $sql = "UPDATE tm_paarung SET successor = '$last_pair_id' WHERE ID = '$pair_id'";
                                                    mysqli_query($con,$sql);
                                                } else {
                                                    $message->getMessageCode("ERR_ADMIN_DB");
                                                    echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                                                }
                                            } else {
                                                $message->getMessageCode("ERR_ADMIN_DB");
                                                echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                                            }
                                        } else {
                                            $message->getMessageCode("ERR_ADMIN_DB");
                                            echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                                        }
                                    }
                                    $match_count++;
                                }
                            } else {
                                $message->getMessageCode("ERR_ADMIN_DB");
                                echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                            }
                        }
                        handlingWildcard($con,$tm_id,$pair_count,$stage_count,$next_stage);
                        $stage_count++;
                        $pair_count = getPairCount($con,$tm_id); // setzt den neuen Wert für die Anzahl der Paarungen
                    }

                    if(getPairCount($con,$tm_id) == 2) // Sollten nach dem letzten Schleifendurchlauf zwei Paarungen ohne Nachfolger sein, wird eine weitere Paarung hinzugefügt. Hierbei handelt es sich dann um das Finale.
                    {
                        $stage_count++;
                        $sql = "INSERT INTO tm_paarung (team_1, team_2, tournament, stage) VALUES ('0', '0', '$tm_id', '$stage_count')";
                        if(mysqli_query($con,$sql))
                        {
                            $last_pair_id = getLastPairId($con,$tm_id);
                            $sql = "INSERT INTO tm_match (result_team1, result_team2) VALUES (NULL, NULL)";
                            if(mysqli_query($con,$sql))
                            {
                                $new_match_id = getNewMatchId($con);
                                $sql = "INSERT INTO tm_matches (match_id) VALUES ('$new_match_id')";
                                if(mysqli_query($con,$sql))
                                {
                                    $new_matches_id = getNewMatchesId($con);
                                    $sql = "UPDATE tm_paarung SET matches_id = '$new_matches_id' WHERE ID = '$last_pair_id'";
                                    if(mysqli_query($con,$sql))
                                    {
                                        $sql = "UPDATE tm_paarung SET successor = '$last_pair_id' WHERE (tournament = '$tm_id') AND (successor IS NULL) AND (ID != '$last_pair_id')";
                                        if(!(mysqli_query($con,$sql)))
                                        {
                                            $message->getMessageCode("ERR_ADMIN_DB");
                                            echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                                        }
                                    } else {
                                        $message->getMessageCode("ERR_ADMIN_DB");
                                        echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                                    }
                                } else {
                                    $message->getMessageCode("ERR_ADMIN_DB");
                                    echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                                }
                            } else {
                                $message->getMessageCode("ERR_ADMIN_DB");
                                echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                            }
                            
                        } else {
                            $message->getMessageCode("ERR_ADMIN_DB");
                            echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                        }
                    }
                }
            } else {
                $message->getMessageCode("ERR_ADMIN_DB");
                echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
            }
        }
    }
?>