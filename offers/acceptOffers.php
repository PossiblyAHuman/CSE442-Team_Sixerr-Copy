<?php
include '../functions.php';

// Validate server request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Request method must be POST';
    die();
}

// Validate user 
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

// Parse json string recieved from front end
$decoded_json = json_decode(file_get_contents('php://input'));

if($decoded_json === null) {
    http_response_code(400);
    echo "Server side error: No JSON data!";
    die();
}

$offerID = $decoded_json->offerID;

$id = $_SESSION["id"];

// Verify offer exists
$stmt = $conn->prepare("SELECT recieverID FROM offers WHERE offerID = ?");
$stmt->bind_param("i", $offerID);
$stmt->execute();
$result = $stmt->get_result();
$info = $result->fetch_assoc();
if($info["recieverID"] != $id) {
    http_response_code(400);
    echo "Invalid Offer";
    die();
// Update db
} else {
    $accepted = "ACCEPTED";
    $stmt = $conn->prepare("UPDATE offers set status = '$accepted' WHERE offerID = ? AND recieverID = ?");
    $stmt->bind_param("ii", $offerID, $id);
    $stmt->execute();
}
mysqli_close($conn);
?>