<?php

include 'db_helper.php';
define('DEBUG', TRUE);

$listUser = "SELECT gtid FROM tb_user;";

var_dump(getDBResultsArray($listUser));

?>
