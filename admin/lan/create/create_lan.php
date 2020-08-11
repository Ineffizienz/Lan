<?php
include(dirname(__FILE__,4) . "/include/init/constant.php");
include(dirname(__FILE__,3) . "/include/admin_func.php");
include(INC . "connect.php");
include(CL . "message_class.php");

$message = new message();

if(isset($_REQUEST["lan_title"]))
{
    $lan_title = $_REQUEST["lan_title"];
    if(isset($_REQUEST["date_from"]))
    {
        $date_from = date("Y-m-d", strtotime($_REQUEST["date_from"]));
        
        if(isset($_REQUEST["date_to"]))
        {
            $date_to = date("Y-m-d", strtotime($_REQUEST["date_to"]));

            $sql = "INSERT INTO lan (title, date_from, date_to) VALUES ('$lan_title','$date_from','$date_to')";
            if(mysqli_query($con,$sql))
            {
                $message->getMessageCode("SUC_ADMIN_CREATE_LAN");
                echo buildJSONOutput(array($message->displayMessage(),"#lan_display","#lan_list"));
            } else {
                $message->getMessageCode("ERR_ADMIN_DB");
                echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
            }
        } else {
            $message->getMessageCode("ERR_ADMIN_MISSING_DATE_TO");
            echo buildJSONOutput($message->displayMessage());
        }
    } else {
        $message->getMessageCode("ERR_ADMIN_MISSING_LAN_DATE_FROM");
        echo buildJSONOutput($message->displayMessage());
    }
} else {
    $message->getMessageCode("ERR_ADMIN_MISSING_LAN_TITLE");
    echo buildJSONOutput($message->displayMessage());
}

?>