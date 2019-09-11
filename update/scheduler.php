<?php

function defineEvents($con)
{
    return scheduleEndOfVote($con);
}

function scheduleEndOfVote($con)
{
    $sql = "CREATE EVENT IF NOT EXISTS close_vote 
    ON SCHEDULE EVERY 15 MINUTE
    STARTS CURRENT_TIMESTAMP
    ENABLE
    DO
    UPDATE tm_vote SET vote_closed = 1 WHERE endtime <= NOW()";
    
    if(mysqli_query($con,$sql))
    {
        $response = "Ereignis <i>close_vote</i> erfolgreich angelegt.<br>";
    } else {
        $response = "Beim Erstellen des Ereignisses <i>close_vote</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
    }
    
    return $response;
}

?>