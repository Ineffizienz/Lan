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
	private $message_code;

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

	/************************************************************************************
	 *	QUERY DATA
	*************************************************************************************/
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

	/************************************************************************************
	 *	DATA VALIDATION
	*************************************************************************************/

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
	private function setPlayerNames($nick,$real_name) //TODO: Funktion separieren
	{
		$sql = "UPDATE player SET name = '$nick' AND real_name = '$real_name' WHERE ID = '$this->id'";
		if(!mysqli_query($this->db_con,$sql))
		{
			return false;
		} else {
			return true;
		}
	}

	private function setFirstLogin()
	{
		if($this->first_login != "0")
		{
			$sql = "UPDATE player SET first_login = '0' WHERE ID = '$this->id'";
			if(!mysqli_query($this->db_con,$sql))
			{
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
	}

	private function setStatusValue()
	{
		$sql = "INSERT INTO status (user_id, status) VALUES ('$this->id','1')";
		if(!mysqli_query($this->db_con,$sql))
		{
			return false;
		} else {
			return true;
		}
	}
	
	public function setUpPlayer($nick, $real_name)
	{
		if($this->setPlayerNames($nick, $real_name))
		{
			if($this->setFirstLogin())
			{
				if($this->setStatusValue())
				{
					return true;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
		
	}

	public function setNewPlayer($new_name,$new_ip)
	{
		$sql = "INSERT INTO player (name,ip,wow_account,team_id,team_captain,ticket_id,ticket_active,first_login) VALUES ('$new_name','$new_ip',NULL,NULL,NULL,NULL,NULL,'1')";
		if(mysqli_query($this->db_con,$sql))
		{
			$this->message_code = "SUC_ADMIN_NEW_PLAYER";
			return $this->message_code;
		} else {
			$this->message_code = "ERR_ADMIN_DB";
			return $this->message_code;
		}
	}

	public function setNewUsername($new_username)
	{
		$sql = "UPDATE player SET name = '$new_username' WHERE ID = '$this->id'";
		if(mysqli_query($this->db_con,$sql))
		{
			$this->message_code = "SUC_CHANGE_USERNAME";
			return $this->message_code;
		} else {
			$this->message_code = "ERR_CHANGE_USERNAME";
			return $this->message_code;
		}
	}

	public function setNewProfilImage($image)
	{
		$sql = "UPDATE player SET profil_image = '$image' WHERE ID = '$this->id'";
		if(mysqli_query($this->db_con,$sql))
		{
			$this->message_code = "SUC_UPLOADED_IMAGE";
			return $this->message_code;
		} else {
			$this->message_code = "ERR_DB";
			return $this->message_code;
		}
	}

	public function removePlayerFromSystem()
	{
		if($this->first_login == "1")
		{
			$this->removePlayer();
			return $this->message_code;
		} else {
			$this->deleteStatus();
			$this->removePreferences();
			$this->removePlayerAchievements();
			$this->removePlayer();
			return $this->message_code;
		}
	}

	private function removePlayer()
	{
		$sql = "DELETE FROM player WHERE ID = '$this->id'";
		if(mysqli_query($this->db_con,$sql))
		{
			$this->message_code = "SUC_ADMIN_DELETE_USER";
			return $this->message_code;
		} else {
			$this->message_code = "ERR_ADMIN_DB";
			return $this->message_code;
		}
	}

	/************************************************************************************
	 *	STATUS HANDLING
	*************************************************************************************/

	public function setNewStatus($status)
	{
		mysqli_query($this->db_con,"UPDATE status SET status = '$status' WHERE user_id = '$this->id'");
	}
	
	private function deleteStatus()
	{
		$sql = "DELETE FROM status WHERE user_id = '$this->id'";
		if(!mysqli_query($this->db_con,$sql))
		{
			$this->message_code = "ERR_ADMIN_DB";
			return $this->message_code;
		}
	}

	/************************************************************************************
	 *	PREFRENCE HANDLING
	*************************************************************************************/

	public function setNewPreference($new_preference)
	{
		$sql = "INSERT pref (player_id,game_id) VALUES ('$this->id','$new_preference')";
		if(mysqli_query($this->db_con,$sql))
		{
			$this->message_code = "SUC_ADD_PREF";
			return $this->message_code;
		} else {
			$this->message_code = "ERR_ADD_PREF";
			return $this->message_code;
		}
	}

	public function removePreference($pref)
	{
		$sql = "DELETE FROM pref WHERE player_id = '$this->id' AND game_id = '$pref'";
		if(mysqli_query($this->db_con,$sql))
		{
			$this->message_code = "SUC_DELETE_PREF";
			return $this->message_code;
		} else {
			$this->message_code = "ERR_DELETE_PREF";
			return $this->message_code;
		}
	}

	private function removePreferences()
	{
		$sql = "DELETE FROM pref WHERE player_id = '$this->id'";
		if(mysqli_query($this->db_con,$sql))
		{
			$this->message_code = "ERR_ADMIN_DB";
			return $this->message_code;
		}
	}

	/************************************************************************************
	 *	ACHIEVEMENT HANDLING
	*************************************************************************************/

	private function removePlayerAchievements()
	{
		if(!empty($this->achievement))
		{
			$sql = "DELETE FROM ac_player WHERE player_id = '$this->id'";
			if(!mysqli_query($this->db_con,$sql))
			{
				$this->message_code = "ERR_ADMIN_DB";
				return $this->message_code;
			}
		}
	}

	/************************************************************************************
	 *	WOW-ACCOUNT HANDLING
	*************************************************************************************/

	public function setNewWowAccount($account_name)
	{
		$sql = "UPDATE player SET wow_account = '$account_name' WHERE ID = '$this->id'";
		if(mysqli_query($this->db_con,$sql))
		{
			$this->message_code = "SUC_ACC_CREATE";
			return $this->message_code;
		} else {
			$this->message_code = "ERR_ACC_CREATE";
			return $this->message_code;
		}
	}

	/************************************************************************************
	 *	GET DATA
	*************************************************************************************/
	
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