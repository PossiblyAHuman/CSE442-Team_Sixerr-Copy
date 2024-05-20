function verifyCode() {
    console.log("verifyCode called;")

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            window.location.href = "../../login.html";
        }
        else{
            document.getElementById("signup_message").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("POST", "./verify.php");

    var username = document.getElementById("username").value
    var password = document.getElementById("password").value
    var email = document.getElementById("email").value
    var code = document.getElementById("code").value


    var data = {'username':username, 'password':password, 'email':email, 'code':code}

    xhttp.send(JSON.stringify(data));
  }
