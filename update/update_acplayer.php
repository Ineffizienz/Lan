<?php

include(dirname(__FILE__,2) . "/include/connect.php");

$sql = "CREATE TABLE ac_player_temp (ID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, player_id INT(11) NOT NULL, ac_id INT(11) NOT NULL, UNIQUE (player_id,ac_id))";
if(mysqli_query($con,$sql))
{
	$result = mysqli_query($con,"SELECT ID FROM player");
	while($row=mysqli_fetch_array($result))
	{
		$user_ids[] = $row["ID"];
	}
	foreach ($user_ids as $user)
	{
		$ac_data = array();
		$result = mysqli_query($con,"SELECT ac_id FROM ac_player WHERE `$user` = '1'");
		if(!empty($result))
		{
			while($row=mysqli_fetch_array($result))
			{
				$ac_data[] = $row["ac_id"];
			}
			foreach ($ac_data as $earned_ac)
			{
				$sql = "INSERT INTO ac_player_temp (player_id, ac_id) VALUES ('$user','$earned_ac')";
				if(mysqli_query($con,$sql))
				{
					echo "Der Spieler mit der ID " . $user . " hat das Achievement mit der ID " . $earned_ac . " zugewiesen bekommen.<br>";
				} else {
					echo "Bei der Zuweisung von Spieler " . $user . " und Achievement " . $earned_ac . " ist ein Fehler aufgetreten: " . mysqli_error($con) . "<br>";
				}
			}
		}
	}
	$delete_sql = "DROP TABLE ac_player";
	if(mysqli_query($con,$delete_sql))
	{
		echo "Tabelle <i>ac_player</i> erfolgreich gelöscht.<br>";
		$rename_sql = "RENAME TABLE ac_player_temp TO ac_player";
		if(mysqli_query($con,$rename_sql))
		{
			echo "Tabelle <i>ac_player_temp</i> in <i>ac_player</i> umbenannt.<br>";
		} else {
			echo "Bei der Umbenennung ist ein Fehler aufgreteten: <b>" . mysqli_error($con) . "</b><br>";
		}
	} else {
		echo "Beim Löschen der Tabelle <i>ac_player</i> ist ein Fehler aufgetreten: <b>" . mysqli_error($con) . "</b><br>";
	}
} else {
	echo mysqli_error($con);
}
?>