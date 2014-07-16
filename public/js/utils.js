var aTag = $("<a>");
var h3Tag = $("<h3>");
var pTag = $("<p>");
var liTag = $("<li>");
var divTag = $("<div>");
var spanTag = $("<span>");
var imgTag = $("<img>");
var optionTag = $("<option>");


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

function getAvaiTimeDataFromCal(calendarId) {

    var avaiTime = [];

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

        avaiTime.push(obj);
    });

//    console.log(JSON.stringify(avaiTime));

    return avaiTime;
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