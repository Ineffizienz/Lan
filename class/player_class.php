<?php
/*
	TODO
		- validate if output IP is an IP
*/
class Player {
	private $db_con;
	private $player;
	private $id;
	private $ip;
	private $username;
	private $realname;
	private $team_id;
	private $team_name;
	private $team_captain;
	private	$profil_image;
	private $first_login;
	private $ticket_active;
	private	$pref = array();
	private $achievement = array();
	private $wow_account;
	private $status_id;
	private $status_name;

	public function __construct ($con, int $player_id)
	{
		$this->db_con = $con;
		$this->id = $player_id;
		
		$this->getPlayerBasicData();
		$this->getPlayerDataTeam();
		$this->getPlayerDataPreferences();
		$this->getPlayerDataWowAccount();
		$this->getPlayerDataAchievements();
		$this->getPlayerStatusData();
	}

	private function getPlayerBasicData()
	{
		$result = mysqli_query($this->db_con,"SELECT IP, name, real_name, team_id, team_captain, profil_image, first_login, ticket_active FROM player WHERE ID = '$this->id'");
		while($row=mysqli_fetch_array($result))
		{
			$this->ip = $row["IP"];
			$this->username = $this->validatePlayerData($row["name"]);
			$this->realname = $this->validatePlayerData($row["real_name"]);
			$this->team_id = $this->validatePlayerData($row["team_id"]);
			$this->team_captain = $this->validatePlayerData($row["team_captain"]);
			$this->image = $row["profil_image"];
			$this->first_login = $row["first_login"];
			$this->ticket_active = $this->translateTicketStatus($row["ticket_active"]);
		}
	}

	private function getPlayerDataTeam()
	{
		if(!($this->team_id == ""))
		{
			$result = mysqli_query($this->db_con,"SELECT name FROM tm_teamname WHERE ID = '$this->team_id'");
			while($row=mysqli_fetch_array($result))
			{
				$this->team_name = $this->validatePlayerData($row["name"]);
			}
		} else {
			$this->team_name = "";
		}
	}

	private function getPlayerDataPreferences()
	{
		$result = mysqli_query($this->db_con,"SELECT games.ID, games.name, games.icon, games.short_title FROM pref LEFT JOIN games ON pref.game_id = games.ID WHERE pref.player_id = '$this->id'");
		while($row=mysqli_fetch_assoc($result))
		{
			if(!empty($row))
			{
				array_push($this->pref,$row);
			} else {
				array_push($this->pref,"Du hast noch keine gesetzt.");
			}
		}

	}

	private function getPlayerDataAchievements()
	{
		$result = mysqli_query($this->db_con,"SELECT ac_id FROM ac_player WHERE player_id = '$this->id'");
		while($row=mysqli_fetch_assoc($result))
		{
			if(!empty($row))
			{
				array_push($this->achievement,$row["ac_id"]);
			} else {
				array_push($this->achievement,"Du hast noch keine Achievements erhalten.");
			}
		}
	}

	private function getPlayerStatusData()
	{
		$result = mysqli_query($this->db_con,"SELECT status.status AS id, status_name.status_name FROM status INNER JOIN status_name ON status_name.status_id = status.status WHERE user_id = '$this->id'");
		while($row=mysqli_fetch_array($result))
		{
			$this->status_id = $row["id"];
			$this->status_name = $row["status_name"];
		}
	}

	private function getPlayerDataWoWAccount()
	{
		$result = mysqli_query($this->db_con,"SELECT wow_account FROM player WHERE ID = '$this->id'");
		while($row=mysqli_fetch_array($result))
		{
			$this->wow_account = $this->validatePlayerData($row["wow_account"]);
		}
	}

	private function validatePlayerData($output)
	{
		if(empty($output) || $output == "")
		{
			return "";
		} else {
			return $output;
		}
	}

	private function translateTicketStatus($t_status)
	{
		if(empty($t_status))
		{
			return "Inaktiv";
		} else {
			return "Aktiv";
		}
	}

	/************************************************************************************
	 *	SET NEW USER/USER-DATA
	*************************************************************************************/
	public function setNewUser($new_name,$new_ip)
	{
		$sql = "INSERT INTO player (name,ip,wow_account,team_id,team_captain,ticket_id,ticket_active,first_login) VALUES ('$c_name','$new_ip',NULL,NULL,NULL,NULL,NULL,'1')";
		if(mysqli_query($this->db_con,$sql))
		{
			return "SUC_ADMIN_NEW_PLAYER";
		} else {
			return "ERR_ADMIN_DB";
		}
	}

