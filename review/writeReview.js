function sendReview(reviewee) {
    console.log("sendReview called;")
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
		// Simulate a mouse click:
		//location.href = "../homepage-ui/homepage.php";
            console.log("review", this.responseText)
            document.getElementById("reviewMessage").innerHTML = "<p>Review Has Been Submitted!</p>"
                //"<p>Login Successful! Go to the " + this.responseText + " to access your homepage.</p>";
        }
        if (this.readyState == 4 && this.status != 200) {
            console.log("review", this.responseText)
            document.getElementById("reviewMessage").innerHTML = "<p>Some Error Occured</p>"
                //"<p>Login Unuccessful due to <b>" + this.responseText + "</b> . Please log in again.</p>";
        }
    };
    xhttp.open("POST", "../addReview.php");

    var rate = document.getElementById("rating").value
    var rev = document.getElementById("review").value
    var data = { 'review': rev, 'rating': rate, "reviewee": reviewee}
    xhttp.send(JSON.stringify(data));
}