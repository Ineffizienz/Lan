<?php
include(dirname(__FILE__,2) . "/include/connect.php");
include("update_file.php");

addRealNameToPlayer($con);
changePlayerCountDataType($con);
addMatchResultToPaarung($con);
transferMatchData($con);
deleteTmMatches($con);
deleteTmMatch($con);
addMatchLockedToPaarung($con);
?>