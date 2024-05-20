function requestCode() 
{
    console.log("requestCode called;")
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            window.location.href = "verifyCode/verify.html";
        }
        else{
            document.getElementById("signup_message").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("POST", "./resetAPI.php");

    var email = document.getElementById("email").value

    var data = {'email':email}

    xhttp.send(JSON.stringify(data));
}