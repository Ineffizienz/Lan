<?php
	
	$names = getTeamNames($con);
	$team_id = getTeams($con);

	foreach ($team_id as $team)
	{
		$member = getTeamMember($con,$team);
		$table_template = file_get_contents("template/part/basic_table.html");

		$tm = implode(", ",$member);

		if (!isset($team_status))
		{
			$team_status = str_replace(array("--VALUE_1--","--VALUE_2--"),array(array_shift($names),$tm),$table_template);
		} else {
			$team_status .= str_replace(array("--VALUE_1--","--VALUE_2--"),array(array_shift($names),$tm),$table_template);
		}
	}

?>