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
	private	$profil_image;
	private $first_login;
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
		$this->getPlayerDataPreferences();
		$this->getPlayerDataWowAccount();
		$this->getPlayerDataAchievements();
		$this->getPlayerStatusData();
	}

	private function getPlayerBasicData()
	{
		$result = mysqli_query($this->db_con,"SELECT IP, name, real_name, profil_image, first_login FROM player WHERE ID = '$this->id'");
		while($row=mysqli_fetch_array($result))
		{
			$this->ip = $row["IP"];
			$this->username = $this->validatePlayerData($row["name"]);
			$this->realname = $this->validatePlayerData($row["real_name"]);
			$this->image = $row["profil_image"];
			$this->first_login = $row["first_login"];
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
			$this->wow_account = $row["wow_account"];
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

	public function getPlayerProfilImage()
	{
		return $this->image;
	}

	public function getPlayerFirstLogin()
	{
		return $this->first_login;
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