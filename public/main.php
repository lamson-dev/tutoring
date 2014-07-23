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
        case "logout":
            logout();
            break;
        case "fetchAvaiTutorWithRatingSummary":
            fetchAvaiTutorWithRatingSummary($data);
            break;
        case "fetchAvaiTutorWithTime":
            fetchAvaiTutorWithTime($data);
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

    $dbQuery = sprintf("SELECT GTID, Password, Type
                        FROM tb_User
                        WHERE GTID='%s' AND Password='%s'",
        mysql_real_escape_string($data->gtid),
        mysql_real_escape_string($data->password));

    $result = getDBResultsArray($dbQuery);

    if (is_null($result)) {
        error('FAILED to login');
    } else {
        $_SESSION['gtid'] = $data->gtid;
        $_SESSION['user_type'] = $result[0]["Type"];
    }
}

function logout() {

    if (isset($_SESSION['gtid'])) {
        unset($_SESSION['gtid']);
    }
    if (isset($_SESSION['user_type'])) {
        unset($_SESSION['user_type']);
    }
    // header("Location: index.php");
}

function fetchAvaiTutorWithRatingSummary($data)
{

    class Tutor {
        public $gtid = '';
        public $fname = '';
        public $lname = '';
        public $email = '';
        public $recAvg = '';
        public $recCount = '';
        public $rateAvg = '';
        public $rateCount = '';
    }

    // array of tutors with all information, to be return as json
    $tutors = array();

    // array of tutorIds, to be used in query
    $tutorIds = array();


    $avai = $data->studentAvai;


    // loop through everyday in the week, search for avai tutorIds
    // then store it in $tutorIds
    foreach ($avai as $slot) {
        $day = $slot->weekday;
        $times = $slot->times;

        if ($times == null) {
            continue;
        }

        $timesString = str_replace ("[", "", json_encode($times));
        $timesString = str_replace("]","", $timesString);
        $timesString = str_replace('"',"'", $timesString);

        $dbQuery = sprintf("SELECT GTID, Fname, Lname, Email, Weekday, Time
                            FROM (tb_User JOIN tb_Teaches ON GTID = TeachTutGTID)
                                JOIN tb_Slot ON GTID = SlotTutGTID
                            WHERE TeachSchool = '%s'
                                AND TeachNumber = '%s'
                                AND Semester = '%s'
                                AND Weekday = '%s'
                                AND Time IN ($timesString)
                                AND (TeachTutGTID, Time, Semester, Weekday)
                                NOT IN (SELECT SlotTutGTID, Time, Semester, Weekday
                                        FROM tb_Hires
                                            JOIN tb_Slot ON (SlotTutGTID = HireTutGTID
                                        AND Time = HireTime AND Semester = HireSemester
                                        AND Weekday = HireWeekday))
                            ORDER BY Lname, Weekday, Time;",

                    mysql_real_escape_string($data->courseSchool),
                    mysql_real_escape_string($data->courseNumber),
                    mysql_real_escape_string(getCurrentSemester()),
                    mysql_real_escape_string($day));

        // echo $dbQuery;
        // return;

        $result = getDBResultsArray($dbQuery);

        if ($result != null) {
            foreach ($result as $row) {
                array_push($tutorIds, $row["GTID"]);

                $t = new Tutor();
                $t->gtid = $row["GTID"];
                $t->fname = $row["Fname"];
                $t->lname = $row["Lname"];
                $t->email = $row["Email"];
                $t->weekday = $row["Weekday"];
                $t->time = $row["Time"];
                array_push($tutors, $t);
            }
        }

    }

    echo json_encode($tutors);

    return;
    // var_dump($tutorIds);

    // $tutorIds = array_unique($tutorIds, SORT_REGULAR);

    // var_dump($tutorIds);

    // return;

    $tutorIdsString = str_replace ("[", "", json_encode($tutorIds));
    $tutorIdsString = str_replace("]","", $tutorIdsString);
    $tutorIdsString = str_replace('"',"'", $tutorIdsString);


    // retrieve average recommendations for these tutors
    $dbQuery = sprintf("SELECT RecTutGTID, AVG(RecNum), COUNT(RecNum)
                        FROM tb_Recommends
                        WHERE RecTutGTID IN ($tutorIdsString)
                        GROUP BY RecTutGTID");

    $avgRecResult = getDBResultsArray($dbQuery);

    if ($avgRecResult != null) {
        $i = 0;
        foreach ($avgRecResult as $row) {
            $tutors[$i]->recAvg = $row["AVG(RecNum)"];
            $tutors[$i]->recCount = $row["COUNT(RecNum)"];
            ++$i;
        }
    }


    // retrieve average ratings for these tutors
    $dbQuery = sprintf("SELECT RateTutGTID, AVG(RateNum), COUNT(RateNum)
                        FROM tb_Rates
                        WHERE RateTutGTID IN ($tutorIdsString)
                        GROUP BY RateTutGTID;");

    $avgRateResult = getDBResultsArray($dbQuery);

    if ($avgRateResult != null) {
        $i = 0;
        foreach ($avgRateResult as $row) {
            $tutors[$i]->rateAvg = $row["AVG(RateNum)"];
            $tutors[$i]->rateCount = $row["COUNT(RateNum)"];
            ++$i;
        }
    }

    // var_dump($tutors);
    echo json_encode($tutors);
}

// function fetchAvaiTutorWithTime($data) {
//
//     // array of tutors with all information, to be return as json
//     $tutors = array();
//
//     $tutorIdsString = str_replace ("[", "", json_encode($data));
//     $tutorIdsString = str_replace("]","", $tutorIdsString);
//     $tutorIdsString = str_replace('"',"'", $tutorIdsString);
//
//     $dbQuery = sprintf("SELECT Fname, Lname, Email, Weekday, Time
//                         FROM (tb_User JOIN tb_Teaches ON GTID = TeachTutGTID)
//                             JOIN tb_Slot ON GTID = SlotTutGTID
//                         WHERE GTID IN ($tutorIdsString)
//                             AND (TeachTutGTID, Time, Semester, Weekday) NOT IN
//                                 (SELECT SlotTutGTID, HireTime, HireSemester, HireWeekday
//                                 FROM tb_Hires JOIN tb_Slot ON (SlotTutGTID = HireTutGTID
//                                     AND Time = HireTime AND Semester = HireSemester
//                                     AND Weekday = HireWeekday))
//                         ORDER BY Lname, Weekday, Time;",
//                 mysql_real_escape_string(getCurrentSemester()),
//                 mysql_real_escape_string($data->courseSchool),
//                 mysql_real_escape_string($data->courseNumber),
//                 mysql_real_escape_string(getCurrentSemester()),
//                 mysql_real_escape_string(getCurrentSemester()));
//
//
//     $result = getDBResultsArray($dbQuery);
//     echo json_encode($result);
//
// }

function scheduleSelectedTutor($data) {

    $studentId = getCurrentUserId();
    $tutorId = getTutorGTIDByName($data->tutorName);
    $courseSchool = $data->school;
    $courseNumber = $data->number;
    $weekday = $data->weekday;
    $time = $data->time;
    $semester = getCurrentSemester();


    // check to see if the student already have scheduled a tutor this time
    $dbQuery = sprintf("SELECT EXISTS (SELECT *
                    FROM tb_Hires
                    WHERE HireStudGTID = '%s'
                        AND HireWeekday = '%s'
                        AND HireTime = '%s'
                        AND HireSemester = '%s');",

        mysql_real_escape_string($studentId),
        mysql_real_escape_string($weekday),
        mysql_real_escape_string($time),
        mysql_real_escape_string($semester));

    $result = mysql_query($dbQuery);

    if ($result) {
        error("You already have scheduled a tutor in this time slot.");
    }

    // ============================================================
    // ============================================================

    $dbQuery = sprintf("INSERT INTO tb_Hires
                        VALUES ('%s','%s','%s','%s','%s','%s','%s');",

        mysql_real_escape_string($tutorId),
        mysql_real_escape_string($studentId),
        mysql_real_escape_string($courseSchool),
        mysql_real_escape_string($courseNumber),
        mysql_real_escape_string($time),
        mysql_real_escape_string($semester),
        mysql_real_escape_string($weekday));

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
        echo $_SESSION['gtid'];
        return $_SESSION['gtid'];
    } else {
        error("NO ID");
    }
}

