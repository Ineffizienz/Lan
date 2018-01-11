<?php

//NOTES:
/*
        - success Message/handling missing
        - delete File missing
        - close file connection missing 
*/
include($_SERVER["DOCUMENT_ROOT"] . "/Project_Ziphon/include/init/constant.php");
include(INC . "connect.php");
include(INIT . "get_parameters.php");
include("../include/admin_func.php");
include(CL . "message_class.php");

// validate if Game exists allready in DB


$message = new message();

$response = validateInput($_REQUEST["game"]);

if ($response === TRUE)
{
        $new_game = $_REQUEST["game"];
        if($_FILES["file"]["size"] == 0)
        {
                $message->getMessageCode("ERR_FILE_SIZE");
                echo $message->displayMessage();      
        } else {
                move_uploaded_file($_FILES["file"]["tmp_name"], ROOT . "key_list/" . $_FILES["file"]["name"]);
                $new_raw_name = rtrim($_FILES["file"]["name"],".txt");
                $key_list = file(ROOT . "key_list/" . $_FILES["file"]["name"]);

                if(verifyGame($con,$new_game,$new_raw_name))
                {

                        foreach ($key_list as $key)
                        {
                                $key = strtoupper($key);
                                $key_response = verifyKey($con,$new_raw_name,$key);
                                if ($key_response === true)
                                {
                                        $sql = "INSERT INTO $new_raw_name (game_key,player_id) VALUES ('$key',NULL)";
                                        if(mysqli_query($con,$sql))
                                        {
                                                $message->getMessageCode("SUC_KEY_ADDED");
                                                echo $message->displayMessage();
                                        } else {
                                                $message->getMessageCode("ERR_DB");
                                                echo $message->displayMessage();
                                        }
                                } else {
                                        $message->getMessageCode($key_response);
                                        echo $message->displayMessage();
                                }

                        }
                        
                        unlink(ROOT . "key_list/" . $_FILES["file"]["name"]);

                } else {

                        createGame($con,$new_game,$new_raw_name);                        
                        foreach ($key_list as $key)
                        {
                                $key = strtoupper($key);
                                $sql = "INSERT INTO $new_raw_name (game_key,player_id) VALUES ('$key',NULL)";
                                if(mysqli_query($con,$sql) === TRUE)
                                {
                                        $message->getMessageCode("SUC_KEY_ADDED");
					echo $message->displayMessage();
                                } else {
                                        echo mysqli_error($con);
                                }
                        }

                        unlink(ROOT . "key_list/" . $_FILES["file"]["name"]);
                }
        }
} else {
        $message->getMessageCode($response);
        echo $message->displayMessage();
}
?>