var courseSchoolSelected = "CS";
var courseNumberSelected = "2200";

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

    // LOGIN
    $("#btn_login").click(login);
    $("#btn_logout").click(logout);

    // STUDENT
    $("#btn_show_avai_tutor").click(fetchAvaiTutorWithRatingSummary);
    // $("#btn_schedule_tutor").click(ScheduleToSelect);
    $("#btn_schedule_selected_tutor").click(scheduleSelectedTutor);
    $("#btn_cancel").click(function () {
        $("#avai_tutor").hide();
    });

    $("#btn_submit_student_eval").click(submitStudentEval);

    // TUTOR
    $("#btn_add_course").click(addMoreCourse);
    $("#btn_submit_app").click(submitTutorApp);
    $("#btn_show_tutor_schedule").click(showTutorSchedule);

    // PROFESSOR
    $("#btn_submit_prof_eval").click(submitProfEval);

    // ADMIN
    $("#btn_show_summary1").click(fetchAdminSummary1);
    $("#btn_show_summary2").click(fetchAdminSummary2);

    // for the timeslot calendar picker
    $(".event").click(toggleTimeSlot);

});

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

    var selected = [];
    $('#summary1_checkboxes input:checked').each(function () {
        selected.push($(this).val());
    });

    if ($.isEmptyObject(selected)) {
        alert("Please select a semester");
        return;
    }

    var data = {};
    data.semesters = selected;

    makeCall("fetchAdminSummary1", data)
        .success(function (response, error) {

            //TODO: populate summary1 table

         var summary1data = JSON.parse(response);

    // if(summary1data.length > 0)
    // {
    //     var num_rows = result.noofteams;
    //     var rows = "";
    //
    //     for(var i=0;i<num_rows;i++)
    //     {
    //         rows +='<tr><td>'+result.noofteams+'</td></tr>';
    //     }
    // $("#admin1container").append(rows);
    // }

//   NEED TO CALCULATE TOTAL FOR TABLE FROM DATA RETURNED
    /*
             console.log(summary1data);
             console.log(summary1data[1].CourseName);
             console.log(summary1data[1]);

    for (var i=0; i<summary1data.length; i++)
    {
        console.log(response[i][name]);
        console.log(response[i][name]);
    }
*/

        }).error(function (message) {
            error(message);
        });
}

function fetchAdminSummary2() {
    var selected = [];
    $('#summary2_checkboxes input:checked').each(function () {
        selected.push($(this).val());
    });

    if ($.isEmptyObject(selected)) {
        alert("Please select a semester");
        return;
    }

    var data = {};
    data.semesters = selected;

    makeCall("fetchAdminSummary2", data)
        .success(function (response, error) {

            //TODO: populate summary2 table

            console.log(response);


        }).error(function (message) {
            error(message);
        });
}

var avaiTutorIds = [];

function fetchAvaiTutorWithRatingSummary() {
    var courseSchool = $("#student_search_course .school_list").val();
    var courseNumber = $("#student_search_course .number_list").val();

    var data = {};
    data.courseSchool = courseSchool;
    data.courseNumber = courseNumber;
    data.studentAvai = getSelectedSlotsFromCal("#student_calendar");

    if (courseSchool == null || courseNumber == null) {
        alert("Please select a course");
        return;
    }

    // TODO: check if student selected avai times

    makeCall("fetchAvaiTutorWithRatingSummary", data)
        .success(function (response, error) {

            if (response == '' || response == null) {
                alert("No tutor is available in your selected times.");
                return;
            }

            var tbody = $('#avai_tutor tbody:last');

            tbody.empty();

            var tutors = JSON.parse(response);

            for (var i = 0; i < tutors.length; ++i) {
                var tutor = tutors[i];

                var tr = trTag.clone();

                tr.append(tdTag.clone().text(tutor.fname));
                tr.append(tdTag.clone().text(tutor.lname));
                tr.append(tdTag.clone().text(tutor.email));
                tr.append(tdTag.clone().text(tutor.recAvg));
                tr.append(tdTag.clone().text(tutor.recCount));
                tr.append(tdTag.clone().text(tutor.rateAvg));
                tr.append(tdTag.clone().text(tutor.rateCount));

                tbody.append(tr);

                avaiTutorIds.push(tutor.gtid);
            }

            $('#avai_tutor_modal').foundation('reveal', 'open');

            // ======
            // populate tutor schedule to select

            $("#Mon").empty(); $("#Tue").empty(); $("#Wed").empty();
            $("#Thu").empty(); $("#Fri").empty();

            for (var i = 0; i < tutors.length; ++i) {
                var tutor = tutors[i];

                var span = spanTag.clone();
                span.attr("class", "event gray");

                var time = labelTag.clone();
                var name = labelTag.clone();
                var email = labelTag.clone();

                time.attr("class", "time");
                name.attr("class", "name");
                email.attr("class", "email");

                time.html(moment(tutor.time, "HH:mm:ss").format("ha"));
                name.html(tutor.fname + " " + tutor.lname);
                email.html("[" + tutor.email + "]");

                span.append(time);
                span.append(name);
                span.append(email);

                var dayDiv = $("#" + tutor.weekday);
                dayDiv.append(span);

                span.click(toggleTimeSlot);
            }

            $("#selected_course").text(courseSchoolSelected + " " + courseNumberSelected);
            disableMultipleSlotsSelection("#tutor_avai_calendar");

        }).error(function (message) {
            error(message)
        });
}


