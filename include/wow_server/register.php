<?php
    include(dirname(__FILE__,3) . "/include/init/constant.php");
    include(INC . "connect.php");
    include(CL . "message_class.php");

    $message = new message();
    
    if(!empty($_POST["accountname"]) && !empty($_POST["password"]) && !empty($_POST["email"]))
    {
        $post_accountname = trim(strtoupper($_POST["accountname"]));
        $post_password = trim(strtoupper($_POST["password"]));
        $post_password_final = sha1("" . $post_accountname . ":" . $post_password . "");
        $post_email = trim($_POST["email"]);

        $result = mysqli_query($con,"SELECT COUNT(*) FROM account WHERE username = '$post_accountname'");
        $row = mysqli_fetch_array($result);
        if($row[0] != 0)
        {
            $message->getMessageCode("ERR_ACC_EXISTS");
            echo json_encode(array("message" => $message->displayMessage()));
        } else {
            if(strlen($post_accountname) < 3)
            {
                $message->getMessageCode("ERR_ACC_SHORT");
                echo json_encode(array("message" => $message->displayMessage()));
            } elseif (strlen($post_accountname) > 32) {
                $message->getMessageCode("ERR_ACC_LONG");
                echo json_encode(array("message" => $message->displayMessage()));
            } elseif (strlen($post_password) < 6) {
                $message->getMessageCode("ERR_PWD_SHORT");
                echo json_encode(array("message" => $message->displayMessage()));
            } elseif (strlen($post_password) > 32) {
                $message->getMessageCode("ERR_PWD_LONG");
                echo json_encode(array("message" => $message->displayMessage()));
            } elseif (strlen($post_email) < 8) {
                $message->getMessageCode("ERR_EMAIL_SHORT");
                echo json_encode(array("message" => $message->displayMessage()));
            } elseif (strlen($post_email) > 64) {
                $message->getMessageCode("ERR_EMAIL_LONG");
                echo json_encode(array("message" => $message->displayMessage()));
            } elseif (!preg_match('/^[A-Z\d]+$/i', $post_accountname)) {
                $message->getMessageCode("ERR_ACC_CHR");
                echo json_encode(array("message" => $message->displayMessage()));
            } elseif(!preg_match('/^[A-Z\d]+$/i',$post_password)) {
                $message->getErrorMessage("ERR_PWD_CHR");
                echo json_encode(array("message" => $message->displayMessage()));
            } else {
                $last_ip = $_SERVER["REMOTE_ADDR"];

                $sql = "INSERT INTO account (username, sha_pass_hash, email, last_ip, expansion) VALUES ('$post_accountname', '$post_password_final', '$post_email', '$last_ip', '2')";
                if(mysqli_query($con,$sql))
                {
                    $message->getMessageCode("SUC_ACC_CREATE");
                    echo json_encode(array("message" => $message->displayMessage()));
                }
            }
        }
    }
?>