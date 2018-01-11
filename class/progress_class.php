<?php
class Progress {
	
	private $DBC = "";
	private $userIP = "";
	private $user = "";
	private $userAC = "";
	private $acTriggerID = "";
	private $acID = "";
	private $acName = "";
	private $acImage = "";
	private $acMessage = "";
	private $acTemplate = "";
	private $ac = "";
	
	public function getTrigger($con,$ip,$ac_trigger)
	{
		$this->DBC = $con;
		$this->userIP = $ip;
		$this->acTrigger = $ac_trigger;
		
		$this->getRequiredData(); //Daten aus der Datenbank beziehen
	}
	
	public function getRequiredData()
	{
		$result = mysqli_query($this->DBC,"SELECT ID FROM ac_trigger WHERE trigger_title = '$this->acTrigger'");
		while($row=mysqli_fetch_array($result))
		{
			$this->acTriggerID = $row["ID"];
		}
		
		$result = mysqli_query($this->DBC,"SELECT ID, title, image_url, message FROM ac WHERE ac_trigger = '$this->acTriggerID'");
		while($row=mysqli_fetch_array($result))
		{
			$this->acID = $row["ID"];
			$this->acName = $row["title"];
			$this->acImage = "images/achievements/" . $row["image_url"];
			$this->acMessage = $row["message"];
		}
		
		$result = mysqli_query($this->DBC,"SELECT name FROM player WHERE ip ='$this->userIP'");
		while($row=mysqli_fetch_array($result))
		{
			$this->user = $row["name"];
		}
		
		$result = mysqli_query($this->DBC,"SELECT $this->user FROM ac_player WHERE ac_id = '$this->acID'");
		while($row=mysqli_fetch_array($result))
		{
			$this->userAC = $row[$this->user];
		}
		
		$this->fetchData(); // Arbeit mit den Daten
	}
	
	public function fetchData()
	{
		
		if(empty($this->userAC))
		{
			$sql = "UPDATE ac_player SET $this->user = '1' WHERE ac_id = '$this->acID'";
			if(mysqli_query($this->DBC,$sql))
			{
				$this->createAchievement();	// Funktionsaufruf zum Einbinden des Achievement-Templates
			}
		} else {
			return null;	
		}
		
	}
	
	public function createAchievement()
	{
		$this->acTemplate = file_get_contents(dirname(__FILE__,2) . "/template/part/progress.html");
		
		$this->ac = str_replace(array("--IMAGE--","--HEADLINE--","--TEXT--"),array($this->acImage,$this->acName,$this->acMessage),$this->acTemplate);
		
		$this->showAchievement(); //Achievement Ausgabe
	}
	
	public function showAchievement()
	{
		return $this->ac;
	}
}

?>