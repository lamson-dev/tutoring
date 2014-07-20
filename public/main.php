<?php

include 'db_helper.php';
define('DEBUG', false);

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
        case "fetchAvaiTutorWithRatingSummary":
            fetchAvaiTutorWithRatingSummary($data);
            break;
        case "submitProfEval":
            submitProfEval($data);
            break;
        case "submitStudentEval":
            submitStudentEval($data);
            break;
        case "submitTutorApp":
            submitTutorApp($data);
            break;
        case "fetchTutorSchedule":
            fetchTutorSchedule($json);
            break;
        case "fetchCourseSchoolList":
            fetchCourseSchoolList();
            break;
        case "fetchCourseNumberList":
            fetchCourseNumberList($data->school);
            break;
        case "fetchTutorNameListByCourse":
            fetchTutorNameListByCourse($data);
            break;
        case "fetchAdminSummary1":
            fetchAdminSummary1($data);
            break;
        case "getCurrentUserId":
            getCurrentUserId();
            break;
        case "getCurrentUserType":
            getCurrentUserType();
            break;
        case "scheduleSelectedTutor":
            scheduleSelectedTutor($data);
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
        error('FAILED to login');
    } else {

        // TODO: get user type from query
        $userType = "test";

        $_SESSION['gtid'] = $data->gtid;
        $_SESSION['user_type'] = $userType;
    }
}

function fetchAvaiTutorWithRatingSummary($data)
{
    // TODO: fetchAvaiTutorWithRatingSummary
}

function scheduleSelectedTutor($data) {

    $studentId = getCurrentUserId();
    $tutorId = getTutorGTIDByName($data->tutorName);
    $courseSchool = $data->courseSchool;
    $courseNumber = $data->courseNumber;
    $weekday = $data->weekday;
    $time = $data->time;
    $semester = getCurrentSemester();

    // TODO: fix this query
    $dbQuery = sprintf("INSERT INTO tb_Recommends (RecTutGTID, RecProfGTID, RecDesc, RecNum)
	                    VALUES ('%s', '%s', '%s', '%s');",
        mysql_real_escape_string($studentId),
        mysql_real_escape_string($tutorId),
        mysql_real_escape_string($courseSchool),
        mysql_real_escape_string($courseNumber),
        mysql_real_escape_string($weekday),
        mysql_real_escape_string($time),
        mysql_real_escape_string($semester));


    $result = getDBResultAffected($dbQuery);
    echo json_encode($result);
}

function submitTutorApp($data)
{
    // TODO: submitTutorApp
    if (!$data->tutorId == getCurrentUserId()) {
        error("Isn't your GTID is " . getCurrentUserId());
    }

    $courses = $data->courses;
    foreach ($courses as $course) {
        list($school, $number) = explode(' ', $course);
    }

    $avai = $data->avai;
    foreach ($avai as $day) {
        $weekday = $day->weekday;
        $times = $day->times;
    }

    // TODO: fix this query
    $dbQuery = sprintf("INSERT INTO tb_Recommends (RecTutGTID, RecProfGTID, RecDesc, RecNum)
	                    VALUES ('%s', '%s', '%s', '%s');",
        mysql_real_escape_string($data->tutorId),
        mysql_real_escape_string($data->firstName),
        mysql_real_escape_string($data->lastName),
        mysql_real_escape_string($data->email),
        mysql_real_escape_string($data->phone),
        mysql_real_escape_string($data->gpa),
        mysql_real_escape_string($data->isGraduate));


    $result = getDBResultAffected($dbQuery);
    echo json_encode($result);
}

function fetchTutorSchedule($tutorId)
{

    if (!$tutorId == getCurrentUserId()) {
        error("Isn't your GTID is " . getCurrentUserId());
    }

    // TODO: fix this query
    $dbQuery = sprintf("SELECT Number
                        FROM tb_Course
                        WHERE School = '%s'
                        ORDER BY Number;",
        mysql_real_escape_string($tutorId));

    $result = getDBResultsArray($dbQuery);
    echo json_encode($result);

}

function isTutoredThisSemBy($data)
{
    // TODO: fix this query
//    $dbQuery = sprintf("SELECT GTID, Password
//                        FROM tb_User
//                        WHERE GTID='%s' AND Password='%s'",
//        mysql_real_escape_string($data->courseNumber),
//        mysql_real_escape_string($data->tutorName),
//        mysql_real_escape_string(getCurrentSemester()));
//
//    $result = getDBResultsArray($dbQuery);

    // TODO: fix this, return true or false
    return true;
}

function isDuplicateEntry($tutorId, $school = null, $number = null)
{

    if ($school = null && $number == null) {
        // TODO: check duplicate entry, if tutor already has a recommendation from a professor
        $dbQuery = sprintf("SELECT GTID, Password
                        FROM tb_User
                        WHERE GTID='%s' AND Password='%s'",
            mysql_real_escape_string(getCurrentUserId()),
            mysql_real_escape_string($tutorId));
    } else {
        // TODO: check duplicate entry, if tutor already has a rate from this student for this course
        $dbQuery = sprintf("SELECT GTID, Password
                        FROM tb_User
                        WHERE GTID='%s' AND Password='%s'",
            mysql_real_escape_string(getCurrentUserId()),
            mysql_real_escape_string($tutorId),
            mysql_real_escape_string($school),
            mysql_real_escape_string($number));
    }

    $result = getDBResultsArray($dbQuery);

    if (!is_null($result)) {
        return true;
    }

    return false;
}


