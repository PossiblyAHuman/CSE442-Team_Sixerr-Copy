function uploadSignupInfo() 
{
    
    console.log("uploadSignupInfo called;")
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            window.location.href = "Sign_up_success_page/index_success.html";
        }
        else{
            document.getElementById("signup_message").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("POST", "./registrationAPI.php");

    var username = document.getElementById("username").value
    var password = document.getElementById("password").value
    var email = document.getElementById("email").value

    var data = {'username':username, 'password':password, 'email':email}

    xhttp.send(JSON.stringify(data));
}