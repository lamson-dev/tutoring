var DEBUG = true;

var aTag = $("<a>");
var h3Tag = $("<h3>");
var pTag = $("<p>");
var liTag = $("<li>");
var divTag = $("<div>");
var spanTag = $("<span>");
var imgTag = $("<img>");
var optionTag = $("<option>");

$(document).ready(function () {

    fetchSchoolList();


    $("#btn_login").click(login);

    $("#btn_apply").click(showId);

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

            var data = JSON.parse(response);

            var schoolList = $("#schoolList");

            for (var obj in data) {

                console.log(obj.School);

                var option = optionTag.clone();
                option.attr("value", obj.School);
                option.html(obj.School);
                schoolList.append(option);
            }
        });
}

var schoolSelected = null;

function fetchCourseNumberList() {
    var data = {};
    data.school = schoolSelected;
    makeCall("fetchCourseNumbrList", data)
        .success(function (response, error) {
            var data = JSON.parse(response);

            var numberList = $("#numberList");

            for (var obj in data) {

                console.log(obj.Number);

                var option = optionTag.clone();
                option.attr("value", obj.Number);
                option.html(obj.Number);
                numberList.append(option);
            }
        });
}

function showId() {
    makeCall("showId", "")
        .success(function (response, error) {
            alert(response);
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
