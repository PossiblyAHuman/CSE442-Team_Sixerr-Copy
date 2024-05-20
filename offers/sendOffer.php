<?php
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Request method must be POST';
    die();
}

# Validate user 
session_start();
if(!isset($_SESSION["id"])) {
    echo "Please login to write a review!";
    http_response_code(400);
    die();
}

http_response_code(200);

// Parse json string recieved from front end
$decoded_json = json_decode(file_get_contents('php://input'));

if($decoded_json === null) {
    http_response_code(400);
    echo "Server side error: No JSON data!";
    die();
}

$id = $_SESSION["id"];
$offerMessage = htmlspecialchars($decoded_json->offerMessage);
$postID = $decoded_json->postID;
$ownerID = $decoded_json->ownerID;

if(strlen($offerMessage) > 300) {
    http_response_code(400);
    echo "Your message is too long";
    die();
}

// Connect to server
$conn = connect_to_database();

// Check server connection
if(!$conn) {
    http_response_code(400);
    die("Connection failed: " . mysqli_connect_error());
}

// Checks if the post exists
$stmt = "SELECT post_id FROM posts WHERE post_id = '$postID'";
$result = $conn->query($stmt);
if($result->num_rows == 0) {
    http_response_code(400);
    echo "This post does not exists";
    die();
} else {
// Insert into table in Oceanus database
    $stmt = $conn->prepare("INSERT INTO offers (senderID, recieverID, postID, message)
        VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $id, $ownerID, $postID, $offerMessage);
    $stmt->execute();
    mysqli_close($conn);
}
mysqli_close($conn);
?>