function getCurrentUserType()
{
    if (isset($_SESSION['user_type'])) {
        echo $_SESSION['user_type'];
        return $_SESSION['user_type'];
    } else {
        error("NO USER TYPE");
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

    $dbQuery = sprintf("SELECT CONCAT(HireSchool,' ',HireNumber) as CourseName, HireSemester,
                            COUNT(DISTINCT HireTutGTID) as NumTutors,
                            COUNT(DISTINCT HireStudGTID) as NumStudents
                            FROM tb_Hires
                        WHERE HireSemester IN ($semsString)
                        GROUP BY HireSemester, CourseName
                        ORDER BY CourseName, HireSemester;");

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
    $dbQuery = sprintf("SELECT CONCAT(HireSchool,' ',HireNumber) as CourseName, HireSemester
                            COUNT(DISTINCT HireTutGTID) as NumTutors,
                            COUNT(DISTINCT HireStudGTID) as NumStudents
                            FROM tb_Hires
                        WHERE HireSemester IN ($semsString)
                        GROUP BY HireSemester, CourseName
                        ORDER BY CourseName, HireSemester;");

    $result = getDBResultsArray($dbQuery);
    echo json_encode($result);
}

function error($message)
{
    // TODO: fix this error function, die?
    throw new Exception($message);
}

?>
