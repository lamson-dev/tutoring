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
            fetchTutorSchedule($data);
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
        case "fetchAdminSummary2":
            fetchAdminSummary2($data);
            break;
        case "getCurrentUserId":
            getCurrentUserId();
            break;
        case "getCurrentUserIdString":
            getCurrentUserIdString();
            break;
        case "getCurrentUserType":
            getCurrentUserType();
            break;
        case "getUserNameById":
            getUserNameById($id);
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
        // died('FAILED to login');
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
                                AND TeachTutGTID IN (SELECT RecTutGTID FROM tb_Recommends)
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

        //echo $dbQuery;
        //return;

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

    // echo json_encode($tutors);
    // return;

    // $tutorIds = array_unique($tutorIds);

    if (count($tutorIds) < 1) {
        echo ''; // no tutors available
        return;
    }

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

        foreach ($avgRecResult as $row) {

            $id = $row["RecTutGTID"];
            foreach ($tutors as $tutor) {
                if (strcmp($id, $tutor->gtid) == 0) {
                    $tutor->recAvg = $row["AVG(RecNum)"];
                    $tutor->recCount = $row["COUNT(RecNum)"];
                }
            }
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
            $id = $row["RateTutGTID"];
            foreach ($tutors as $tutor) {
                if (strcmp($id, $tutor->gtid) == 0) {
                    $tutor->rateAvg = $row["AVG(RateNum)"];
                    $tutor->rateCount = $row["COUNT(RateNum)"];
                }
            }
        }
    }

    echo json_encode($tutors);
}

