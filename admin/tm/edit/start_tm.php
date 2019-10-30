<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(dirname(__FILE__,3) . "/include/admin_func.php");
    include(CL . "message_class.php");
    include(INC . "connect.php");
    include(INIT. "get_parameters.php");

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
            $sql = "SET @active_trigger = 0";
            if (mysqli_query($con,$sql))
            {
                $sql = "UPDATE tm SET tm_locked = '1' WHERE ID = '$tm_id'";
                if(mysqli_query($con,$sql))
                {
                    $message->getMessageCode("SUC_ADMIN_START_TM");
                    echo buildJSONOutput($message->displayMessage());
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
?>