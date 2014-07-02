CREATE TABLE SQL Statements

CREATE TABLE tb_User (
	GTID VARCHAR(30) PRIMARY KEY,
	Password VARCHAR(30) NOT NULL,
	Fname VARCHAR(50) NOT NULL,
	Lname VARCHAR(50) NOT NULL,
	Email VARCHAR(50) NOT NULL,
	PhoneNumber VARCHAR(10) NOT NULL
)

CREATE TABLE tb_Administrator (
	AdminGTID CHAR(9) PRIMARY KEY,
	FOREIGN KEY AdminGTID REFERENCES tb_User(GTID)
)

CREATE TABLE tb_Professor (
	ProfGTID CHAR(9) PRIMARY KEY,
	FOREIGN KEY ProfGTID REFERENCES tb_User(GTID)
)

CREATE TABLE tb_Student (
	StudGTID CHAR(9) PRIMARY KEY,
	FOREIGN KEY StudGTID REFERENCES tb_User(GTID)
)

CREATE TABLE tb_Tutor (
	TutGTID CHAR(9) PRIMARY KEY,
	IsGraduate BOOLEAN NOT NULL,
	GPA DECIMAL(1,2) NOT NULL,
	FOREIGN KEY TutGTID REFERENCES tb_User(GTID)
)

CREATE TABLE tb_Course (
	School VARCHAR(4) NOT NULL,
	Number CHAR(4) NOT NULL,
	PRIMARY KEY (School, Number)
)

CREATE TABLE tb_Teaches (
	TeachTutGTID CHAR(9) NOT NULL,
	TeachSchool VARCHAR(4) NOT NULL,
	Teach Number CHAR(4) NOT NULL,
	GTA BOOLEAN NOT NULL,
	PRIMARY KEY (TeachTutGTID, TeachSchool, TeachNumber),
	FOREIGN KEY TeachTutGTID REFERENCES tb_Tutor(TutGTID),
	FOREIGN KEY TeachSchool REFERENCES tb_Course(School),
	FOREIGN KEY TeachNumber REFERENCES tb_Course(Number)
)

CREATE TABLE tb_Recommends (
	RecTutGTID CHAR(9) NOT NULL,
	RecProfGTID CHAR(9) NOT NULL,
	RescDescEval VARCHAR(4000) NOT NULL,
	RescNumEval CHAR(1) NOT NULL,
	PRIMARY KEY (RecTutGTID, RecProfGTID),
	FOREIGN KEY RecTutGTID REFERENCES tb_Tutor(TutGTID),
	FOREIGN KEY RecProfGTID REFERENCES tb_Professor(ProfGTID)
)

CREATE TABLE tb_Rates (
	RateUndGTID CHAR(9) NOT NULL,
	RateTutGTID CHAR(9) NOT NULL,
	RateSchool VARCHAR(4) NOT NULL,
	RateNumber CHAR(4) NOT NULL,
	RateDescEval VARCHAR(4000) NOT NULL,
	RateNumEval CHAR(1) NOT NULL,
	Semester VARCHAR(10) NOT NULL,
	PRIMARY KEY (RateUndGTID, RateTutGTID, RateSchool, RateNumber ),
	FOREIGN KEY RateUndGTID REFERENCES tb_Student(StudGTID),
	FOREIGN KEY RateTutGTID REFERENCES tb_Tutor(TutGTID),
	FOREIGN KEY RateSchool REFERENCES tb_Course(School),
	FOREIGN KEY RateNumber REFERENCES tb_Course(Number)
)

CREATE TABLE tb_TutorTimeSlots (
	Time INT NOT NULL,
	TutSemester VARCHAR(10) NOT NULL,
	Weekday VARCHAR(2) NOT NULL,
	TutTimeGTID CHAR(9) NOT NULL,
	IsHired BOOLEAN NOT NULL,
	PRIMARY KEY (Time, Semester, Weekday, TutTimeGTID),
	FOREIGN KEY TutTimeGTID REFERENCES tb_Tutor(TutGTID)
)

CREATE TABLE tb_Hires (
	HireUndGTID CHAR(9) NOT NULL,
	HireSchool VARCHAR(4) NOT NULL,
	HireNumber CHAR(4) NOT NULL,
	HireTime INT NOT NULL,
	HireSemester VARCHAR(10) NOT NULL,
	HireWeekday VARCHAR(2) NOT NULL,
	PRIMARY KEY (HireUndGTID, HireSchool, HireNumber,HireTime, HireSemester, HireWeekday),
	FOREIGN KEY HireUndGTID REFERENCES tb_Student(StudGTID),
	FOREIGN KEY HireSchool REFERENCES tb_Course(School),
	FOREIGN KEY HireTime REFERENCES tb_TutorTimeSlots(Time),
	FOREIGN KEY HireSemester REFERENCES tb_TutorTimeSlots(TutSemester),
	FOREIGN KEY HireWeekday REFERENCES tb_TutorTimeSlots(Weekday)
)
































=================================================================
==== Login Task:

global $currentUserGTID = read("GTID");
$password = read("Password");

