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
        move_uploaded_file($_FILES["file"]["tmp_name"], AC . $_FILES["file"]["name"]);
        $path = $_FILES["file"]["name"];

        $message->getMessageCode($ac->setNewAcImage($id,$path));
        echo buildJSONOutput(array($message->displayMessage(),$_REQUEST["p_element"],$_REQUEST["c_element"],0));
    } else {
        $message->getMessageCode("ERR_ADMIN_NO_IMAGE");
        echo buildJSONOutput($message->displayMessage());
    }
?>