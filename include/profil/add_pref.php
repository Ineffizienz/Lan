<?php
    session_start();
    include(dirname(__FILE__,2) . "/init/constant.php");
    include(INC . "connect.php");
    include(INIT . "get_parameters.php");
    include(CL . "message_class.php");
    include(CL . "progress_class.php");

    $message = new message();
    $achievement = new Progress();
    $game_id = $_REQUEST["checkedGame"];

    //$user_id = getUserId($con,IP); --> remove
    $player_id = $_SESSION["player_id"];
    $user_pref = getUserPref($con,$player_id);

    if(empty($user_pref))
    {
         //INSERT new value
        $sql = "INSERT INTO pref (user_id,preferences) VALUES ('$player_id','$game_id')";
        if(mysqli_query($con,$sql))
        {
            echo "Inserted";
        } else {
            $message->getMessageCode("ERR_DB");
            echo json_encode(array("message" => $message->displayMessage()));
        }
    } else {
        //UPDATE existing value
        $prefs = explode(", ",$user_pref);
        if(!in_array($game_id,$prefs))
        {
            $new_pref = $user_pref . ", " . $game_id;
            $sql = "UPDATE pref SET preferences = '$new_pref' WHERE user_id = '$player_id'";
            if(mysqli_query($con,$sql))
            {
                echo json_encode(array("message" => "Updated"));
            } else {
                $message->getMessageCode("ERR_DB");
                echo json_encode(array("message" => $message->displayMessage()));
            }
        } else {
         
        // DELETE existing value
            $updated_pref = str_replace(", " . $game_id,"",$user_pref);

            $sql = "UPDATE pref SET preferences = '$updated_pref' WHERE user_id = '$player_id'";
            if(mysqli_query($con,$sql))
            {
                echo json_encode(array("message" => "Deleted"));
            } else {
                $message->getMessageCode("ERR_DB");
                echo json_encode(array("message" => $message->displayMessage()));
            }
        }

    }
?>