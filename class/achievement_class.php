<?php
class Achievement {
	
	private $id = "";
	private $title = "";
	private $message = "";
	private $image = "";
	private $trigger = "";
	private $category = "";
	private $visib = "";
	private $ac_template = "";
	private $ac = "";
	private $adminArr = array("--ID--","--NAME--","--IMAGE_URL--","--MESSAGE--","--TRIGGER--","--CATEGORY--","--VISIBILITY--");
	private $singleArr = array("--IMAGE--","--HEADLINE--","--TEXT--");
	private $basicArr = array("--ID--","--HEADLINE--");


	public function getDetails($single_details)
	{
		$this->title = $single_details["title"];
		$this->message = $single_details["message"];

		if(empty($single_details["image_url"]))
		{
			$this->image = "NULL";
		} else {
			$this->image = "images/achievements/" . $single_details["image_url"];
		}

		$this->buildAchievement();
	}

	public function getDetailsLowAc($achievement_details)
	{
		if (empty($achievement_details))
		{
			$this->ac = "File not Found.";
		} else {

				$this->title = $achievement_details["title"];
				$this->message = $achievement_details["message"];

				if (empty($achievement_details["image_url"]))
				{
					$this->image = "NULL";
				} else {
					$this->image = "images/achievements/" . $achievement_details["image_url"];
				}

				$this->buildLowAchievement();
		}
	}
	
	public function getAdminDetails($admin_achievements)
	{
		if (empty($admin_achievements))
		{
			$this->ac = "File not Found.";
		} else {
			$this->id = $admin_achievements["ID"];
			$this->title = $admin_achievements["title"];
			$this->message = $admin_achievements["message"];
			$this->trigger = $admin_achievements["trigger_title"];
			$this->category = $admin_achievements["c_name"];
			
			if (empty($admin_achievements["image_url"]))
			{
				$this->image = "NULL";
			} else {
				$this->image = "images/achievements/" . $admin_achievements["image_url"];
			}
			
			if($admin_achievements["ac_visibility"] == "1")
			{
				$this->visib = "Sichtbar";
			} elseif ($admin_achievements["ac_visibility"] == "0") {
				$this->visib = "Unsichtbar";
			}
			
			$this->buildAdminAchievement();
		}
	}
	
	public function getBasicDetails($basic)
	{
		$this->id = $basic["ID"];
		$this->title = $basic["title"];
		
		$this->buildBasicAchievement();
	}
	
	public function buildAchievement()
	{
		$this->ac_template = file_get_contents("template/part/single_achievement.html");

		if($this->image == "NULL")
		{
			$this->ac = str_replace($this->singleArr, array("images/keinbild.jpg",$this->title,$this->message),$this->ac_template);
		} else {	
			if(file_exists($this->image))
			{

				$this->ac = str_replace($this->singleArr, array($this->image,$this->title,$this->message), $this->ac_template);

			} else {
				$this->ac = str_replace($this->singleArr, array("Kein Bild",$this->title,$this->message), $this->ac_template);
			}
		}
	}

	public function buildLowAchievement()
	{
		$this->ac_template = file_get_contents("template/part/low_achievement.html");

		if($this->image == "NULL")
		{
			$this->ac .= str_replace(array($this->r_title,$this->r_message,$this->r_image), array($this->title,$this->message,"images/keinbild.jpg"),$this->ac_template);
		} else {
			if(file_exists($this->image))
			{
				$this->ac = str_replace(array($this->r_title,$this->r_message,$this->r_image), array($this->title,$this->message,$this->image), $this->ac_template);
			} else {
				$this->ac = str_replace(array($this->r_title,$this->r_message,$this->r_image), array($this->title,$this->message,"Kein Bild"), $this->ac_template);
			}
		}
	}
	
	public function buildAdminAchievement()
	{
		$this->ac_template = file_get_contents("template/admin/part/ac_list.html");
		
		if($this->image == "NULL")
		{
			$this->ac .= str_replace($this->adminArr, array($this->id,$this->title,"keinbild.jpg",$this->message,$this->trigger,$this->category,$this->visib), $this->ac_template);
		} else {
			if(file_exists($this->image))
			{
				$this->ac = str_replace($this->adminArr, array($this->id,$this->title,$this->image,$this->message,$this->trigger,$this->category,$this->visib), $this->ac_template);
			} else {
				$this->ac = str_replace($this->adminArr, array($this->id,$this->title,"Error",$this->message,$this->trigger,$this->category,$this->visib), $this->ac_template);
			}
		}
	}
	
	public function buildBasicAchievement()
	{
		$this->ac_template = file_get_contents("template/part/ac_small.html");
		
		$this->ac = str_replace($this->basicArr,array($this->id,$this->title),$this->ac_template);
	}

	public function displayAchievement()
	{
		return $this->ac;
	}	
}
	
?>