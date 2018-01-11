<?php
class Player {
	// Output-Parameter
	private $user = "";
	
	// User-Data
	private $id = "";
	private $name = "";
	private $profil_image = "";
	
	// Queried User-Data
	private $user_data = array();
	
	// Template
	private $template = "";
	
	//Replacement-Arrays
	private $rSettingsArr = array("--USERNAME--","--IMAGE--");
	
	
	private $team_id = "";

	public function getUserData($con,$ip)
	{
		$this->queryUserData();
		
		$this->id = $this->user_data["ID"];
		$this->name = $this->user_data["name"];
		$this->profil_image = $this->user_data["profil_image"];
		
		$this->buildUserData();

		
	}
	
	public function queryUserData()
	{
		$result = mysqli_query($con,"SELECT ID, name, profil_image FROM player WHERE ip = '$ip'");
		while($row=mysqli_fetch_array($result))
		{
			$this->user_data = $row;
		}
		
		return $this->user_data;
	}
	
	public function buildUserSettingsPage()
	{
		$this->template = file_get_contents("template/own_settings.html");
		
		$this->user = str_replace($this->rSettingsArr,array($this->name,$this->profil_image),$this->template);
		
		$this->displayUserSettingsPage();
	}
	
	public function displayUserSettingsPage()
	{
		return $this->user;
	}
	
}
?>