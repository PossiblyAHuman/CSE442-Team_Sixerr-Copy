<?php
# Validate proper request: POST
if($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Server side error: Invalid Request!";
    die();
}

http_response_code(200);

# Validate user 
session_start();
if(!isset($_SESSION["id"])) {
    echo "Please login to view your profile";
    http_response_code(400);
    die();
}

// Parse json string recieved from front end
$decoded_json = json_decode(file_get_contents('php://input'));

if($decoded_json === null) {
    http_response_code(400);
    echo "Server side error: No JSON data!";
    die();
}

$rating = (int)htmlspecialchars($decoded_json->rating);
$review = htmlspecialchars($decoded_json->review);
$reviewee = (int)htmlspecialchars($decoded_json->reviewee);

// Basic checks
if($reviewee == 0 || $reviewee < 0) {
    http_response_code(400);
    echo "Invalid User";
    die();
}
if($rating > 5 || $rating == 0 || $rating < 0) {
    http_response_code(400);
    echo "Please provide a rating between 1-5";
    die();
}
if(strlen($review) > 300) {
    http_response_code(400);
    echo "Review is too long";
    die();
}

# Server info
$servername = "oceanus.cse.buffalo.edu:3306";
$username = "tli58";
$password = "50351999";
$db = "cse442_2023_spring_team_t_db";

# Create server connection
$conn = mysqli_connect($servername, $username, $password, $db);

# Check server connection
if(!$conn) {
    http_response_code(400);
    die("Connection failed: " . mysqli_connect_error());
}

$id = $_SESSION["id"];

if($id == $reviewee) {
    echo "You cannot review yourself!";
    die();
}

// Gets Reviewee ID
$stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
$stmt->bind_param("i", $reviewee);
$stmt->execute();
$result = $stmt->get_result();
// Check if this user exists
if($result->num_rows == 0) {
    http_response_code(400);
    echo "Invalid User";
    die();
} 

$stmt = "SELECT review_id from review WHERE reviewer_id = '$id' AND reviewee_id = '$reviewee'";
$result = $conn->query($stmt);
// Check if this user made a review for reviewee
if($result->num_rows != 0) {
    http_response_code(200);
    echo "You already made a review for this person!";
    die();
} else {
    date_default_timezone_set("America/New_York");
    $time = date("m/d/y");
    $stmt = $conn->prepare("INSERT INTO review (reviewee_id, reviewer_id, review, rating, time)
        VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisis", $reviewee, $id, $review, $rating, $time);
    $stmt->execute();

    // Calcualte new rating
    $stmt = "SELECT rating FROM users WHERE id = '$reviewee'";
    $main_rating = $conn->query($stmt);

    $stmt = "SELECT rating FROM review WHERE reviewee_id = '$reviewee'";
    $result = $conn->query($stmt);
    $all_rating = $result->num_rows;

    $total = 0;
    while($row = $result->fetch_assoc()) {
        $total += (int)$row["id"];
    }

    $new_rating = (($main_rating * $all_rating) + $rating)/($total + 1);
    if($new_rating > 5) {
        $new_rating = 5;
    }
    $stmt = "UPDATE users SET rating = '$new_rating' WHERE id = '$reviewee'";
    $result = $conn->query($stmt);
}
mysqli_close($conn);
?>