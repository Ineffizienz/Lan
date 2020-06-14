<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(dirname(__FILE__,3) . "/include/admin_func.php");
    include(CL . "message_class.php");
    include(INC . "connect.php");

    $tm_id = $_REQUEST["tm_id"];
    $message = new message();

    $existing_tm = getTmById($con,$tm_id);

    if(empty($existing_tm))
    {
        $message->getMessageCode("ERR_ADMIN_TM_DOES_NOT_EXISTS");
        echo buildJSONOutput($message->displayMessage());
    } else {
        $tm_status = getTournamentStatus($con,$tm_id);
        if($tm_status == "1")
        {
            $tm_period_id = getPeriodIdFromTournament($con,$tm_id);
            $sql = "DELETE FROM tm_period WHERE ID = '$tm_period_id";
            if(mysqli_query($con,$sql))
            {
                $sql = "DELETE FROM tm_gamerslist WHERE tm_id = '$tm_id'";
                if(mysqli_query($con,$sql))
                {
                    $sql = "DELETE FROM tm_paarung WHERE tournament = '$tm_id'";
                    if(mysqli_query($con,$sql))
                    {
                        $sql = "DELETE FROM tm WHERE ID = '$tm_id'";
                        if(mysqli_query($con,$sql))
                        {
                            $message->getMessageCode("SUC_ADMIN_DELETE_TM");
                            echo buildJSONOutput(array($message->displayMessage(),"#tm_maintain","#tm_list"));
                        } else {
                            $message->getMessageCode("ERR_ADMIN_INTERN_#4");
                            echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                        }
                    } else {
                        $message->getMessageCode("ERR_ADMIN_DB");
                        echo buildJSONOutput(array($message->displayMessage() . mysqli_error($con)));
                    }    
                } else {
                    $message->getMessageCode("ERR_ADMIN_DB");
                    echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                }
            } else {
                $message->getMessageCode("ERR_ADMIN_DB");
                echo buildJSONoutput($message->displayMessage() . mysqli_error($con));
            }
        } else {
            $sql = "DELETE FROM tm WHERE ID = '$tm_id'";
            if(mysqli_query($con,$sql))
            {
                $message->getMessageCode("SUC_ADMIN_DELETE_TM");
                echo buildJSONOutput(array($message->displayMessage(),"#tm_maintain","#tm_list"));
            } else {
                $message->getMessageCode("ERR_ADMIN_DB");
                echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
            }
        }
    }
?>