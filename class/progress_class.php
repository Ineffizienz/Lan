<?php
class Progress {
	
	private $DBC = "";
	private $user = "";
	private $userAC = "";
	private $acID = "";
	private $acName = "";
	private $acImage = "";
	private $acMessage = "";
	private $acTemplate = "";
	private $ac = "";
	
	public function getTrigger($con,$player_id,$ac_trigger)
	{
		$this->DBC = $con;
		$this->user = $player_id;
		$this->acTrigger = $ac_trigger;
		
		$this->getRequiredData(); //Daten aus der Datenbank beziehen
	}
	
	public function getRequiredData()
	{
		$result = mysqli_query($this->DBC,"SELECT ID FROM ac_trigger WHERE trigger_title = '$this->acTrigger'");
		while($row=mysqli_fetch_array($result))
		{
			$this->acID = $row["ID"];
		}
		
		$result = mysqli_query($this->DBC,"SELECT title, image_url, message FROM ac WHERE ID = '$this->acID'");
		while($row=mysqli_fetch_array($result))
		{
			$this->acName = $row["title"];
			$this->acImage = "images/achievements/" . $row["image_url"];
			$this->acMessage = $row["message"];
		}
		
		$this->fetchData(); // Arbeit mit den Daten
	}
	
	public function fetchData()
	{
		$sql = "INSERT INTO ac_player (player_id, ac_id) VALUES ('$this->user','$this->acID')";
		if(mysqli_query($this->DBC,$sql))
		{
			$this->createAchievement();	// Funktionsaufruf zum Einbinden des Achievement-Templates
		} else {
			return null;
		}		
	}
	
	public function createAchievement()
	{
		$this->acTemplate = file_get_contents(dirname(__FILE__,2) . "/template/part/progress.html");
		
		$this->ac = str_replace(array("--IMAGE--","--TITLE--","--TEXT--"),array($this->acImage,$this->acName,$this->acMessage),$this->acTemplate);
		
		$this->showAchievement(); //Achievement Ausgabe
	}
	
	public function showAchievement()
	{
		return $this->ac;
	}
}

?>