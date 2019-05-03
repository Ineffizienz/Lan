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
        $starttime = getTmStartById($con,$tm_id);
        
        if(strtotime($starttime) < time())
        {
            $message->getMessageCode("ERR_ADMIN_TM_CANNOT_BE_STARTED");
            echo buildJSONOutput($message->displayMessage());
        } else {
            $new_starttime = date("Y-m-d H:i:s", time());
            $sql = "UPDATE tm SET starttime = '$new_starttime' WHERE ID = '$tm_id'";
            if(mysqli_query($con,$sql))
            {
                $message->getMessageCode("SUC_ADMIN_START_TM");
                echo buildJSONOutput($message->displayMessage());
            } else {
                $message->getMessageCode("ERR_ADMIN_INTERN_#4");
                echo buildJSONOutput($message->displayMessage());
            }
        }
    }
?>