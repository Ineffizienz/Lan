<?php
    include(dirname(__FILE__,4) . "/include/init/constant.php");
	require_once INC . 'session.php';
    include(INC. "connect.php");
    include(INC. "function.php");
    include(CL . "message_class.php");

    $message = new message();
    $tm_id = $_REQUEST["tm_id"];
    $stage = $_REQUEST["stage"];
    $pair_id = $_REQUEST["pair_id"];
    $player_id = $_REQUEST["player_id"];

    if(getGamerslistIdFromPair($con,$player_id,$pair_id))
    {
        $result = $_REQUEST["result"];
        
        $lock_time = getMatchLockTime($con,$pair_id);
        $current_time = date("Y-m-d H:i:s", strtotime("now"));

        //Time based events have to be recoded
        if(!empty($lock_time) && (strtotime($current_time) > strtotime($lock_time)))
        {
            $message->getMessageCode("ERR_MATCH_LOCKED");
            echo json_encode(array("message"=>$message->displayMessage()));
        } elseif (empty($lock_time) || ($lock_time > $current_time)) {

            $message_code = matchResultHandling($con,$tm_id,$stage,$pair_id,$player_id,$result);
            $message->getMessageCode($message_code);
            echo json_encode(array("message"=>$message->displayMessage()));
        }
               
    } else {
        $message->getMessageCode("ERR_INCORRECT_MATCH");
        echo json_encode(array("message"=>$message->displayMessage() . mysqli_error($con)));
    }
?>