// look up user credential in the database
$result = "SELECT Password
  FROM tb_user
  WHERE GTID == $gtid"

// check to see if user input correct password
if ($result != null && result['Password'] == $password) {
  // proceed to main menu screen
} else {
  error("incorrect gtid/password");
  // return to login form
}



=================================================================
==== Student Search Tutors Screen, Show Available Tutor Task:

// show dropdown list of school
SELECT School
  FROM tb_Course
  ORDER BY School;

// read selected School
$courseSchoolSelected = read(“School”)

// show list of a course number based on selected school
SELECT Number
  FROM tb_Course
  WHERE School == $courseSchoolSelected
  ORDER BY Number;

// read selected course Number
$courseNumberSelected = read(“Number”)


// show list of availability based on selected course
SELECT Weekday, Time
FROM tb_Teaches
JOIN tb_TutorTimeSlots ON tb_TutorTimesSlots.TutTimeGTID = tb_Teaches.TeachTutGTID
ORDER BY Weekday, Time


// read selected set of days and times into 2 arrays
$dayTimeSelected[] = [read(“Day”), read(“Time”)];


// show list of available tutors based on selected day and time
SELECT Fname, Lname, Email, AVG(RecNumEval), COUNT(RecNumEval), AVG(RateNumEval), COUNT(RateNumEval)
FROM tb_TutorTimeSlots, (tb_User JOIN tb_Rates ON tb_User.GTID = Rates.RateTutGTID) JOIN tb_Recommends ON tb_User.GTID = tb_Recommends.RecTutGTID
WHERE	(tb_TutorTimeSlot.Weekday, tb_TutorTimeSlots.Time IN $dayTimeSelected[] AND tb_TutorTimeSlots.TutSemester = getCurrentSemester() AND tb_TutorTimeSlots.TutTimeGTID = tb_User.GTID AND tb_Rates.RateSchool = $courseSchoolSelected AND tb_Rates.RateNumber = $courseNumberSelected)
GROUP BY Email

=================================================================
==== Schedule A Tutor Screen, Submit Request Task:


// show list of available tutors with their information
// and time slots (which are not hired by other students)
SELECT GTID, Fname, Lname, Email, Weekday, Time
	FROM tb_User
	JOIN tb_Teaches
ON tb_User.GTID == tb_Teaches.TeachTutGTID
JOIN tb_TutorTimeSlots
	ON tb_Teaches.TeachTutGTID == tb_TutorTimeSlots.TutTimeGTID
	WHERE tb_Teaches.TeachSchool == $courseSchoolSelected
		AND tb_Teaches.TeachNumber == $courseNumberSelected
		AND tb_TutorTimeSlots.IsHired == false

// note that, we retrieve Tutor GTID but we don’t show it

// read selected tutor and desired time slot
$tutorFirstName = read("FirstName");
$tutorLastName = read("LastName");
$tutorEmail = read("Email");
$tutorGTID = corresponding GTID retrieved from previous query (not shown on UI)
$daySelected = read(“Day”);
$timeSelected = read(“Time”);

// insert a row into HIRES table, record an appointment
INSERT INTO tb_Hires (HireUndGTID, HireSchool, HireNumber, Weekday, Time, Semester)
	VALUES ($tutorGTID, $courseSchoolSelected, $courseNumberSelected, $daySelected, $timeSelected, getCurrentSemester())

// update availability status of tutor in tb_TutorTimeSlots
UPDATE tb_TutorTimeSlots
	SET IsHired = true
	WHERE TutTimeGTID == $tutorGTID
		AND Weekday == $daySelected
		AND Time == $timeSelected
		AND Semester ==  getCurrentSemester()






=================================================================
==== Tutor Evaluation by Student, Rate Tutor Task:

// show dropdown list of school
SELECT School
  FROM tb_Course
  ORDER BY School;

// read selected School from
$courseSchoolSelected = read(“School”)

// show dropdown list of a course number based on selected school
SELECT Number
  FROM tb_Course
  WHERE School == $courseSchoolSelected
  ORDER BY Number;

// read selected course Number
$courseNumberSelected = read(“Number”)


// show dropdown list of names of tutors who teach the course
// tutor GTID is not shown in UI, but correspond to tutor name
SELECT GTID, Fname, Lname
	FROM tb_User
	JOIN tb_Teaches ON tb_User.GTID == tb_Teaches.TeachTutGTID
	WHERE tb_Teaches.TeachSchool == $courseSchoolSelected
		AND tb_Teaches.TeachNumber == $courseNumberSelected

// when user select a tutor name, program store tutor’s GTID instead
$tutorGTID = read("Tutor Name"); // retrieve GTID
$descriptiveEval = read("Descriptive Evaluation");
$numericEval = read("Numeric Evaluation");



// record a student evaluation in database
INSERT INTO tb_Rates (RateUndGTID, RateTutGTID, RateSchool, RateNumber, RateDesEval, RateNumEval, Semester)
	VALUES ($currentUserGTID, $tutorGTID, $courseSchoolSelected, $courseNumberSelected, $descriptiveEval, $numericEval, getCurrentSemester())





