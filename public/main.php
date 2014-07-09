<?php

include 'db_helper.php';
define('DEBUG', TRUE);

$action = null;

// start session to save GTID
session_start();

// need to save selected course as well

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
            showId();
            break;
        case "showId":
            showId();
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
//        echo "FAILED to login";
        throw new Exception('FAILED to login');
    } else {
        $_SESSION['gtid'] = $data->gtid;
    }
}

function showId() {
    if (isset($_SESSION['gtid'])) {
        echo $_SESSION['gtid'];
    } else {
        throw new Exception("NO ID");
    }

}

?>