function scheduleSelectedTutor($data) {

    $studentId = getCurrentUserId();
    $tutorId = getTutorGTIDByName($data->tutorName);
    $courseSchool = $data->school;
    $courseNumber = $data->number;
    $weekday = $data->weekday;
    $time = $data->time;
    $semester = getCurrentSemester();

	// check to see if student already has filled semester course quota
	$dbQuery1 = sprintf("SELECT HireStudGTID
						FROM tb_Hires
						WHERE HireStudGTID = '%s'
						AND HireSchool = '%s'
						AND HireNumber = '%s'
						AND HireSemester = '%s';",
						mysql_real_escape_string($studentId),
						mysql_real_escape_string($courseSchool),
						mysql_real_escape_string($courseNumber),
						mysql_real_escape_string(getCurrentSemester()));
						
	echo $dbQuery1;
								
	$result1 = getDBResultsArray($dbQuery1);

    if ($result1 != null) {
		error("You have already scheduled a tutor for this course this semester.");
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

    if (strcmp($data->tutorId, getCurrentUserId()) != 0) {
        error("Isn't your GTID is " . getCurrentUserId());
    }

    $tutorId = getCurrentUserId();
    $semester = getCurrentSemester();

    $isGraduate = ($data->isGraduate) ? 'true' : 'false';

    // update tutor basic information in tb_User
    $dbQuery = sprintf("UPDATE tb_User
                        SET Fname = '%s', Lname = '%s',
                            Email = '%s', PhoneNumber = '%s'
                        WHERE GTID = %s;",
        mysql_real_escape_string($data->firstName),
        mysql_real_escape_string($data->lastName),
        mysql_real_escape_string($data->email),
        mysql_real_escape_string($data->phone),
        mysql_real_escape_string($tutorId));

    $result = getDBResultAffected($dbQuery);

    // update tutor information in tb_Tutor
    $dbQuery = sprintf("UPDATE tb_Tutor
                        SET IsGraduate = %s,
                            GPA = '%s'
                        WHERE TutGTID = '%s'",
        mysql_real_escape_string($isGraduate),
        mysql_real_escape_string($data->gpa),
        mysql_real_escape_string($tutorId));


    $result = getDBResultAffected($dbQuery);


    // update tutor courses that he teaches
    $courses = $data->courses;
    $values = '';
    foreach ($courses as $course) {
        list($school, $number, $gta) = explode(' ', $course);

        $values .= "('$tutorId','$school', '$number', $gta),";
    }


    // insert tutor teaches courses
    $values = rtrim($values, ",");
    $dbQueryTeaches = "INSERT INTO tb_Teaches (TeachTutGTID, TeachSchool, TeachNumber, GTA)
                       VALUES " . $values . ";";

    $resultTeaches = getDBResultAffected($dbQueryTeaches);


    $values = '';
    $avai = $data->avai;
    foreach ($avai as $day) {

        $weekday = $day->weekday;
        $times = $day->times;

        if ($times == null) {
            continue;
        }

        foreach ($times as $time) {
                $values .= "('$time','$semester','$weekday','$tutorId'),";
        }
    }

    // insert tutor available slots
    $values = rtrim($values, ",");
    $dbQuerySlot = "INSERT INTO tb_Slot (Time, Semester, Weekday, SlotTutGTID)
	               VALUES " . $values . ";";

    $resultSlot = getDBResultAffected($dbQuerySlot);

    echo json_encode($resultTeaches) . "   $$$   " . json_encode($resultSlot);
}

function fetchTutorSchedule($tutorId)
{

    // $tutorId = getCurrentUserId();

    if (strcmp($tutorId, getCurrentUserId()) != 0) {
        error("Isn't your GTID is " . getCurrentUserId());
    }


    $dbQuery = sprintf("SELECT HireWeekday, HireTime, Fname, Lname, Email, HireSchool, HireNumber
                        FROM tb_User
	                       JOIN tb_Hires ON HireStudGTID = GTID
                               AND HireTutGTID = '%s'
                               AND HireSemester = '%s'
	                    ORDER BY HireWeekday, HireTime;",
                mysql_real_escape_string($tutorId),
                mysql_real_escape_string(getCurrentSemester()));

    $result = getDBResultsArray($dbQuery);
    echo json_encode($result);

}

function isTutoredThisSemBy($data)
{
	$dbQuery = sprintf("SELECT *
						FROM tb_Hires
						WHERE HireTutGTID = '%s'
						AND HireStudGTID = '%s'
						AND HireSemester = '%s'
						AND HireSchool = '%s'
						AND HireNumber = '%s'",
						mysql_real_escape_string($data->tutorId),
					   mysql_real_escape_string(getCurrentUserId()),
					   mysql_real_escape_string(getCurrentSemester()),
					   mysql_real_escape_string($data->courseSchool),
					   mysql_real_escape_string($data->courseNumber));

	$result = getDBResultsArray($dbQuery);

	if(!is_null(($result)))
	{
		return true;
	}
	else
	{
		return false;
	}
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

    if (!isValidTutorId($data->tutorId)) {
        error('This GTID does not exist in database');
    }

    // TODO: check duplicate entry, if tutor already has a recommendation from a professor
    if (isDuplicateEntry($data->tutorId)) {
        // TODO: either throw error like this or update recommendation
        error("You already recommended this student");
    }


	// check if professor recommendation already exists then overwrites
	$dbQuery1 = sprintf("SELECT RecTutGTID FROM tb_Recommends WHERE RecTutGTID = '%s'
						AND RecProfGTID = '%s';",
						mysql_real_escape_string($data->tutorId),
						mysql_real_escape_string(getCurrentUserId()));

	$result1 = getDBResultsArray($dbQuery1);
	echo json_encode($result1);

	if(json_encode($result1) != null)
	{
		$dbQuery2 = sprintf("DELETE FROM tb_Recommends
							WHERE RecProfGTID = '%s'
							AND RecTutGTID = '%s';",
							mysql_real_escape_string(getCurrentUserId()),
							mysql_real_escape_string($data->tutorId));
	}

	$result2 = getDBResultAffected($dbQuery2);
    echo json_encode($result2);

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
        alert("You didn't hire this tutor this semester!");
        return;
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

function isValidTutorId($gtid)
{
    $dbQuery = sprintf("SELECT TutGTID
                        FROM tb_Tutor
                        WHERE TutGTID='%s';",
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

function getCurrentUserIdString()
{
    if (isset($_SESSION['gtid'])) {
        echo $_SESSION['gtid'];
    } else {
        error("NO ID");
    }
}

function getUserNameById($id) {

    if ($id==null) {
        $id = getCurrentUserId();
    }

    $dbQuery = sprintf("SELECT Fname, Lname
                        FROM tb_User
                        WHERE GTID = '%s';",
                mysql_real_escape_string($id));

    $result = getDBResultsArray($dbQuery);

    echo $result[0]["Fname"] . " " . $result[0]["Lname"];
    // echo json_encode($result);
}

function getCurrentUserType()
{
    if (isset($_SESSION['user_type'])) {
        echo $_SESSION['user_type'];
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

    $dbQueryGTA = sprintf("SELECT CONCAT(RateSchool,' ',RateNumber) as CourseName,
                                RateSemester,
                                COUNT(RateTutGTID) as NumGTA, AVG(RateNum) as AvgRating
                    FROM tb_Rates JOIN tb_Teaches ON
                    RateTutGTID = TeachTutGTID
                        AND RateSchool = TeachSchool
                        AND RateNumber = TeachNumber
                        AND RateSemester IN ($semsString)
                    WHERE GTA = 1 AND RateTutGTID IN (SELECT TutGTID FROM tb_Tutor WHERE IsGraduate = 1)
                    GROUP BY RateSchool, RateNumber, RateSemester
                    ORDER BY RateSchool, RateNumber, RateSemester;");

    $resultGTA = getDBResultsArray($dbQueryGTA);

    $dbQuery = sprintf("SELECT CONCAT(RateSchool,' ',RateNumber) as CourseName,
                                RateSemester,
                                COUNT(RateTutGTID) as NumGTA, AVG(RateNum) as AvgRating
                    FROM tb_Rates JOIN tb_Teaches ON
                    RateTutGTID = TeachTutGTID
                        AND RateSchool = TeachSchool
                        AND RateNumber = TeachNumber
                        AND RateSemester IN ($semsString)
                    WHERE GTA = 0 AND RateTutGTID IN (SELECT TutGTID FROM tb_Tutor WHERE IsGraduate = 1)
                    GROUP BY RateSchool, RateNumber, RateSemester
                    ORDER BY RateSchool, RateNumber, RateSemester;");

    $result = getDBResultsArray($dbQuery);

    echo json_encode($resultGTA) . "$$$" . json_encode($result);
}

function error($message)
{
    // TODO: fix this error function, die?
    throw new Exception($message);
}

?>
