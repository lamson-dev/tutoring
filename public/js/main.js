var DEBUG = true;

$(document).ready(function () {

    $("#btn_login").click(login);

});

function login() {

    var gtid = $("#gtid").val();
    var password = $("#password").val();

    var data = {};
    data.gtid = gtid;
    data.password = password;

    $.ajax({
        type: 'POST',
        url: 'main.php',
        data: {
            'action': 'login',
            'json': JSON.stringify(data)
        },
        success: function (response, error) {
            alert(response);
        }
    });

}