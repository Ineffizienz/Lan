<?php
	$result = mysqli_query($con, ""
			. "SELECT name, "
			. "(SELECT COUNT(*) FROM gamekeys WHERE (games.ID = gamekeys.game_id) GROUP BY games.ID) as total, "
			. "(SELECT COUNT(*) FROM gamekeys WHERE (games.ID = gamekeys.game_id) AND (gamekeys.player_id IS NULL) GROUP BY games.ID) as available "
			. "FROM games ORDER BY name ASC;");
	
	$table_template = file_get_contents("template/part/table_overview.html");
	$key_status = "";
	while($row=mysqli_fetch_array($result))
	{
		$name = $row['name'];
		$total = $row['total'];
		if(!is_numeric($total))
			$total = 0;
		$available = $row['available'];
		if(!is_numeric($available))
			$available = 0;
		$key_status .= str_replace(array("--GAME--","--KEY--"),array($name, "$available / $total"),$table_template);
	}
?>