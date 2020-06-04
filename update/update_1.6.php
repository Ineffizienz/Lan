<?php
    include(dirname(__FILE__,2) . "/include/connect.php");

    $sql = "ALTER TABLE tm CHANGE `player_count` `player_count` INT(11) NOT NULL";

    if(mysqli_query($con,$sql))
    {
        echo "Die Spalte <i>player_count</i> wurde erfolgreich auf <i>NOT NULL</i> umgestellt.<br>";
    } else {
        echo mysqli_error($con) . $sql . "<br>";
    }
?>