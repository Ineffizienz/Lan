<?php
include(dirname(__FILE__,3) . "/include/init/constant.php");
include(dirname(__FILE__,2) . "/include/admin_func.php");
include(INC . "connect.php");
include(CL . "message_class.php");

$message = new message();

if(isset($_REQUEST["region_id"]))
{
    if(isset($_REQUEST["region_name"]))
    {
        $existing_regions = getWowRegions($con);
        if(empty($existing_regions))
        {
            $region_id = $_REQUEST["region_id"];
            $region_name = $_REQUEST["region_name"];

            $sql = "INSERT INTO wow_region (region_id, region_name) VALUES ('$region_id','$region_name')";
            if(mysqli_query($con,$sql))
            {
                $message->getMessageCode("SUC_ADMIN_REGION_ADDED");
                echo buildJSONOutput(array($message->displayMessage(),$_REQUEST["p_element"],$_REQUEST["c_element"],0));
            } else {
                $message->getMessageCode("ERR_ADMIN_DB");
                echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
            }
        } else {
            if(in_array($_REQUEST["region_id"],$existing_regions["region_id"]))
            {
                $message->getMessageCode("ERR_ADMIN_REGION_ID_EXISTS");
                echo buildJSONOutput($message->displayMessage());
            } else {
                if(in_array($_REQUEST["region_name"],$existing_regions["region_name"]))
                {
                    $message->getMessageCode("ERR_ADMIN_REGION_NAME_EXISTS");
                    echo buildJSONOutput($message->displayMessage());
                } else {
                    $region_id = $_REQUEST["region_id"];
                    $region_name = $_REQUEST["region_name"];

                    $sql = "INSERT INTO wow_region (region_id, region_name) VALUES ('$region_id','$region_name')";
                    if(mysqli_query($con,$sql))
                    {
                        $message->getMessageCode("SUC_ADMIN_REGION_ADDED");
                        echo buildJSONOutput(array($message->displayMessage(),$_REQUEST["p_element"],$_REQUEST["c_element"],0));
                    } else {
                        $message->getMessageCode("ERR_ADMIN_DB");
                        echo buildJSONOutput($message->displayMessage());
                    }
                }
            }
        }
        
    } else {
        $message->getMessageCode("ERR_ADMIN_NO_REGION_NAME");
        echo buildJSONOutput($message->displayMessage());
    }
} else {
    $message->getMessageCode("ERR_ADMIN_NO_REGION_ID");
    echo buildJSONOutput($message->displayMessage());
}
?>