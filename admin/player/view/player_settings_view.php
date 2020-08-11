<?php
	
	$output = new template("/admin/part/player_table.html");

	$players = getAllUserIDs($con);

	$player_data = array();

	foreach ($players as $id)
	{
		$player = new Player($con,$id);
		array_push($player_data,$player->getFullBasicData());
	}
	$output->assign_array($player_data);
?>