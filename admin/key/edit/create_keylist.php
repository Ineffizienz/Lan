<?php

//NOTES:
/*
        - success Message/handling missing
        - delete File missing
        - close file connection missing 
*/

include(dirname(__FILE__,4) . "/include/init/constant.php");
include(dirname(__FILE__,3) . "/include/admin_function.php");
include(INC . "connect.php");
include(CL . "message_class.php");

// validate if Game exists allready in DB


$error = new message();

$response = validateInput($_REQUEST["game"]);

if ($response === TRUE)
{
        $new_game = $_REQUEST["game"];
        if($_FILES["file"]["size"] == 0)
        {
                $error->getMessageCode("ERR_ADMIN_FILE_TO_HUGE");
                $error->displayMessage();      
        } else {
                move_uploaded_file($_FILES["file"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/key_list/" . $_FILES["file"]["name"]);
                $new_raw_name = rtrim($_FILES["file"]["name"],".txt");
                $key_list = file($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/key_list/" . $_FILES["file"]["name"]);

                if(verifyGame($con,$new_game,$new_raw_name))
                {

                        foreach ($key_list as $key)
                        {
                                $key = strtoupper($key);
                                $key_response = verifyKey($con,$new_raw_name,$key);
                                if ($key_response === true)
                                {
                                        mysqli_query($con,"INSERT INTO $new_raw_name (game_key,player_id) VALUES ('$key',NULL)");
                                } else {
                                        $error->getMessageCode($key_response);
                                        $error->displayMessage();
                                }

                        }
                        
                        //unlink($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/key_list/" . $_FILES["file"]["name"]);

                } else {

                        createGame($con,$new_game,$new_raw_name);                        
                        foreach ($key_list as $key)
                        {
                                $key = strtoupper($key);
                                $sql = "INSERT INTO $new_raw_name (game_key,player_id) VALUES ('$key',NULL)";
                                if(mysqli_query($con,$sql) === TRUE)
                                {
                                        echo "Läuft.<br>";
                                } else {
                                        echo mysqli_error($con);
                                }
                        }

                        //unlink($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/key_list/" . $_FILES["file"]["name"]);
                }
        }
} else {
        $error->getMessageCode($response);
        $error->displayMessage();
}
?>