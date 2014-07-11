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

    $data = json_decode($json);
    switch ($action) {
        case "login":
            login($data);
            break;
        case "showAvaiTutor":
            showAvaiTutor($data);
            break;
        case "rateTutor":
            rateTutor($data);
            break;
        case "submitTutorApp":
            submitTutorApp($data);
            break;
        case "showTutorSchedule":
            showTutorSchedule();
            break;
        case "fetchSchoolList":
            fetchSchoolList();
            break;
        case "fetchCourseNumberList":
            fetchCourseNumberList($data->school);
            break;
        case "getCurrentUserId":
            getCurrentUserId();
            break;
    }
}

function login($data)
{

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

function showAvaiTutor($data)
{
    // TODO: work on this
}

function submitTutorApp($data)
{
    // TODO: work on this
}

function showTutorSchedule() {
    $gtid = getCurrentUserId();

    // TODO: fix this query
    $dbQuery = sprintf("SELECT Number
                        FROM tb_Course
                        WHERE School = '%s'
                        ORDER BY Number;",
        mysql_real_escape_string($school));

    $result = getDBResultsArray($dbQuery);
    echo json_encode($result);

}

function rateTutor($data)
{
    // record a student evaluation in database

    $dbQuery = sprintf("INSERT INTO tb_Rates
                        VALUES('%s', '%s', '%s', '%s', '%s', '%d', '%s')",
        mysql_real_escape_string(getCurrentUserGTID),
        mysql_real_escape_string($data->tutorId),
        mysql_real_escape_string($data->courseSchool),
        mysql_real_escape_string($data->courseNumber),
        mysql_real_escape_string($data->descriptiveEval),
        mysql_real_escape_string($data->numericEval),
        mysql_real_escape_string(getCurrentSemester()));

    $result = getDBResultAffected($dbQuery);

    echo json_encode($result);
}

function fetchSchoolList()
{
    $dbQuery = sprintf("SELECT DISTINCT School
                        FROM tb_Course
                        ORDER BY School;");

    $result = getDBResultsArray($dbQuery);
    echo json_encode($result);
}

function fetchCourseNumberList($school)
{
    $dbQuery = sprintf("SELECT Number
                        FROM tb_Course
                        WHERE School = '%s'
                        ORDER BY Number;",
        mysql_real_escape_string($school));

    $result = getDBResultsArray($dbQuery);
    echo json_encode($result);
}

function getCurrentUserId()
{
    if (isset($_SESSION['gtid'])) {
        return $_SESSION['gtid'];
    } else {
        throw new Exception("NO ID");
    }

}

function getCurrentSemester()
{
    return "Summer";
//    TODO: maybe returning semester based on the month
}

?>
