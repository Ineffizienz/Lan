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
	private	$pref = array();

	public function __construct ($con, int $player_id)
	{
		$this->db_con = $con;
		$this->id = $player_id;
		
		$this->getPlayerBasicData();
		$this->getPlayerDataPreferences();
	}

	private function getPlayerBasicData()
	{
		$result = mysqli_query($this->db_con,"SELECT IP, name, real_name, profil_image FROM player WHERE ID = '$this->id'");
		while($row=mysqli_fetch_array($result))
		{
			$this->ip = $row["IP"];
			$this->username = $this->validatePlayerData($row["name"]);
			$this->realname = $this->validatePlayerData($row["real_name"]);
			$this->image = $row["profil_image"];
		}
	}

	private function getPlayerDataPreferences()
	{
		$result = mysqli_query($this->db_con,"SELECT games.icon, games.short_title FROM pref LEFT JOIN games ON pref.game_id = games.ID WHERE pref.player_id = '$this->id'");
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
		$sql = "DELETE FROM pref WHERE player_id = '$player->id' AND game_id = '$pref'";
		if(mysqli_query($this->db_con,$sql))
		{
			return "SUC_DELETE_PREF";
		} else {
			return "ERR_DELETE_PREF";
		}
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

	public function getPlayerPreferences()
	{
		return $this->pref;
	}
}
?>