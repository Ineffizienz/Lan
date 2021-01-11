<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(dirname(__FILE__,3) . "/include/admin_func.php");
    include(INC . "connect.php");
    include(CL . "message_class.php");

    $message = new message();

    if(isset($_REQUEST["n_trigger"]))
    {
        $new_trigger = $_REQUEST["n_trigger"];
        $sql = "INSERT INTO ac_trigger (trigger_title) VALUES ('$new_trigger')";
        if(mysqli_query($con,$sql))
        {
            $message->getMessageCode("SUC_ADMIN_CREATE_TRIGGER");
            echo buildJSONOutput($message->displayMessage());
        } else {
            $message->getMessageCode("ERR_ADMIN_DB");
            echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
        }
    }
?>