function submitProfEval($data)
{

    if (!isValidId($data->tutorId)) {
        error('This GTID does not exist in database');
    }

    // TODO: check duplicate entry, if tutor already has a recommendation from a professor
    if (isDuplicateEntry($data->tutorId)) {
        // TODO: either throw error like this or update recommendation
        error("You already recommended this student");
    }


    if ($data->tutorId == getCurrentUserId()) {
        error("You don't want to recommend yourself, do you?");
    }

    // record a professor recommendation
    $dbQuery = sprintf("INSERT INTO tb_Recommends (RecTutGTID, RecProfGTID, RecDesc, RecNum)
	                    VALUES ('%s', '%s', '%s', '%d');",
        mysql_real_escape_string($data->tutorId),
        mysql_real_escape_string(getCurrentUserId()),
        mysql_real_escape_string($data->descEval),
        mysql_real_escape_string($data->numEval));


    $result = getDBResultAffected($dbQuery);
    echo json_encode($result);
}

function submitStudentEval($data)
{

    // check if student is tutored by this tutor this semester
    if (!isTutoredThisSemBy($data)) {
        error("You didn't hire this tutor this semester!");
    }

    if (isDuplicateEntry($data->tutorId, $data->courseSchool, $data->courseNumber)) {
        // TODO: either throw error like this or update rating
        error("You already recommended this student");
    }

    // record a student evaluation in database
    $dbQuery = sprintf("INSERT INTO tb_Rates
                        VALUES('%s', '%s', '%s', '%s', '%s', '%d', '%s')",
        mysql_real_escape_string(getCurrentUserId()),
        mysql_real_escape_string($data->tutorId),
        mysql_real_escape_string($data->courseSchool),
        mysql_real_escape_string($data->courseNumber),
        mysql_real_escape_string($data->descEval),
        mysql_real_escape_string($data->numEval),
        mysql_real_escape_string(getCurrentSemester()));


    $result = getDBResultAffected($dbQuery);
    echo json_encode($result);
}

function isValidId($gtid)
{
    $dbQuery = sprintf("SELECT GTID
                        FROM tb_User
                        WHERE GTID='%s';",
        mysql_real_escape_string($gtid));

    $result = getDBResultsArray($dbQuery);

    if (is_null($result)) {
        return false;
    } else {
        return true;
    }
}

function getTutorGTIDByName($name)
{
    list($firstName, $lastName) = explode(' ', $name);

    $dbQuery = sprintf("SELECT GTID
                        FROM tb_User
                        JOIN tb_Tutor ON GTID = TutGTID
                        WHERE Fname='%s' AND Lname='%s'",
        mysql_real_escape_string($firstName),
        mysql_real_escape_string($lastName));

    $result = getDBResultRecord($dbQuery);

    return $result['GTID'];

}

function fetchTutorNameListByCourse($data)
{

    $dbQuery = sprintf("SELECT GTID, Fname, Lname
	                    FROM tb_User
	                    JOIN tb_Teaches ON tb_User.GTID = tb_Teaches.TeachTutGTID
	                    WHERE tb_Teaches.TeachSchool = '%s'
                        AND tb_Teaches.TeachNumber = '%s';",
        mysql_real_escape_string($data->school),
        mysql_real_escape_string($data->number));

    $result = getDBResultsArray($dbQuery);

    echo json_encode($result);

}

function fetchCourseSchoolList()
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
        error("NO ID");
    }
}

function getCurrentUserType()
{
    if (isset($_SESSION['user_type'])) {
        return $_SESSION['user_type'];
    } else {
        error("NO USE TYPE");
    }
}

function getCurrentSemester()
{
    //TODO: maybe returning semester based on the month
    return "Summer";
}

function fetchAdminSummary1($data)
{

    $sems = $data->semesters;
    for ($i = 0; $i < count($sems); ++$i) {
        $sems[$i] = "'" . $sems[$i] . "'";
    }

    $semsString = implode(", ", $sems);

    $dbQuery = sprintf("SELECT HireSemester, CONCAT(HireSchool,' ',HireNumber) as CourseName,
                            COUNT(DISTINCT HireTutGTID) as NumTutors,
                            COUNT(DISTINCT HireStudGTID) as NumStudents
                            FROM tb_Hires
                        WHERE HireSemester IN ($semsString)
                        GROUP BY HireSemester, CourseName
                        ORDER BY HireSemester, CourseName;");

    $result = getDBResultsArray($dbQuery);
    echo json_encode($result);
}

function fetchAdminSummary2($data)
{

    $sems = $data->semesters;
    for ($i = 0; $i < count($sems); ++$i) {
        $sems[$i] = "'" . $sems[$i] . "'";
    }

    $semsString = implode(", ", $sems);

    // TODO: fix this query!
    $dbQuery = sprintf("SELECT HireSemester, CONCAT(HireSchool,' ',HireNumber) as CourseName,
                            COUNT(DISTINCT HireTutGTID) as NumTutors,
                            COUNT(DISTINCT HireStudGTID) as NumStudents
                            FROM tb_Hires
                        WHERE HireSemester IN ($semsString)
                        GROUP BY HireSemester, CourseName
                        ORDER BY HireSemester, CourseName;");

    $result = getDBResultsArray($dbQuery);
    echo json_encode($result);
}

function error($message)
{
    // TODO: fix this error function, die?
    throw new Exception($message);
}

?>
