<?php
include(dirname(__FILE__,2) . "/include/connect.php");
include("update_dev_file.php");

updateGameMaps($con);
updatePlayerNick($con);
?>