=================================================================
==== Professor Recommendation Screen, Summit Professor Recommendation:

$studentGTID = read("Student GTID");

// do a check to see if this student exists in database
$result = “SELECT GTID FROM tb_User WHERE GTID == $studentGTID”

if ($result == null)
	// error(“wrong student GTID / student is not in record”)

$descriptiveEval = read("Descriptive Evaluation");
$numericEval = read("Numeric Evaluation");

// record a recommendation from professor
INSERT INTO tb_Recommends (RecTutGTID, RecProfGTID, RecDescEval, RecNumEval)
	VALUES ($studentGTID, $currentUserGTID, $descriptiveEval, $numericEval)





=================================================================
==== Tutor Submit Application Task:

// collect basic student information
$gatechID = read("Georgia Tech ID");
$firstName = read("First Name");
$lastName = read("Last Name");
$email = read("Email");
$phone = read("Telephone");
$gpa = read("GPA");
$isGraduate = read("Graduate");

// show list of courses for tutoring
// allow prospective tutor to add multiple courses
// that he/she wants to teach
SELECT School, Number
  FROM tb_Course

// read course information and availability information
// each element in courses: {school, number, isGTA}
$courses[] = read("Courses for Tutoring")

// each element in avaiSlots: {day, [time, time, time]}
$avaiSlots[] = read ("Available Days/Times");

// update student basic information
UPDATE tb_User
	SET Fname = $firstName, Lname = $lastName,
Email = $email, PhoneNumber = $phone,
IsGraduate = $isGraduate
	WHERE GTID == $gatechID

// record a new tutor
INSERT INTO tb_Tutor (TutGTID, GPA)
	VALUES ($gatechID, $gpa)

// record the courses that a tutor teaches
INSERT INTO tb_Teaches (TeachTutGTID, TeachSchool, TeachNumber, GTA)
	VALUES ($gatechID, $courses[0].school, $courses[0].number, $courses[0].isGTA),
		($gatechId, $courses[1].school, $courses[1].number, $courses[1].isGTA),
		...
		...






// record tutor’s available time slots
INSERT INTO tb_TutorTimeSlots (TutTimeGTID, Weekday, Time, TutSemester)
	VALUES ($gatechID, $avaiSlots[0].day, $avaiSlots[0].time[0], getCurrentSemester()),
		$gatechID, $avaiSlots[0].day, $avaiSlots[0].time[1], getCurrentSemester()),
		$gatechID, $avaiSlots[0].day, $avaiSlots[0].time[2], getCurrentSemester()),
		$gatechID, $avaiSlots[1].day, $avaiSlots[1].time[0], getCurrentSemester()),
		...
		...





=================================================================
==== Find Tutor Schedule Task:


// read tutor GTID
$tutorGTID = read("Tutor GTID")


// show tutor schedule
SELECT Weekday, Time, Fname, Lname, Email, HireSchool, HireNumber
  FROM tb_User
JOIN tb_Hires
WHERE HireTutGTID == $tutorGTID;




=================================================================
==== Admin Show Report #1 Task:

// read selected semesters
$semesters[] = read("Academic Year 2014");

SELECT School + ‘ ‘ + Number, TutSemester, COUNT(HireUndGTID), Count(TutTimeGTID)
AS CourseName, Semester, COUNT(Student), COUNT(Tutor)
FROM (tb_Hires JOIN tb_TutorTimeSlots ON tb_Hires.Time = tb_TutorTimeSlots.Time AND tb_Hires.Weekday = tb_TutorTimeSlots.Weekday AND tb_Hires.HireSemester = tb_TutorTimeSlots.TutSemester)
WHERE HireSemester IN $semesters[]
ORDER BY CourseName
GROUP BY CourseName





=================================================================
==== Admin Show Report #2 Task:

// read selected semesters
$semesters[] = read("Academic Year 2014");


// retrieve list of GTA tutors who have ratings
SELECT RateSchool, RateNumber, Semester, RateNumEval
FROM tb_Rates
	JOIN tb_Teaches ON RateTutGTID == TeachTutGTID
			AND RateSchool == TeachSchool
			AND RateNumber == TeachNumber
	WHERE GTA == true
GROUP BY RateSchool, RateNumber;

// using the data retrieved from query above
// function to group and calculate average rating to GTA tutors
// for a specific course
for every course in the list
	groupAndCalAvgRatingBySemesters($semesters[])
	calAvgRatingByCourse()



// do a similar data retrieval for non TA
// retrieve list of GTA tutors who have ratings
SELECT RateSchool, RateNumber, Semester, RateNumEval
FROM tb_Rates
	JOIN tb_Teaches ON RateTutGTID == TeachTutGTID
			AND RateSchool == TeachSchool
			AND RateNumber == TeachNumber
	WHERE GTA == false
GROUP BY RateSchool, RateNumber;

// using the data retrieved from query above
// function to group and calculate average rating to GTA tutors
// for a specific course
for every course in the list
	groupAndCalAvgRatingBySemesters($semesters[])
calAvgRatingByCourse()
