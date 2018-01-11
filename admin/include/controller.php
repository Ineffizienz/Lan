<?php
	switch($_REQUEST["aa"]) {
		case "home":
			$content = build_content("../template/admin/overview.html");
		break;
		case "player":
			$content = build_content("../template/admin/settings_player.html");
		break;
		case "keys":
			$content = build_content("../template/admin/keys.html");
		break;
		case "team":
			$content = build_content("../template/admin/team.html");
		break;
		case "achieve":
			$content = build_content("../template/admin/achievement_settings.html");
		break;
		case "ticket":
			$content = build_content("../template/admin/ticket_status.html");
		break;
		default:
			$content = build_content("../template/admin/overview.html");
	}

	if(isset($_REQUEST["subaa"])) {
		switch($_REQUEST["subaa"]) {
			case "action":
				$content = build_content("../template/admin/ac_action.html");
			break;
		}
	}
?>