	public function setNewUsername($new_username)
	{
		$sql = "UPDATE player SET name = '$new_username' WHERE ID = '$this->id'";
		if(mysqli_query($this->db_con,$sql))
		{
			return "SUC_CHANGE_USERNAME";
		} else {
			return "ERR_CHANGE_USERNAME";
		}
	}

	public function removePlayerFromSystem()
	{
		if($this->first_login == "1")
		{
			$this->removePlayer();
		} else {
			$this->deleteStatus();
			$this->resetKeys();
			$this->removePreferences();
			$this->removePlayerAchievements();
			$this->removePlayer();
		}
	}
	
	private function removePlayer()
	{
		$sql = "DELETE FROM player WHERE ID = '$this->id'";
		if(mysqli_query($this->db_con,$sql))
		{
			return "SUC_ADMIN_DELETE_USER";
		} else {
			return "ERR_ADMIN_DB";
		}
	}

	/************************************************************************************
	 *	KEYS
	*************************************************************************************/

	private function resetKeys()
	{
		$sql = "UPDATE gamekeys SET player_id = NULL WHERE player_id = '$this->id'";
		if(!mysqli_query($this->db_con,$sql))
		{
			return "ERR_ADMIN_DB";
		}
	}

	/************************************************************************************
	 *	STATUS
	*************************************************************************************/

	private function deleteStatus()
	{
		$sql = "DELETE FROM status WHERE user_id = '$this->id'";
		if(!mysqli_query($this->db_con,$sql))
		{
			return "ERR_ADMIN_DB";
		}
	}

	/************************************************************************************
	 *	PREFRENCES
	*************************************************************************************/

	public function setNewPreference($new_preference)
	{
		$sql = "INSERT pref (player_id,game_id) VALUES ('$this->id','$new_preference')";
		if(mysqli_query($this->db_con,$sql))
		{
			return "SUC_ADD_PREF";
		} else {
			return "ERR_ADD_PREF";
		}
	}

	public function removePreference($pref)
	{
		$sql = "DELETE FROM pref WHERE player_id = '$this->id' AND game_id = '$pref'";
		if(mysqli_query($this->db_con,$sql))
		{
			return "SUC_DELETE_PREF";
		} else {
			return "ERR_DELETE_PREF";
		}
	}

	private function removePreferences()
	{
		$sql = "DELETE FROM pref WHERE player_id = '$this->id'";
		if(mysqli_query($this->db_con,$sql))
		{
			return "ERR_ADMIN_DB";
		}
	}

	/************************************************************************************
	 *	ACHIEVEMENTS
	*************************************************************************************/

	public function setNewAchievementAdmin($new_achievement)
	{
		$sql = "INSERT ac_player (player_id,ac_id) VALUES ('$this->id','$new_achievement')";
		if(mysqli_query($this->db_con,$sql))
		{
			return "SUC_ADMIN_ASSIGN_AC";
		} else {
			return "ERR_ADMIN_DB";
		}
	}

	private function removePlayerAchievements()
	{
		if(!empty($this->achievement))
		{
			$sql = "DELETE FROM ac_player WHERE player_id = '$this->id'";
			if(!mysqli_query($this->db_con,$sql))
			{
				return "ERR_ADMIN_DB";
			}
		}
	}
	
	public function getFullBasicData()
	{
		return array("ID" => $this->id,"IP" => $this->ip,"username" => $this->username,"realname" => $this->realname,"team_id" => $this->team_id,"team_name" => $this->team_name,"team_captain" => $this->team_captain,"wow_account" => $this->wow_account);
	}
	
	public function getPlayerId()
	{
		return $this->id;
	}

	public function getPlayerIp()
	{
		return $this->ip;
	}

	public function getPlayerUsername()
	{
		return $this->username;
	}

	public function getPlayerRealname()
	{
		return $this->realname;
	}

	public function getPlayerTeamId()
	{
		return $this->team_id;
	}

	public function getPlayerTeamName()
	{
		return $this->team_name;
	}

	public function getPlayerTeamCaptain()
	{
		return $this->team_captain;
	}

	public function getPlayerProfilImage()
	{
		return $this->image;
	}

	public function getPlayerFirstLogin()
	{
		return $this->first_login;
	}

	public function getPlayerTicketActive()
	{
		return $this->ticket_active;
	}

	public function getPlayerPreferences()
	{
		return $this->pref;
	}

	public function getPlayerAchievements()
	{
		return $this->achievement;
	}

	public function getPlayerWowAccount()
	{
		return $this->wow_account;
	}

	public function getPlayerStatusId()
	{
		return $this->status_id;
	}

	public function getPlayerStatusName()
	{
		return $this->status_name;
	}
}
?>