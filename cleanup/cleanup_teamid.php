<?php
	# This file is for cleaning up the messed up Team-IDs 
	include("../include/connect.php");
	
	$sql = "UPDATE player SET team_id = NULL WHERE team_id = '0'";
	if(mysqli_query($con,$sql))
	{
		echo "Datensätze erfolgreich aktualisiert.<br>";
	} else {
		echo "Beim aktualisieren der Datensätze ist ein Fehler aufgetreten: " . mysqli_error($con);
	}
?>