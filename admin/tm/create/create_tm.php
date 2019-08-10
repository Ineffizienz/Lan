<?php
/* Notizen
        - Vote nach Start des Turniers bereinigen!
*/
include(dirname(__FILE__,4) . "/include/init/constant.php");
include(dirname(__FILE__,3) . "/include/admin_func.php");
include(INIT . "get_parameters.php");
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

        $tm_from = date("Y-m-d H:i:s", strtotime($tm_from));
        $tm_to = date("Y-m-d H:i:s", strtotime($tm_to));

        $sql = "INSERT INTO tm_period (time_from,time_to) VALUES ('$tm_from','$tm_to')";
        if(mysqli_query($con,$sql))
        {
            $tm_period_id = getTournamentPeriodId($con);
            $end_register = date("Y-m-d H:i:s", strtotime("+30 minutes")); //begrenzt den Anmeldungszeitraum für Turniere auf 30 Minuten
            
            $sql = "INSERT INTO tm (game_id,mode,mode_details,tm_period_id,tm_end_register,lan_id) VALUES ('$game_id','$mode','$mode_details','$tm_period_id','$end_register','0')";
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

/*if (isset($_REQUEST["game"]))
{
    if (empty($_REQUEST["game"]))
    {
        $message->getMessageCode("ERR_ADMIN_INTERN_#2");
        echo buildJSONOutput($message->displayMessage());
    } else if (empty($_REQUEST["mode"])) {
        $message->getMessageCode("ERR_ADMIN_INTERN_#3");
        echo buildJSONOutput($message->displayMessage());
    } else {

        $tms_game = getTournamentGames($con);

        if(in_array($_REQUEST["game"],$tms_game))
        {
            foreach($tms_game as $existing_tm)
            {
                $tm_modes = getTournamentModes($con,$existing_tm);
                if ($tm_modes == $_REQUEST["mode"])
                {
                    $message->getMessageCode("ERR_ADMIN_TM_EXISTS");
                    echo buildJSONOutput($message->displayMessage());
                    break;
                } else {
                    $message->getMessageCode("WARN_ADMIN_GAME_HAS_TM");
                    echo buildJSONOutput($message->displayMessage());
                    break;
                }
            }
        } else {
            
            // Requests parameters game and mode from URL
            $tm_game = $_REQUEST["game"];
            $tm_mode = $_REQUEST["mode"];
            $tm_mode_details = $_REQUEST["mode_details"];
            $tm_min_player = $_REQUEST["min_player"];
            $datetime = strtotime($_REQUEST["datetime"]);
            $tm_starttime = date("Y-m-d H:i:s", $datetime);

            // Checks if image_data is 0 or contains an image
            if(!isset($_FILES["file"]))
            {
                $sql = "INSERT INTO tm (game, mode, mode_details, banner, min_player, starttime) VALUES ('$tm_game','$tm_mode','$tm_mode_details',NULL,'$tm_min_player','$tm_starttime')";
                if(mysqli_query($con,$sql))
                {
                    $message->getMessageCode("SUC_ADMIN_CREATE_TM");
                    echo buildJSONOutput(array($message->displayMessage(),array("#tm_maintain","#create_tm_form"),array("#tm_list","#create_form")));
                } else {
                    $message->getMessageCode("ERR_ADMIN_CREATE_TM");
                    echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                }
            } else {
                $result_validate = validateImageFile($_FILES["file"]["size"],pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));
                if ($result_validate == "1")
                {
                    $tm_banner = $_FILES["file"]["name"];
                    if (!file_exists($_FILES["file"]["name"]))
                    {
                        move_uploaded_file($_FILES["file"]["tmp_name"], BANNER . $_FILES["file"]["name"]);
                    }
                    
                    $sql = "INSERT INTO tm (game, mode, mode_details, banner, min_player, starttime) VALUES ('$tm_game','$tm_mode','$tm_mode_details','$tm_banner','$tm_min_player','$tm_starttime')";
                    if(mysqli_query($con,$sql))
                    {
                        $message->getMessageCode("SUC_ADMIN_CREATE_TM");
                        echo buildJSONOutput(array($message->displayMessage(),array("#tm_maintain","#create_tm_form"),array("#tm_list","#create_form")));
                    } else {
                        $message->getMessageCode("ERR_ADMIN_CREATE_TM");
                        echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                    }
                } else {
                    $message->getMessageCode($result_validate);
                    echo $message->displayMessage();
                }
            }
        }        

    } 

} else {
    $message->getMessageCode("ERR_ADMIN_NO_GAME_SELECTED");
    echo buildJSONOutput($message->displayMessage());
}*/

?>