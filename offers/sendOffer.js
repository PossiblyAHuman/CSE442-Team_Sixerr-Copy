function sendOffer(postID, ownerID) {
    console.log("sendOffer called;")
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("offerResponse").innerHTML =
                "<p>Offer Submitted Successfully!</p>";
        }
        if (this.readyState == 4 && this.status != 200) {
            document.getElementById("offerResponse").innerHTML =
                "<p>Offer submission Unuccessful due to <b>" + this.responseText + "</b> . Please send offer again.</p>";
        }
    };

    var offerMessage = document.getElementById("offerMessage").value
    var data = { 'offerMessage': offerMessage, 'postID': postID, "ownerID": ownerID }
    console.log("Sending: ", data)

    xhttp.open("POST", "../sendOffer.php");
    xhttp.send(JSON.stringify(data));
}

function remove_post(post_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            alert("Successfully removed post");
          }
        if (this.readyState == 4 && this.status != 200) {
            alert("Error encountered: " + this.responseText);
        }
    };

    xhttp.open("POST", "../remove-post-api.php");
    xhttp.send(post_id);
}