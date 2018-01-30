<?php
	include("../include/connect.php");

	$sql = "ALTER TABLE tm_teamname CHANGE ID ID INT(11) AUTO_INCREMENT";
	if(mysqli_query($con,$sql))
	{
		echo "ID erfolgreich das Attribut 'AUTO_INCREMENT' hinzugefügt.<br>";
	} else {
		echo "Da ist ein Fehler aufgetreten: " . mysqli_error($con);
	}
?>