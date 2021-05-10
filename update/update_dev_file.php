<?php
function updateGameMaps($con)
{
    if(mysqli_query($con,"ALTER TABLE game_maps ADD selectable INT(11) NOT NULL AFTER map_image"))
    {
        echo "Die Spalte <i>selectable</i> wurde erfolgreich hinzugefügt.<br>";
    } else {
        echo "Beim Hinzufügen der Spalte <i>selectable</i> ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
    }
}

function updatePlayerNick($con)
{
    if(mysqli_query($con,"ALTER TABLE player CHANGE name player_nick VARCHAR(255)"))
    {
        echo "Die Spalte <i>name</i> wurde in <i>player_nick</i> umbenannt.<br>";
    } else {
        echo "Beim Umbenennen der Spalte <i>name</i> in der Tabelle <i>player</i> ist ein Fehler aufgreteten: " . mysqli_error($con) . "<br>";
    }
}
?>