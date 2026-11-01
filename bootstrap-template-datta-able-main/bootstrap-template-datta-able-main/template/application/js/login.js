$("#loginForm").on("submit", function (e) {
    e.preventDefault();

    let username = $("#username").val();
    let password = $("#password").val();








    
    let sendingData = {
        "action": "login",
        "username": username,
        "password": password,
    };

    $.ajax({
        method: "POST",
        dataType: "json",
        url: "../api/login.php",
        data: sendingData,
        success: function (data) {
            let status = data.status;
            let response = data.data;

            if (status){
               window.location.href = "../views/dashboad.php";
            } else {
                displayMessage("error", response);
            }
        },
        error: function (data) {
            displayMessage("error", "An error occurred while processing your request.");
        },
    });
});

function displayMessage(type, message) {
    let success = document.querySelector(".alert-success");
    let error = document.querySelector(".alert-danger");

    if (type == "success") {
        error.classList = "alert alert-danger d-none";
        success.classList = "alert alert-success";
        success.innerHTML = message;
       
    } else {
        success.classList = "alert alert-success d-none";
        error.classList = "alert alert-danger";
        error.innerHTML = message;
         $("#password").val("");
    }
}