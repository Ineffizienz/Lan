<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
    include(dirname(__FILE__,3) . "/include/admin_func.php");
    include(INC . "connect.php");
    include(CL . "message_class.php");
    include(CL . "achievement_class.php");

    $message = new message();
    $ac = new Achievement($con);

    $acid = $_REQUEST["ac_id"];
    
    if(isset($_FILES["file"]["size"]) && !empty($_FILES["file"]["size"]))
    {
        $result_validate = validateImageFile($_FILES["file"]["size"],pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));
        if($result_validate == "1")
        {
            move_uploaded_file($_FILES["file"]["tmp_name"], AC . $_FILES["file"]["name"]);
            $path = $_FILES["file"]["name"];

            $message->getMessageCode($ac->setNewAcImage($id,$path));
            echo buildJSONOutput(array($message->displayMessage(),".ac_img_label",".ac_image_disp"));
        
        } else {
            $message->getMessageCode($result_validate);
            echo buildJSONOutput($message->displayMessage());
        }
    }
?>