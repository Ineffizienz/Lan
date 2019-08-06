<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(dirname(__FILE__,3) . "/include/admin_func.php");
    include(CL . "message_class.php");
    include(INC . "connect.php");
    include(INIT . "get_parameters.php");

    $message = new message();

    if(isset($_REQUEST["vote_id"]))
    {
        $vote_id = $_REQUEST["vote_id"];
        $sql = "DELETE FROM tm_vote_player WHERE tm_vote_id = '$vote_id'";
        if(mysqli_query($con,$sql)
        {
            $sql = "DELETE FROM tm_vote WHERE ID = '$vote_id'";
            if(mysqli_query($con,$sql))
            {
                $message->getMessageCode("SUC_ADMIN_DELETE_VOTE");
                echo buildJSONOutput($message->displayMessage());
            } else {
                $message->getMessageCode("ERR_ADMIN_DB");
                echo buildJSONOutput($message->displayMessage());
            }
        } else {
            $message->getMessageCode("ERR_ADMIN_DB");
            echo buildJSONOutput($message->displayMessage());
        }
    } else {
        $message->getMessageCode("ERR_ADMIN_EMPTY_PARAM");
        echo buildJSONOutput($message->displayMessage());
    }
?>