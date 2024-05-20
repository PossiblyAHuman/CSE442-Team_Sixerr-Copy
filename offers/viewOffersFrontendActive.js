function fetchAcceptedOffers() {
    console.log("fetching accepted offers;")
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            //console.log(this.responseText)
            var offers = JSON.parse(this.responseText);

            console.log("Incoming Accepted Offers", offers[1])
            console.log("Outgoing Accepted Offers", offers[2])
            console.log("Outgoing Non-Accepted Offers", offers[3])
            var mainContainer = ""

            var requestedOffersHTML = "<div class='postOffers'>"
            requestedOffersHTML += "<h2>Incoming Accepted Offers</h2> <button onclick='myFunction1()'>Display/Hide List</button> <div id='list1'>"
            requestedOffersHTML += "<p>Description: Make a post, wait for others to send offers, review and accept one of the offers, and it will be here.</p>"
            requestedOffersHTML += "Count: " + offers[1].length;
            for (offer of offers[1]) {

                requestedOffersHTML += `<div id=${offer.offerID} class="offer">
                        <div>
                            <div class="Row">
                                <p>Post Id: ${offer.postID}</p>
                                <p>Offer Id: ${offer.offerID}</p>
                            </div>
                            <div class="Row">
                                <p>Employer (You): ${offer.recieverName}</p>
                                <p>Candidate: ${offer.senderName}</p>
                            </div>
                            <div class="Row">
                                <p>Offer Message: ${offer.message}</p>
                            </div>
                            <div class="Row">
                                <a href="../review/viewProfileAPI.php/?id=${offer.senderID}"><button>View the Candidate's Public Profile.</button></a>
                                <a href="./get-post.php/?postId=${offer.postID}">View Full Post</a>
                                <p>Status: ${offer.status}</p>
                            </div>
                        </div>
                    </div>`
            }
            requestedOffersHTML += "</div></div>";
            mainContainer += requestedOffersHTML;


            var assignedOffersHTML = "<div class='postOffers'>"
            assignedOffersHTML += "<h2>Your Outgoing Accepted Offers</h2> <button onclick='myFunction2()'>Display/Hide List</button> <div id='list2'>"
            assignedOffersHTML += "<p>Description: Search for posts, send offers, and wait for it to be accepted, and it will be here when it is accepted.</p>"
            assignedOffersHTML += "Count: " + offers[2].length;
            for (offer of offers[2]) {

                assignedOffersHTML += `<div id=${offer.offerID} class="offer">
                        <div>
                            <div class="Row">
                                <p>Post Id: ${offer.postID}</p>
                                <p>Offer Id: ${offer.offerID}</p>
                            </div>
                            <div class="Row">
                                <p>Employer: ${offer.recieverName}</p>
                                <p>Candidate (You): ${offer.senderName}</p>
                            </div>
                            <div class="Row">
                                <p>Offer Message: ${offer.message}</p>
                            </div>
                            <div class="Row">
                                <a href="../review/viewProfileAPI.php/?id=${offer.senderID}"><button>View the Candidate's Public Profile.</button></a>
                                <a href='./get-post.php/?postId=${offer.postID}'>View Full Post</a>
                                <p>Status: ${offer.status}</p>
                            </div>
                        </div>
                    </div>`
            }
            assignedOffersHTML += "</div></div>";
            mainContainer += assignedOffersHTML;



            var sentOffersHTML = "<div class='postOffers'>"
            sentOffersHTML += "<h2>Your Outgoing Pending Offers</h2> <button onclick='myFunction3()'>Display/Hide List</button> <div id='list3'>"
            sentOffersHTML += "<p>Description: Search for posts, send offers, and you can view your sent offers here.</p>"
            sentOffersHTML += "Count: " + offers[3].length;
            for (offer of offers[3]) {

                sentOffersHTML += `<div id=${offer.offerID} class="offer">
                        <div>
                            <div class="Row">
                                <p>Post Id: ${offer.postID}</p>
                                <p>Offer Id: ${offer.offerID}</p>
                            </div>
                            <div class="Row">
                                <p>Employer: ${offer.recieverName}</p>
                                <p>Candidate (You): ${offer.senderName}</p>
                            </div>
                            <div class="Row">
                                <p>Offer Message: ${offer.message}</p>
                            </div>
                            <div class="Row">
                                <a href="../review/viewProfileAPI.php/?id=${offer.senderID}"><button>View the Candidate's Public Profile.</button></a>
                                <a href='./get-post.php/?postId=${offer.postID}'>View Full Post</a>
                                <p>Status: ${offer.status}</p>
                            </div>
                        </div>
                    </div>`
            }
            sentOffersHTML += "</div></div>";

            mainContainer += sentOffersHTML;

            document.getElementById("offersList").innerHTML = mainContainer;
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





function myFunction1() {
        var x = document.getElementById("list1");
        if (x.style.display === "none") {
            x.style.display = "block";
            console.log(1)
        } else {
            x.style.display = "none";
            console.log(2)
        }
}


function myFunction2() {
    var x = document.getElementById("list2");
    if (x.style.display === "none") {
        x.style.display = "block";
        console.log(1)
    } else {
        x.style.display = "none";
        console.log(2)
    }
}


function myFunction3() {
    var x = document.getElementById("list3");
    if (x.style.display === "none") {
        x.style.display = "block";
        console.log(1)
    } else {
        x.style.display = "none";
        console.log(2)
    }
}