function sendCreateForm() {
    console.log("sendCreateForm called;")
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          alert("Successfully created your Gig!");
        }
        if (this.readyState == 4 && this.status != 200) {
          alert("Error encountered: " + this.responseText);
        }
    };
    xhttp.open("POST", "./create-api.php");

    // var title = document.getElementById("title").value
    // var hours = document.getElementById("hours").value
    // var pay = document.getElementById("pay").value
    // var desc = document.getElementById("desc").value
    // var data = { 'title': title, 'time': hours, 'price': pay, 'description': desc}
    // xhttp.send(JSON.stringify(data));

    var fd = new FormData();
    fd.append("title", document.getElementById("title").value);
    fd.append("time", document.getElementById("hours").value);
    fd.append("price", document.getElementById("pay").value);
    fd.append("description", document.getElementById("desc").value);
    fd.append("image", document.getElementById("image").files[0]);

    xhttp.send(fd);
}