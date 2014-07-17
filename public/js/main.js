var DEBUG = true;

var courseSchoolSelected = "";
var courseNumberSelected = "";

$(document).ready(function () {

    // hacked, put this function in php files to run it on page loaded
    // fetchCourseSchoolList();

    $("#student_search_course .school_list").change(function () {
        courseSchoolSelected = $(this).val();

        $('#student_search_course .number_list').find('option').remove().end();

        fetchCourseNumberList(courseSchoolSelected, "#student_search_course");

    });

    $("#student_rate_course .school_list").change(function () {
        courseSchoolSelected = $(this).val();

        $('#student_rate_course .number_list').find('option').remove().end();
        $('#rate_tutor_name_list').find('option').remove().end();

        fetchCourseNumberList(courseSchoolSelected, "#student_rate_course");
    });

    $("#student_rate_course .number_list").change(function () {
        courseNumberSelected = $(this).val();

        $('#rate_tutor_name_list').find('option').remove().end();

        fetchTutorNameListByCourse(courseSchoolSelected, courseNumberSelected);
    });

    $("#tutor_course .school_list").change(function () {
        courseSchoolSelected = $(this).val();

        $('#tutor_course .number_list').find('option').remove().end();

        fetchCourseNumberList(courseSchoolSelected, "#tutor_course");

    });

    $("#btn_login").click(login);
    $("#btn_submit_student_eval").click(submitStudentEval);
    $("#btn_submit_prof_eval").click(submitProfEval);
    $("#btn_show_avai_tutor").click(showAvaiTutor);
    $("#btn_submit_app").click(submitTutorApp);
    $("#btn_show_summary1").click(fetchAdminSummary1);


    $("#btn_schedule_tutor").click(function () {

    });

    $("#btn_cancel").click(function () {
        $("#avai_tutor").hide();
    });


    $("#btn_tutor_apply").click(function () {
        $("#in_app_gtid").html(getCurrentUserId());
    });

    $("#btn_add_course").click(addMoreCourse);

    $(".event").click(toggleTimeSlot);

});

function addMoreCourse() {
    var tutorCourse = $("#tutor_course");

    var div = divTag.clone();


    div.append(tutorCourse.html());

    div.attr("id", uniqueId());
    div.attr("class", "row");

    div.find(".number_list").find('option').remove().end();
    div.find(".school_list").change(function () {

        fetchCourseNumberList($(this).val(), "#" + div.attr("id"));

    });

    $("#course_list").append(div);
}

function fetchTutorNameListByCourse(school, number) {

    var data = {};
    data.school = school;
    data.number = number;

    makeCall("fetchTutorNameListByCourse", data)
        .success(function (response, error) {

            var json = JSON.parse(response);

            var nameList = $("#rate_tutor_name_list");

            for (var key in json) {

                if (!json.hasOwnProperty(key)) {
                    continue;
                }

                var tutorName = json[key].Fname + " " + json[key].Lname;
                var tutorId = json[key].GTID;

                var option = optionTag.clone();
                option.attr("value", tutorId);
                option.html(tutorName);
                nameList.append(option);

            }

            nameList.append("<option selected disabled hidden value=''>- Select Tutor Name -</option>");
        });
}

function fetchCourseSchoolList(courseSelectorId) {
    makeCall("fetchCourseSchoolList")
        .success(function (response, error) {

            var json = JSON.parse(response);

            var schoolList = $(courseSelectorId + " .school_list");

            schoolList.append("<option selected disabled hidden value=''>- Department -</option>");

            for (var key in json) {

                if (!json.hasOwnProperty(key)) {
                    continue;
                }

                var school = json[key].School;

                var option = optionTag.clone();
                option.attr("value", school);
                option.html(school);
                schoolList.append(option);

            }

        });
}


function fetchCourseNumberList(school, courseSelectorId) {
    var data = {};
    data.school = school;
    makeCall("fetchCourseNumberList", data)
        .success(function (response, error) {
            var json = JSON.parse(response);

            var numberList = $(courseSelectorId + " .number_list");

            numberList.append("<option selected disabled hidden value=''>- Number -</option>");

            for (var key in json) {

                if (!json.hasOwnProperty(key)) {
                    continue;
                }

                var number = json[key].Number;

                var option = optionTag.clone();
                option.attr("value", number);
                option.html(number);
                numberList.append(option);

            }

        });
}

