var DEBUG = true;

var aTag = $("<a>");
var h3Tag = $("<h3>");
var pTag = $("<p>");
var liTag = $("<li>");
var divTag = $("<div>");
var spanTag = $("<span>");
var imgTag = $("<img>");
var optionTag = $("<option>");

var courseSchoolSelected = "CS";

$(document).ready(function () {

    fetchSchoolList();
    fetchCourseNumberList(courseSchoolSelected);


    $("#search_school_list").change(function () {
        courseSchoolSelected = $("#search_school_list").val();

        $('#search_number_list').find('option').remove().end();

        fetchCourseNumberList(courseSchoolSelected);
    });

    $("#rate_school_list").change(function () {
        courseSchoolSelected = $("#rate_school_list").val();

        $('#rate_number_list').find('option').remove().end();

        fetchCourseNumberList(courseSchoolSelected);
    });


    $("#btn_login").click(login);

    $("#btn_show_avai_tutor").click(showAvaiTutor);

    $("#btn_schedule_tutor").click(function () {

    });

    $("#btn_cancel").click(function () {
        $("#avai_tutor").hide();
    });


    $("#btn_tutor_apply").click(function () {
        $("#in_app_gtid").html(getCurrentUserId());
    });

    $("#btn_submit_app").click(submitTutorApp);

    $("#btn_submit_student_eval").click(rateTutor);

});

function login() {

    var gtid = $("#gtid").val();
    var password = $("#password").val();

    var data = {};
    data.gtid = gtid;
    data.password = password;

    makeCall("login", data)
        .success(function (response, error) {
            window.location = "/main-menu.php";
        }).error(function (message) {
            alert("Error: " + message);
        });

}

function fetchSchoolList() {
    makeCall("fetchSchoolList")
        .success(function (response, error) {

            var json = JSON.parse(response);

            var schoolList = $("#search_school_list");

            var rateSchoolList = $("#rate_school_list");

            for (var key in json) {

                var school = json[key].School;

                var option = optionTag.clone();
                option.attr("value", school);
                option.html(school);
                schoolList.append(option);

                rateSchoolList.append(option.clone());
            }
        });
}


function fetchCourseNumberList(school) {
    var data = {};
    data.school = school;
    makeCall("fetchCourseNumberList", data)
        .success(function (response, error) {
            var json = JSON.parse(response);

            var numberList = $("#search_number_list");

            var rateNumberList = $("#rate_number_list");

            for (var key in json) {

                var number = json[key].Number;

                var option = optionTag.clone();
                option.attr("value", number);
                option.html(number);
                numberList.append(option);

                rateNumberList.append(option.clone());
            }
        });
}

function showAvaiTutor() {
    var courseShool = $("#search_school_list").val();
    var courseNumber = $("#search_number_list").val();

    var data = {};
    data.courseSchool = courseShool;
    data.courseNumber = courseNumber;


    //TODO: get student availabilities and add to data
    data.studentAvai = [];

    makeCall("showAvaiTutor", data)
        .success(function (response, error) {

            //TODO: populate available tutors to UI

            $("#avai_tutor").show();

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
    var isGraduate = null;
    var courses = [];
    var avai = [];

    var data = {};
    data.tutorId = tutorId;
    data.firstName = firstName;
    data.lastName = lastName;
    data.email = email;
    data.phone = phone;
    data.gpa = gpa;
    data.isGraduate = isGraduate;
    data.courses = courses;
    data.avai = avai;

    //TODO: get courses want to teach

    //TODO: get available times


    makeCall("submitTutorApp", data)
        .success(function (response, error) {

            //TODO: notify user that app was submitted before going back to the main menu
            window.location = "/main-menu.php";
        }).error(function () {
            //TODO: do something here
        });

}

function rateTutor() {


    var courseShool = $("#rate_school_list").val();
    var courseNumber = $("#rate_number_list").val();
    var tutorName = $("#in_tutor_name").val();
    var descEval = $("#in_desc_eval").val();
    var numEval = 4;

    // TODO: need to validate input here, check if empty
    if (courseShool == null || courseNumber == null) {

    }

    if (tutorName == null) {
        tutorName.attr("class", "error");
        $("#in_tutor_name_error").show();

    }

    if (descEval == null) {

    }

    if (numEval == null) {

    }

    var data = {};
    data.courseSchool = courseShool;
    data.courseNumber = courseNumber;
    data.tutorName = tutorName;
    data.descEval = descEval;
    data.numEval = numEval;


    // check if this student had this tutor this semester

    makeCall("rateTutor", data)
        .success(function (response, error) {

            $("#in_tutor_name").attr("class", "");
            $("#in_tutor_name_error").hide();

            //TODO: notify user that rating was submitted before going back to the main menu

            console.log(response);
//            window.location = "/main-menu.php";
        }).error(function () {

            //TODO: do something here

        });

}

function getCurrentUserId() {
    makeCall("getCurrentUserId", "")
        .success(function (response, error) {
            alert(response);
            return response;
        });
}

function makeCall(method, data) {
    return $.ajax({
        type: 'POST',
        url: 'main.php',
        data: {
            'action': method,
            'json': JSON.stringify(data)
        }
    });
}
