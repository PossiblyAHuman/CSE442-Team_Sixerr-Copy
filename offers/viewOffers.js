function fetchOffers() {
    console.log("fetching offers;")
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText)
            var offers = JSON.parse(this.responseText)[0];
            /*for (offer of offers) {
                console.log(offer);
            }*/

            //console.log(offers)
            


            var grouped = [];
            offers.forEach(function (a) {
                this[a.postID] || grouped.push(this[a.postID] = []);
                this[a.postID].push(a);
            }, Object.create(null));
            console.log(grouped);


            /*<div class="Row">
                <div class="">
                    <p>The id of the candidate is <?php echo $user;?></p>
                    <p>OfferMessage</p>
                    <a href="https://www-student.cse.buffalo.edu/CSE442-542/2023-Spring/cse-442t/test/profileReviewTests/viewProfileAPI.php/?id=<?php echo $user; ?>">View the candidate's public profile</a>
                </div>
            </div>*/
            /*if (grouped.length == 1) {
                grouped = [[grouped[0]]]
            }*/
            console.log(grouped)
            var AllOffersHTML = ""

            if (grouped.length == 0) {
                AllOffersHTML = "<p>You have not recieved any offers yet, make posts to recieve offers!</p>"
            }

            for (postOffers of grouped) {
                var postOffersHTML = "<div class='postOffers'>"
                postOffersHTML += `<h1>Offers for the post #${postOffers[0].postID}</h1>`
                postOffersHTML += `<h2>
                    <a href="./get-post.php/?postId=${postOffers[0].postID}">View the full post here</a>
                </h2>
                <button onclick='myFunction(${postOffers[0].postID})'>Display/Hide List</button>
                <div id='${postOffers[0].postID}'>`
                for (offer of postOffers) {
                    var acceptButtonHTML = `<p>Offer has already been accepted.</p>`
                    if (offer.status == "PENDING") {
                        //acceptButtonHTML = `<div id=${offer.offerID + "AcceptButton"}><button onclick="acceptOffer(${offer.offerID})">Accept This Offer</button></div>`
                        acceptButtonHTML = `<button id=${offer.offerID + "AcceptButton"} onclick="acceptOffer(${offer.offerID})">Accept This Offer</button>`
                    }

                    postOffersHTML += `<div id=${offer.offerID} class="offer">
                        <div>
                            <div class="Row">
                                <p>Post Id: ${offer.postID}</p>
                                <p>Offer Id: ${offer.offerID}</p>
                                <p>Candidate: ${offer.senderID}</p>
                                <p>Status: ${offer.status}</p>
                            </div>
                            <p>Offer Message: ${offer.message}</p>
                            <div class="Row">
                                <a href="../review/viewProfileAPI.php/?id=${offer.senderID}"><button>View the Candidate's Public Profile.</button></a>
                                ${acceptButtonHTML}
                            </div>
                        </div>
                    </div>`
                }
                postOffersHTML += "</div></div>";
                AllOffersHTML += postOffersHTML;
            }
            document.getElementById("offersList").innerHTML = AllOffersHTML;



        }
        if (this.readyState == 4 && this.status != 200) {
            document.getElementById("offersList").innerHTML =
                "<p>Unable to fetch offers due to <b>" + this.responseText + "</b>. Please try again.</p>";
        }
    };
    
    //var data = { 'offerMessage': offerMessage, 'postID': postID, "ownerID": ownerID }
    //console.log("Sending: ", data)

    xhttp.open("GET", "./viewOffers.php");
    xhttp.send();
}





function acceptOffer(offerID) {
    console.log("sendOffer called;")
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById(offerID +"AcceptButton").outerHTML =
                "<p>Offer Accepted Now.</p>";
        }
        if (this.readyState == 4 && this.status != 200) {
            //document.getElementById("offerMessage").innerHTML =
                //"<p>Unable to accept offer due to <b>" + this.responseText + "</b> . Please accept offer again.</p>";
            alert("Unable to accept offer due to " + this.responseText);
        }
    };

    var data = {'offerID': offerID}
    console.log("Accepting the offer: ", data)

    xhttp.open("POST", "./acceptOffers.php");
    xhttp.send(JSON.stringify(data));
}




function myFunction(postId) {
    var x = document.getElementById(postId);
    if (x.style.display === "none") {
        x.style.display = "block";
        console.log(1)
    } else {
        x.style.display = "none";
        console.log(2)
    }
}