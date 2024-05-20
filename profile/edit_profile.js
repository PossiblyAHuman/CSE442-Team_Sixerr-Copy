function openOverFlowPosts() {
    document.getElementById("OverFlowYourRequests").style.width = "100%";
  }

  function closeOverFlowPosts() {
    document.getElementById("OverFlowYourRequests").style.width = "0px";

  }


  function openSettings() {
    document.getElementById("settings-menu").style.width = "300px";
  }

  function closeSettings() {
    document.getElementById("settings-menu").style.width = "0px";

  }

  function openDialogue() {
    document.getElementById("pop-up-dialogue").style.width = "247px";
    document.getElementById("pop-up-dialogue").style.padding = "30px";
  }

  function closeDialogue() {
    document.getElementById("pop-up-dialogue").style.width = "0px";
    document.getElementById("pop-up-dialogue").style.padding = "0px";
  }

  function sendNewEmailPass() {
    console.log("sendNewEmailPass called;")
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          alert("Successfully modified profile details!");
        }
        if (this.readyState == 4 && this.status != 200) {
          alert("Error encountered: " + this.responseText);
        }
    };
    xhttp.open("POST", "./edit-api.php");

    // var email = document.getElementById("email").value
    // var pwd = document.getElementById("pass").value
    // var data = { 'email': email, 'password': pwd }
    // xhttp.send(JSON.stringify(data));

    var fd = new FormData();
    fd.append("email", document.getElementById("email").value);
    fd.append("password", document.getElementById("pass").value);
    fd.append("image", document.getElementById("image").files[0]);

    xhttp.send(fd);
}

function sendNewBio() {
  console.log("sendNewBio called;")
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        alert("Successfully updated Bio!")
      }
      if (this.readyState == 4 && this.status != 200) {
        alert("Error encountered: " + this.responseText);
      }
  };
  xhttp.open("POST", "./edit-api.php");

  // var bio = document.getElementById("bio").value
  // var data = { 'bio': bio}
  // xhttp.send(JSON.stringify(data));

  var fd = new FormData();
  fd.append("bio", document.getElementById("bio").value);

  xhttp.send(fd);
}

function switchLightMode() {
  console.log("switchLightMode called;")
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        //alert("Successfully changed lightmode!")
        location.reload();
      }
      if (this.readyState == 4 && this.status != 200) {
        alert("Error encountered: " + this.responseText);
      }
  };
  xhttp.open("POST", "./darkMode.php");
  var data = {}
  xhttp.send(JSON.stringify(data));


}