function fetchAdminSummary1() {
    var semFall = $("#sem_fall").is(":checked");
    var semSpring = $("#sem_spring").is(":checked");
    var semSummer = $("#sem_summer").is(":checked");

    var data = {};
    data.semFall = semFall;
    data.semSpring = semSpring;
    data.semSummer = semSummer;

    makeCall("fetchAdminSummary1", data)
        .success(function (response, error) {

            //TODO: populate summary table
            console.log(JSON.stringify(response));

//   NEED TO CALCULATE TOTAL FOR TABLE FROM DATA RETURNED

        }).error(function () {
            //TODO: do something here
        });
}

function showAvaiTutor() {
    var courseShool = $("#search_school_list").val();
    var courseNumber = $("#search_number_list").val();

    var data = {};
    data.courseSchool = courseShool;
    data.courseNumber = courseNumber;
    data.studentAvai = getAvaiTimeDataFromCal("#student_calendar");

    makeCall("showAvaiTutor", data)
        .success(function (response, error) {

            //TODO: populate available tutors to UI
            console.log(JSON.stringify(response));

//            $("#avai_tutor").show();
        }).error(function () {
            //TODO: do something here
        });
}

function submitTutorApp() {

    var tutorId = $("#in_app_gtid").val();
    var firstName = $("#in_app_fname").val();
    var lastName = $("#in_app_lname").val();
    var email = $("#in_app_email").val();
    var phone = $("#in_app_phone").val();
    var gpa = $("#in_app_gpa").val();
    var studentType = $('input[name="student_type"]:checked').val();

    var courses = [];

    if (tutorId == '' || firstName == '' || lastName == ''
        || email == '' || phone == '' || gpa == ''
        || studentType == null) {
        alert("Please fill in all the requirements.");
        return;
    }


    var schools = $("#course_list .school_list");
    var numbers = $("#course_list .number_list");

    var i = 0;
    schools.each(function() {
        courses.push($(this).val());
    });

    numbers.each(function() {
        courses[i] = courses[i] + " " + $(this).val();
//        console.log(courses[i]);
        ++i;
    });


    var data = {};
    data.tutorId = tutorId;
    data.firstName = firstName;
    data.lastName = lastName;
    data.email = email;
    data.phone = phone;
    data.gpa = gpa;
    data.isGraduate = (studentType == 'grad') ? true : false;
    data.courses = courses;
    data.avai = getAvaiTimeDataFromCal("#tutor_calendar");

    console.log(JSON.stringify(data));

    makeCall("submitTutorApp", data)
        .success(function (response, error) {

            alert("Tutor Application Submitted");
            window.location = "/main-menu.php";
        }).error(function (message) {
            error(message);
        });

}

function submitStudentEval() {


    var courseShool = $("#rate_course_selector select").val();
    var courseNumber = $("#rate_course_selector select").val();
    var tutorId = $("#rate_tutor_name_list").val();
    var descEval = $("#rate_desc_eval").val();
    var numEval = $('input[name="rating"]:checked').val();

    // TODO: need to validate input here, check if empty
    if (courseShool == null || courseNumber == null) {

    }

    if (tutorId == null) {
//        tutorName.attr("class", "error");
//        $("#in_tutor_name_error").show();

    }

    if (descEval == null) {

    }

    if (numEval == null) {

    }

    var data = {};
    data.courseSchool = courseShool;
    data.courseNumber = courseNumber;
    data.tutorId = tutorId;
    data.descEval = descEval;
    data.numEval = numEval;

    // TODO: check duplicate entry

    makeCall("submitStudentEval", data)
        .success(function (response, error) {

//            $("#in_tutor_name").attr("class", "");
//            $("#in_tutor_name_error").hide();

            //TODO: notify user that rating was submitted before going back to the main menu
            alert("Rated this tutor");
            console.log(response);
            window.location = "/main-menu.php";
        }).error(function () {

            //TODO: do something here

        });

}

function submitProfEval() {

    var tutorId = $("#rec_tutor_gtid").val();
    var descEval = $("#rec_desc_eval").val();
    var numEval = $('input[name="rec_rating"]:checked').val();

    var data = {};
    data.tutorId = tutorId;
    data.descEval = descEval;
    data.numEval = numEval;

    if (tutorId == null || descEval == null || numEval == null) {
        alert("Please fill in all required input forms");
        return;
    }


    // TODO: need to validate tutorID
    // TODO: check duplicate entry

    makeCall("submitProfEval", data)
        .success(function (response, error) {

            alert("Recommended this tutor");
            console.log(response);

            window.location = "/main-menu.php";
        }).error(function (message) {
            alert("Error: " + message);
        });

}


