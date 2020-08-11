<?php
include(dirname(__FILE__,4) . "/include/init/constant.php");
include(dirname(__FILE__,3) . "/include/admin_func.php");
include(INC . "connect.php");
include(CL . "message_class.php");

$message = new message();

if(isset($_REQUEST["tm_id"]))
{
    $tm_id = $_REQUEST["tm_id"];
    if(checkForTournament($con,$tm_id))
    {
        $tournament = getSingleTournamentData($con,$tm_id);
        $game_id = $tournament["game_id"];
        $mode = $tournament["mode"];
        $mode_details = $tournament["mode_details"];
        $player_count = $tournament["player_count"];
        $period_id = $tournament["tm_period_id"];

        $sql = "INSERT INTO archiv_tm VALUES ('$tm_id','$game_id','$mode','$mode_details','$player_count','$period_id')";
        if(mysqli_query($con,$sql))
        {
            
            if(archivTmPaarung($con,$tm_id) && archivTmGamerslist($con,$tm_id) && archivTmPeriod($con,$period_id))
            {
                $sql = "DELETE FROM tm WHERE ID = '$tm_id'";
                if(mysqli_query($con,$sql))
                {
                    $message->getMessageCode("SUC_ADMIN_ARCHIV_TM");
                    echo buildJSONOutput(array($message->displayMessage(),"#tm_maintain","#tm_list"));
                } else {
                    $message->getMessageCode("ERR_ADMIN_DEL_TM_ENTRY");
                    echo buildJSONOutput($message->displayMessage());
                }
            } else {
                $message->getMessageCode("ERR_ADMIN_ARCHIV_DATA");
                echo buildJSONOutput($message->displayMessage());
            }           

        } else {
            $message->getMessageCode("ERR_ADMIN_ARCHIV_TOURNAMENT");
            echo buildJSONOutput($message->displayMessage() . $tm_id);
        }
    } else {
        $message->getMessageCode("ERR_ADMIN_TM_DOES_NOT_EXISTS");
        echo buildJSONOutput($message->displayMessage());
    }
} else {
    $message->getMessageCode("ERR_ADMIN_EMPTY_PARAM");
    echo buildJSONOutput($message->displayMessage());
}
?>