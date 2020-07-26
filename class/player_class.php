<?php
/*
	TODO
		- validate if output IP is an IP
*/
class Player {
	private $db_con;
	private $player;
	public  $id;
	public 	$ip;
	public  $username;
	public  $realname;

	public function __construct ($con, int $player_id)
	{
		$this->db_con = $con;
		$this->id = $player_id;
		
		$this->getPlayerBasicData();
	}

	private function getPlayerBasicData()
	{
		$result = mysqli_query($this->db_con,"SELECT IP, name, real_name FROM player WHERE ID = '$this->id'");
		while($row=mysqli_fetch_array($result))
		{
			$this->ip = $row["IP"];
			$this->username = $this->validatePlayerData($row["name"]);
			$this->realname = $this->validatePlayerData($row["real_name"]);

			$this->returnPlayer();
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

	public function returnPlayer()
	{
		return $this->player;
	}
	
}
?>