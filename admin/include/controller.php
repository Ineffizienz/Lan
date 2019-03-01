<?php
	if (isset($_REQUEST["aa"]))
	{
		switch($_REQUEST["aa"]) {
			case "home":
				$content = buildContent("../template/admin/overview.html");
			break;
			case "player":
				$content = buildContent("../template/admin/settings_player.html");
			break;
			case "games":
				$content = buildContent("../template/admin/game_settings.html");
			break;
			case "keys":
				$content = buildContent("../template/admin/keys.html");
			break;
			case "team":
				$content = buildContent("../template/admin/team.html");
			break;
			case "turnier":
				$content = buildContent("../template/admin/tm.html");
			break;
			case "achieve":
				$content = buildContent("../template/admin/achievement_settings.html");
			break;
			case "ticket":
				$content = buildContent("../template/admin/ticket_status.html");
			break;
			default:
				$content = buildContent("../template/admin/overview.html");
		}
	} else {
		$content = buildContent("../template/admin/overview.html");
	}
	

	if(isset($_REQUEST["subaa"])) {
		switch($_REQUEST["subaa"]) {
			case "action":
				$content = buildContent("../template/admin/ac_action.html");
			break;
		}
	}
?>