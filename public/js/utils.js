
var DEBUG = false;

var aTag = $("<a>");
var h3Tag = $("<h3>");
var pTag = $("<p>");
var liTag = $("<li>");
var divTag = $("<div>");
var spanTag = $("<span>");
var imgTag = $("<img>");
var optionTag = $("<option>");
var trTag = $("<tr>");
var tdTag = $("<td>");

var labelTag = $("<label>");


function login() {

    var gtid = $("#gtid").val();
    var password = $("#password").val();

    var data = {};
    data.gtid = gtid;
    data.password = password;

    makeCall("login", data)
        .success(function (response, error) {
            alert("Logged In Successfully");

            window.location = "/main-menu.php";
        }).error(function (message) {
            error("Wrong GTID or Password");
        });

}

function logout() {

    makeCall("logout", "")
        .success(function (response, error) {
            alert("Logged Out Successfully");
            window.location = "/index.php";
        }).error(function (message) {
            error("Error logging out");
        });
}

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

function showMenuBasedOnUserType() {

    if (DEBUG) {
        $("#menu_student").show();
        $("#menu_admin").show();
        $("#menu_professor").show();
        $("#menu_tutor").show();
        return;
    }

    makeCall("getCurrentUserType", "")
        .success(function (response, error) {

            // alert("User type: " + response);
            $("#menu_" + response).show();

        }).error(function (message) {
            error(message);
        });

}

function error(message) {
    // TODO: make this throw error function better
    if (typeof message === 'object') {
        message = JSON.stringify(message);
    }
    alert("Error: " + message);
    return;
}

// Should work for most cases
function uniqueId() {
    return Math.round(new Date().getTime() + (Math.random() * 100));
}

function disableMultipleSlotsSelection(calendarId) {

    $(calendarId + " .event").click(function () {

        var events = $(calendarId).find(".event.green");
        events.each(function () {
            var slot = $(this);
            slot.removeClass("green");
            slot.addClass("gray");
        });

        $(this).removeClass("gray");
        $(this).addClass("green");
    });
}

function getSelectedTutorSlot() {

    var selection = $("#tutor_avai_calendar .green");
    var dayName = selection.closest("td").closest('table').find('th').eq(selection.closest("td").index()).text();

    var data = {};
    data.weekday = dayName;
    data.time = moment(selection.find(".time").text(), "ha").format("HH:mm");
    data.tutorName = selection.find(".name").text();
    data.tutorEmail = selection.find(".email").text();

    return data;
}

function getSelectedSlotsFromCal(calendarId) {

    var selectedSlots = [];

    var days = $(calendarId + " td");
    days.each(function () {

        var td = $(this);
        var dayName = td.closest('table').find('th').eq(td.index()).text();
        var events = td.find(".event.green");

        var obj = {};
        obj.weekday = dayName;
        obj.times = [];

        events.each(function () {

            var time = $(this).text();

            time = moment(time, "ha").format("HH:mm");

            obj.times.push(time);
        });

        selectedSlots.push(obj);
    });

//    console.log(JSON.stringify(selectedSlots));

    return selectedSlots;
}

function toggleTimeSlot() {
    var slot = $(this);

    if (slot.hasClass("green")) {
        slot.removeClass("green");
        slot.addClass("gray");
    } else {
        slot.removeClass("gray");
        slot.addClass("green");
    }
}

function getCurrentUserId() {
    makeCall("getCurrentUserId", "")
        .success(function (response, error) {
            alert(response);
            return response;
        });
}

function getCurrentUserType() {
    makeCall("getCurrentUserType", "")
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
