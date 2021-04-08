<?php

include(dirname(__FILE__,4) . "/include/init/constant.php");
include(dirname(__FILE__,3) . "/include/admin_func.php");
include(INC . "connect.php");
include(CL . "message_class.php");

$message = new message();

if(!$_REQUEST["tm_game_id"] == "0")
{
    if(isset($_REQUEST["tm_name_ingame"]) && !empty($_REQUEST["tm_name_ingame"]))
    {
        if(isset($_REQUEST["tm_map_size"]) && !empty($_REQUEST["tm_map_size"]))
        {
            $game_id = $_REQUEST["tm_game_id"];
            $game_name = rtrim($_REQUEST["tm_game_name"]);
            $ingame_name = $_REQUEST["tm_name_ingame"];
            $map_size = $_REQUEST["tm_map_size"];
            $game_dir = $game_name . "/";
            $image_dir = str_replace("\\","/",MAP . $game_dir);

            if(!file_exists($image_dir))
            {
                mkdir($image_dir, 0777);
            }

            $existing_map_names = getTmMapNamesByGameId($con,$game_id);

            if(in_array($ingame_name,$existing_map_names))
            {
                $message->getMessageCode("ERR_ADMIN_MAP_EXISTS");
                echo buildJSONOutput($message->displayMessage());
            } else {
                $image_path = uploadFile($_FILES,$game_dir,$image_dir);

                $sql = "INSERT INTO game_maps (game_id, game_name, map_name_ingame, map_size, map_image) VALUES ('$game_id', '$game_name', '$ingame_name', '$map_size', '$image_path')";
                if(mysqli_query($con,$sql))
                {
                    $message->getMessageCode("SUC_ADMIN_ADD_MAP");
                    echo buildJSONOutput(array($message->displayMessage(),$_REQUEST["p_element"],$_REQUEST["c_element"],0));
                } else {
                    $message->getMessageCode("ERR_ADMIN_DB");
                    echo buildJSONOutput($message->displayMessage() . mysqli_error($con));
                }
            }

        } else {
            $message->getMessageCode("ERR_ADMIN_NO_MAPSIZE");
            echo buildJSONOutput($message->displayMessage());
        }
    } else {
        $message->getMessageCode("ERR_ADMIN_NO_INGAME_NAME");
        echo buildJSONOutput($message->displayMessage());
    }
} else {
    $message->getMessageCode("ERR_ADMIN_NO_GAME_SELECETD");
    echo buildJSONOutput($message->displayMessage());
}

?>