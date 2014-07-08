<?php

include 'db_helper.php';
define('DEBUG', TRUE);

$action = null;

if (array_key_exists("action", $_POST)) {
    $action = $_POST["action"];
    $json = $_POST["json"];
    call($action, $json);
}


function call($action, $json)
{
    switch ($action) {
        case "login":
            login($json);
            break;
        case 1:
            break;
        case 2:
            break;
    }
}

function login($json)
{
    $data = json_decode($json);

    $dbQuery = sprintf("SELECT GTID, Password
                        FROM tb_User
                        WHERE GTID='%s' AND Password='%s'",
        mysql_real_escape_string($data->gtid),
        mysql_real_escape_string($data->password));

    $result = getDBResultsArray($dbQuery);

    if (is_null($result)) {
        echo "FAILED to login";
    } else {
        echo "Login successfully";
    }
}

?>
