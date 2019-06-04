<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(dirname(__FILE__,3) . "/include/admin_func.php");
    include(INC . "connect.php");
    include(CL . "message_class.php");


    $acid = $_REQUEST["ac_id"];
    $message = new message();
    
    if(isset($_FILES["file"]["size"]) && !empty($_FILES["file"]["size"]))
    {
        $result_validate = validateImageFile($_FILES["file"]["size"],pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));
        if($result_validate == "1")
        {
            move_uploaded_file($_FILES["file"]["tmp_name"], AC . $_FILES["file"]["name"]);
            $path = $_FILES["file"]["name"];

            $sql = "UPDATE ac SET image_url = '$path' WHERE ID = '$acid'";
            if(mysqli_query($con,$sql))
            {
                $message->getMessageCode("SUC_CHANGE_AC_IMAGE");
                echo buildJSONOutput(array($message->displayMessage(),"#ac_image_" . $acid,".ac_image_disp"));
            } else {
                $message->getMessageCode("ERR_ADMIN_DB");
                echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
            }
        } else {
            $message->getMessageCode($result_validate);
            echo buildJSONOutput($message->displayMessage());
        }
    }
?>