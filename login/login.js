function sendLoginInfo() {
    console.log("sendLoginInfo called;")
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
		// Simulate a mouse click:
		location.href = "../homepage-ui/homepage.php";
            //document.getElementById("loginMessage").innerHTML =
                //"<p>Login Successful! Go to the " + this.responseText + " to access your homepage.</p>";
        }
        if (this.readyState == 4 && this.status != 200) {
            document.getElementById("loginMessage").innerHTML =
                "<p>Login Unuccessful due to <b>" + this.responseText + "</b> . Please log in again.</p>";
        }
    };
    xhttp.open("POST", "./login-api.php");

    var user = document.getElementById("username").value
    var pwd = document.getElementById("password").value
    var data = { 'username': user, 'password': pwd }
    xhttp.send(JSON.stringify(data));
}