<?php
	$output = new template("part/table_overview.html");
	$result = mysqli_query($con, ""
			. "SELECT name, "
			. "(SELECT COUNT(*) FROM gamekeys WHERE (games.ID = gamekeys.game_id) GROUP BY games.ID) as total, "
			. "(SELECT COUNT(*) FROM gamekeys WHERE (games.ID = gamekeys.game_id) AND (gamekeys.player_id IS NULL) GROUP BY games.ID) as available "
			. "FROM games ORDER BY name ASC;");
	$key_status = array();
	while($row=mysqli_fetch_array($result))
	{
		$name = $row['name'];
		$total = $row['total'];
		if(!is_numeric($total))
			$total = 0;
		$available = $row['available'];
		if(!is_numeric($available))
			$available = 0;

		array_push($key_status,array("game"=>$name,"key"=>"$available / $total"));
	}

	$output->assign_array($key_status);
?>