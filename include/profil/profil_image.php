<?php
	include(dirname(__FILE__,3) . "/include/init/constant.php");
	require_once INC . 'session.php';
	include(CL . "message_class.php");
	include(CL . "progress_class.php");
	include(CL . "player_class.php");
	include(INC . "connect.php");
	include(INC . "function.php");

	$message = new message();
	$achievement = new Progress();
	$player = new Player($con,$_SESSION["player_id"]);

	$extension = pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION);
	$root_path = ROOT . "/user/" . $player->getPlayerUsername();

	if(file_exists($root_path))
	{
		if(file_exists($root_path . $player->getPlayerProfilImage()))
		{
			unlink($root_path . $player->getPlayerProfilImage());
		}
		
		move_uploaded_file($_FILES["file"]["tmp_name"], $root_path . "/profil_image." . $extension);

		$path = "user/" . $player->getPlayerUsername() . "/profil_image." . $extension;

		$message->getMessageCode($player->setNewProfilImage($path));
		echo json_encode(array("message" => $message->displayMessage(), "image" => $path));

	} else {
		mkdir($root_path);

		move_uploaded_file($_FILES["file"]["tmp_name"], $root_path . "/profil_image." . $extension);

		$path = "user/" . $player->getPlayerUsername() . "/profil_image." . $extension;

		$sql = "UPDATE player SET profil_image = '$path' WHERE ID = '$player_id'";

		$message->getMessageCode($player->setNewProfilImage());
		echo json_encode(array("message" => $message->displayMessage()));
	}
?>