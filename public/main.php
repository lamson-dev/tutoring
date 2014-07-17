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
        case "submitProfEval":
            submitProfEval($data);
            break;
        case "submitStudentEval":
            submitStudentEval($data);
            break;
        case "submitTutorApp":
            submitTutorApp($data);
            break;
        case "showTutorSchedule":
            showTutorSchedule();
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
    }
}

function login($data)
{

    $dbQuery = sprintf("SELECT GTID, Password
                        FROM tb_User
                        WHERE GTID='%s' AND Password='%s'",
        mysql_real_escape_string($data->gtid),
        mysql_real_escape_string($data->password));

    $result = getDBResultRecord($dbQuery);

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

function showTutorSchedule()
{
    $gtid = getCurrentUserId();

    // TODO: fix this query
    $dbQuery = sprintf("SELECT Number
                        FROM tb_Course
                        WHERE School = '%s'
                        ORDER BY Number;",
        mysql_real_escape_string($gtid));

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

function submitProfEval($data)
{

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
        // TODO: throw some error
        echo false;
        return;
    }

//    $tutorGTID = getTutorGTIDByName($data->tutorName);

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

    if (DEBUG) {
        return "111111111";
    }

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

function fetchAdminSummary1($data)
{
	$value =  null;

	if( $data-> semFall == "true" )
	{
	}
	elseif( $data-> semSpring == "true" )
	{
	}	
	elseif( $data-> semSummer == "true" )
	{
	}	
	elseif( $data-> semFall == "true" && $data-> semSpring == "true" )
	{
	}	
	elseif( $data-> semFall == "true" && $data-> semSummer == "true" )
	{
	}	
	elseif( $data-> semSpring == "true" && $data-> semSummer == "true" )
	{
	}	
	else
	{
		$value = "Fall,"." Spring, "."Summer";
	}
	$value = "Fall";
	$value = $value . "Spring";
	$value = $value . "Summer";

//		$value = "Fall,"." Spring, "."Summer";
    echo json_encode($value);

	$dbQuery = sprintf("SELECT HireSemester, 
			CONCAT(HireSchool,' ',HireNumber) as CourseName, 
			COUNT(DISTINCT HireTutGTID) as NumTutors, 
			COUNT(DISTINCT HireStudGTID) as NumStudents
                        FROM tb_Hires
			WHERE HireSemester IN('%s') 
                        GROUP BY HireSemester, CourseName
                        ORDER BY HireSemester,CourseName;",
        mysql_real_escape_string($value));

    $result = getDBResultsArray($dbQuery);
   // echo json_encode($result);
}

?>
