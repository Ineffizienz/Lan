<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(dirname(__FILE__,3) . "/include/admin_func.php");
    include(CL . "message_class.php");
    include(INIT . "get_parameters.php");
    include(INC . "connect.php");

    $tm_id = $_REQUEST["tm_id"];
    $message = new message();

    $existing_tm = getTmById($con,$tm_id);

    if(empty($existing_tm))
    {
        $message->getMessageCode("ERR_ADMIN_TM_DOES_NOT_EXISTS");
        echo buildJSONOutput($message->displayMessage());
    } else {
        $sql = "DELETE FROM tm WHERE ID = '$tm_id'";
        if(mysqli_query($con,$sql))
        {
            $message->getMessageCode("SUC_ADMIN_DELETE_TM");
            echo buildJSONOutput($message->displayMessage());
        } else {
            $message->getMessageCode("ERR_ADMIN_INTERN_#4");
            echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
        }
    }
?>