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

    $player_id = $_SESSION["player_id"];

    if(isset($_REQUEST["checkedGame"]))
    {
         //INSERT new value
        $sql = "INSERT INTO pref (player_id,game_id) VALUES ('$player_id','$game_id')";
        if(mysqli_query($con,$sql))
        {
            echo json_encode(array("message" => "Inserted"));
        } else {
            $sql = "DELETE FROM pref WHERE player_id = '$player_id' AND game_id = '$game_id'";
            if(mysqli_query($con,$sql))
            {
                echo json_encode(array("message" => "Updated"));
            } else {
                $message->getMessageCode("ERR_DB");
                echo json_encode(array("message" => $message->displayMessage()));
            }
        }
    }
?>