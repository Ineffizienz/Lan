<?php

include(dirname(__FILE__,4) . "/include/init/constant.php");
include(dirname(__FILE__,3) . "/include/admin_func.php");
include(INIT . "get_parameters.php");
include(INC . "connect.php");
include(CL . "message_class.php");

$message = new message();

if (isset($_REQUEST["game"]))
{
    if (empty($_REQUEST["game"]))
    {
        $message->getMessageCode("ERR_ADMIN_INTERN_#2");
        echo json_encode(array("message" => $message->displayMessage()));
    } else if (empty($_REQUEST["mode"])) {
        $message->getMessageCode("ERR_ADMIN_INTERN_#3");
        echo json_encode(array("message" => $message->displayMessage()));
    } else {

        $tms_game = getTournamentGames($con);

        if(in_array($_REQUEST["game"],$tms_game))
        {
            foreach($tms_game as $existing_tm)
            {
                $tm_modes = getTournamentModes($con,$existing_tm);
                if ($tm_modes == $_REQUEST["mode"])
                {
                    $message->getMessageCode("ERR_ADMIN_TM_EXISTS");
                    echo json_encode(array("message" => $message->displayMessage()));
                    break;
                } else {
                    $message->getMessageCode("WARN_ADMIN_GAME_HAS_TM");
                    echo json_encode(array("message" => $message->displayMessage()));
                    break;
                }
            }
        } else {
            $result_validate = validateImageFile($_FILES["file"]["size"],pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));
            if ($result_validate == "1")
            {
                move_uploaded_file($_FILES["file"]["tmp_name"], BANNER . $_FILES["file"]["name"]);
                
                $tm_banner = $_FILES["file"]["name"];
                $tm_game = $_REQUEST["game"];
                $tm_mode = $_REQUEST["mode"];
                $sql = "INSERT INTO tm (game, mode, banner) VALUES ('$tm_game','$tm_mode','$tm_banner')";
                if(mysqli_query($con,$sql))
                {
                    $message->getMessageCode("SUC_ADMIN_CREATE_TM");
                    echo json_encode(array("message" => $message->displayMessage()));
                } else {
                    $message->getMessageCode("ERR_ADMIN_CREATE_TM");
                    echo json_encode(array("message" => $message->displayMessage() . mysqli_error($con)));
                }
            } else {
                $message->getMessageCode($result_validate);
                echo $message->displayMessage();
            }
        }        

    } 

} else {
    $message->getMessageCode("ERR_ADMIN_NO_GAME_SELECTED");
    echo json_encode(array("message" => $message->displayMessage()));
}

?>