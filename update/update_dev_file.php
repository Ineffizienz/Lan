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
?>