function scheduleSelectedTutor() {

    var slot = getSelectedTutorSlot();
    slot.school = courseSchoolSelected;
    slot.number = courseNumberSelected;

    makeCall("scheduleSelectedTutor", slot)
        .success(function (response, error) {
            alert("Tutor Scheduled!!!");
            // window.location = "/main-menu.php";
        }).error(function (message) {
            error("You already have a tutor scheduled for this time!");
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
    var gtas = $("#course_list input[type='checkbox']");

    var schoolCount = 0;
    var numCount = 0;
    var i = 0;
    schools.each(function () {

        var val = $(this).val();
        if (val != null && val != '') {
            courses.push(val);
            ++schoolCount;
        }

    });
    numbers.each(function () {

        var val = $(this).val();
        if (val != null && val != '') {
            courses[i] = courses[i] + " " + val;
            ++numCount;
        }
        ++i;
    });

    i = 0;
    gtas.each(function () {

        var val = $(this).is(':checked');
        courses[i] = courses[i] + " " + val;
        ++i;
    });

    if (numCount < 1 || $("#course_list .number_list").val() == null) {
        alert("You must select a course to teach.");
        return;
    }

    // console.log(JSON.stringify(courses));
    // return;

    var data = {};
    data.tutorId = tutorId;
    data.firstName = firstName;
    data.lastName = lastName;
    data.email = email;
    data.phone = phone;
    data.gpa = gpa;
    data.isGraduate = (studentType == 'grad') ? true : false;
    data.courses = courses;
    data.avai = getSelectedSlotsFromCal("#tutor_calendar");


    var avaiCount = 0;
    for (var i=0; i < data.avai.length; ++i) {
        avaiCount += data.avai[i].times.length;
    }
    if (avaiCount < 5) {
        alert("You need to have at least 5 available time slots selected");
        return;
    }

    makeCall("submitTutorApp", data)
        .success(function (response, error) {
            console.log(response);
            alert("Tutor Application Submitted");
            window.location = "/main-menu.php";
        }).error(function (message) {
            error(message);
        });

}

function showTutorSchedule() {

    var tutorId = $("#tutor_gtid").val();

    makeCall("fetchTutorSchedule", tutorId)
        .success(function (response, error) {
            //TODO: populate current tutor schedule

            console.log(response);

        }).error(function (message) {
            error(message);
        });
}

function submitStudentEval() {


    var courseShool = $("#student_rate_course .school_list").val();
    var courseNumber = $("#student_rate_course .number_list").val();
    var tutorId = $("#rate_tutor_name_list").val();
    var descEval = $("#rate_desc_eval").val();
    var numEval = $('input[name="rating"]:checked').val();

    if (courseShool == null || courseNumber == null) {
        alert("Please select a course!")
        return;
    }
    if (tutorId == null) {
//        tutorName.attr("class", "error");
//        $("#in_tutor_name_error").show();
        alert("Please select a tutor name!");
        return;
    }
    if (descEval == null) {
        alert("You don't have anything to say about the tutor?");
        return;
    }
    if (numEval == null) {
        alert("Please rate this tutor!");
        return;
    }

    var data = {};
    data.courseSchool = courseShool;
    data.courseNumber = courseNumber;
    data.tutorId = tutorId;
    data.descEval = descEval;
    data.numEval = numEval;

    makeCall("submitStudentEval", data)
        .success(function (response, error) {

//            $("#in_tutor_name").attr("class", "");
//            $("#in_tutor_name_error").hide();

            alert("Rated this tutor");
            console.log(response);
            window.location = "/main-menu.php";
        }).error(function (message) {
            error(message);
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


	
    if (tutorId == null || descEval == '' || numEval == null) {
        alert("Please fill in all required input forms");
        return;
    }

    makeCall("submitProfEval", data)
        .success(function (response, error) {

            alert("Recommended this tutor");
            console.log(response);

            window.location = "/main-menu.php";
        }).error(function (message) {
            error("You must enter a valid tutor GTID");
        });

}
