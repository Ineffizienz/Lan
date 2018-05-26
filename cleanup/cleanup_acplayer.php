<?php
	include("../include/connect.php");

	$result = mysqli_query($con,"SELECT ID, name FROM player");
	while($row=mysqli_fetch_assoc($result))
	{
		$users[] = $row;
	}

	$result = mysqli_query($con,"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'Project_Ziphon' AND TABLE_NAME ='ac_player' AND COLUMN_NAME != 'ID' AND COLUMN_NAME != 'ac_id'");
	while($row=mysqli_fetch_assoc($result))
	{
		$existing_player[] = $row["COLUMN_NAME"];
	}

	foreach ($users as $user)
	{
		if(in_array($user["name"],$existing_player))
		{
			$user_id = $user["ID"];
			$username = $user["name"];
			$sql = "ALTER TABLE ac_player CHANGE COLUMN `$username` `$user_id` INT(11) NULL";
			if(mysqli_query($con,$sql))
			{
				echo $username . " zu " . $user_id . " geändert.<br>";
			} else {
				echo var_dump($sql);
				echo mysqli_error($con) . "<br>";
			}
		} else {
			$user_id = $user["ID"];
			$sql = "ALTER TABLE ac_player ADD `$user_id` INT(11) NULL";
			if (mysqli_query($con,$sql))
			{
				echo $user_id . " erfolgreich hinzugefügt.<br>";
			} else {
				echo mysqli_error($con) . "<br>";
			}

		}
	}

	// Falsche Spalten manuell entfernen
?>