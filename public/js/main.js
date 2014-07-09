var DEBUG = true;

$(document).ready(function () {

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

function showId() {
    makeCall("showId", "")
        .success(function(response, error) {
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
