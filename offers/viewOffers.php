<?php
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo 'Request method must be GET';
    die();
}

# Validate user 
session_start();
if(!isset($_SESSION["id"])) {
    echo "Please login to view offers!";
    http_response_code(400);
    die();
}

http_response_code(200);

// Connect to server
$conn = connect_to_database();

// Check server connection
if(!$conn) {
    http_response_code(400);
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_SESSION["id"];
$status = "ACCEPTED";

$offers = [];
$recieverAccepted = [];
$senderAccepted = [];
$senderNotAccepted = [];


// Checks if the post exists
$stmt = "SELECT * FROM offers WHERE recieverID = '$id'";
$result = $conn->query($stmt);

if ($result->num_rows > 0) {
    $totalOffers = $result->num_rows;
    // Output data for each row
    while($totalOffers > 0) {
        $info = $result->fetch_assoc();

        // Gets username for reciever and sender IDs and attaches them to the JSON object
        $recieverName = get_username_from_id($id);
        $senderID = $info["senderID"];
        $stmt2 = "SELECT username FROM users WHERE id = '$senderID'";
        $result2 = $conn->query($stmt2);
        $senderName = $result2->fetch_assoc();
        $info["recieverName"] = $recieverName;
        $info["senderName"] = $senderName["username"];

        // Get accepted statuses for recieverID only
        if ($info["status"] == $status) {
            $recieverAccepted[] = $info;
        }
        // Get all relevant statuses
        $offers[] = $info;
        $totalOffers = $totalOffers - 1;
    }
    // Send an array with $post[0] being all offers made to user and $post[1] being all accepted offers
} /*else {
    http_response_code(405);
    echo "No Offers";
}*/

// Checks if the post exists
$stmt = "SELECT * FROM offers WHERE senderID = '$id'";
$result = $conn->query($stmt);

if ($result->num_rows > 0) {
    $totalOffers = $result->num_rows;
    // Output data for each row
    while($totalOffers > 0) {
        $info = $result->fetch_assoc();

        // Gets username for reciever and sender IDs and attaches them to the JSON object
        $senderName = get_username_from_id($id);
        $recieverID = $info["recieverID"];
        $stmt2 = "SELECT username FROM users WHERE id = '$recieverID'";
        $result2 = $conn->query($stmt2);
        $recieverName = $result2->fetch_assoc();
        $info["recieverName"] = $recieverName["username"];
        $info["senderName"] = $senderName;
        
        // Get accepted statuses for senserID only
        if ($info["status"] == $status) {
            $senderAccepted[] = $info;
        }
        if ($info["status"] == "PENDING") {
            $senderNotAccepted[] = $info;
        }
        $totalOffers = $totalOffers - 1;
    }
} /*else {
    http_response_code(405);
    echo "No Offers";
}*/
// Send ARRAY(post[0] = ALL OFFERS, post[1] = recieverID accepted, post[2] = senderID accepted)
$posts = array($offers, $recieverAccepted, $senderAccepted, $senderNotAccepted);
echo json_encode($posts);
mysqli_close($conn);
?>
