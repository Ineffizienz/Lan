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
    if(isset($_REQUEST["vote_id"]))
    {
        
        $vote_id = $_REQUEST["vote_id"];
        $game_id = $_REQUEST["game_id"];
        //$lan_id = $_REQUEST["lan_id"];
        $tm_from = $_REQUEST["tm_time_from"];
        $tm_to = $_REQUEST["tm_time_to"];
        $mode = $_REQUEST["mode"];
        $mode_details = $_REQUEST["mode_details"];
        $max_player = $_REQUEST["max_player"];

        $tm_from = date("Y-m-d H:i:s", strtotime($tm_from));
        $tm_to = date("Y-m-d H:i:s", strtotime($tm_to));

        $sql = "INSERT INTO tm_period (time_from,time_to) VALUES ('$tm_from','$tm_to')";
        if(mysqli_query($con,$sql))
        {
            $tm_period_id = getTournamentPeriodId($con);
            $end_register = date("Y-m-d H:i:s", strtotime("+30 minutes")); //begrenzt den Anmeldungszeitraum für Turniere auf 30 Minuten
            $vote_count = getVotedPlayers($con,$vote_id);
            
            $sql = "INSERT INTO tm (game_id,mode,mode_details,player_count,tm_period_id,tm_end_register,tm_locked,lan_id) VALUES ('$game_id','$mode','$mode_details','$vote_count','$tm_period_id','$end_register','0','0')";
            if(mysqli_query($con,$sql))
            {
                $tm_id = getLastTmId($con);
                $player_ids = getPlayerIdsFromVote($con,$vote_id);
                
                foreach ($player_ids as $player_id)
                {
                    $player_ids_gamerslist = getPlayerIdFromGamerslist($con,$tm_id);

                    if(empty($player_ids_gamerslist))
                    {
                        $sql = "INSERT INTO tm_gamerslist (tm_id, player_id) VALUES ('$tm_id','$player_id')";
                        if(!mysqli_query($con,$sql))
                        {
                            $message->getMessageCode("ERR_ADMIN_DB");
                            echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                        }
                    } else {
                        if(!in_array($player_id,$player_ids_gamerslist))
                        {
                            $sql = "INSERT INTO tm_gamerslist (tm_id, player_id) VALUES ('$tm_id','$player_id')";
                            if(!mysqli_query($con,$sql))
                            {
                                $message->getMessageCode("ERR_ADMIN_DB");
                                echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                            }
                        }
                    }
                }

                $sql = "DELETE FROM tm_vote_player WHERE tm_vote_id = '$vote_id'";
                if(mysqli_query($con,$sql))
                {
                    $sql = "DELETE FROM tm_vote WHERE ID = '$vote_id'";
                    if(mysqli_query($con,$sql))
                    {
                        $sql = "CREATE EVENT IF NOT EXISTS start_tm" . $tm_id . " ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 30 MINUTE ENABLE DO UPDATE tm SET tm_locked = 1 WHERE ID = '$tm_id'";
                        if(mysqli_query($con,$sql))
                        {
                            $message->getMessageCode("SUC_ADMIN_CREATE_TM_FROM_VOTE");
                            echo buildJSONOutput($message->displayMessage());
                        } else {
                            $message->getMessageCode("ERR_ADMIN_DB");
                            echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                        }
                    } else {
                        $message->getMessageCode("ERR_ADMIN_DB");
                        echo buildJSONoutput($message->displayMessage() . mysqli_error($con));
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
            echo buildJSONOutput($message->displayMessage());
        }

    } else {
        $message->getMessageCode("ERR_ADMIN_EMPTY_PARAM");
        echo buildJSONOutput($message->displayMessage());
    }
} else {
    $message->getMessageCode("ERR_ADMIN_EMPTY_PARAM");
    echo buildJSONOutput($message->displayMessage());
}

?>