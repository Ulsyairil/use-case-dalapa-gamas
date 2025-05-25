function login() {
    let username = $("#username").val();
    let password = $("#password").val();

    $.ajax({
        type: "POST",
        url: "/login-request",
        data: {
            username: username,
            password: password
        },
        dataType: "json",
        success: function (response) {
            if (response.code != 0) {
                return notyf.error(response.message);
            }

            location.href = "/ticket";
        },
        error: function (response) {
            return notyf.error(response.responseJSON.message);
        }
    });
}

