<?php
    include(dirname(__FILE__,3) . "/include/init/constant.php");
	require_once INC . 'session.php';
    include(INC . "connect.php");
    include("new_reg.php");
    include(CL . "message_class.php");
    include(CL . "player_class.php");

    $message = new message();
    $player = new Player($con,$_SESSION["player_id"]);
    
    if(!empty($_POST["accountname"]) && !empty($_POST["password"]) && !empty($_POST["email"]))
    {
        $post_accountname = trim(strtoupper($_POST["accountname"]));
        $post_password = trim($_POST["password"]);
        $post_email = trim($_POST["email"]);

        $result = mysqli_query($con_wow,"SELECT COUNT(*) FROM account WHERE username = '$post_accountname'");
        $row = mysqli_fetch_array($result);
        if($row[0] != 0)
        {
            $message->getMessageCode("ERR_ACC_EXISTS");
            echo json_encode(array("message" => $message->displayMessage()));
        } else {
                $new_set = GetSRP6RegistrationData($post_accountname,$post_password);

                $last_ip = $_SERVER["REMOTE_ADDR"];
                
                $sql = "INSERT INTO account (username, salt, verifier, email, last_ip, expansion) VALUES ('$post_accountname', '$new_set[0]', '$new_set[1]', '$post_email', '$last_ip', '2')";
                if(mysqli_query($con_wow,$sql))
                {
                    $message->getMessageCode($player->setNewWoWAccount($post_accountname));
                    echo json_encode(array("message" => $message->displayMessage()));
                }
        }
    } else {
        $message->getMessageCode("ERR_SOMETHING_MISSING");
        echo json_encode(array("message" => $message->displayMessage()));
    }
?>