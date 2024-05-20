<?php
# Validate proper request: Not POST, since we're not submitting a form. So should be GET.
if($_SERVER["REQUEST_METHOD"] == "POST") {
    http_response_code(405);
    echo "Server side error: Invalid Request!";
    die();
}

http_response_code(200);

# Validate user 
session_start();
if(!isset($_SESSION["id"])) {
    echo "Please login to write a review!";
    http_response_code(400);
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

$id = $_SESSION["id"]; // Reviewer
$reviewee = $_GET["id"]; // Reviewee

// Gets Username for reviewer
$stmt = "SELECT username FROM users WHERE id = '$id'";
$result = $conn->query($stmt);
$info = $result->fetch_assoc();
$user = $info["username"];

// Gets Username for reviewee
$stmt2 = "SELECT username FROM users WHERE id = '$reviewee'";
$result2 = $conn->query($stmt2);
$info2 = $result2->fetch_assoc();
$user2 = $info2["username"];

mysqli_close($conn);
?>

<html>
    <head>
        <title>Sixerr Profile Review</title>

        <script type="text/javascript" src="../writeReview.js"></script>
        <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
        <link rel = "icon" href = "./logoclear_001.png"/>
        <link rel="stylesheet" href="../writeReview.css">
    </head>

    <body class="backgroundColor">

        <div class="inputForm">
            <br>
            <img src="../images/logoclear_001.png" alt="logo" class="logoImg"/>
            <h1> <?php echo $user; ?>, Write Your Review to <?php echo $user2; ?></h1>

            <form id = "center">
                <input id="rating" type="number" name="rating" class="displayBlock" placeholder = "5">
                <input id="review" type="text" name="review" class="displayBlock" placeholder = "Review...">
                <p>
                <input type="button" value="Submit Review" onclick="sendReview(<?php echo $reviewee; ?>);" class="loginButton">
                </p>
                <br>
            </form>
        </div>

	    <br>
        <div id="reviewMessage" class="reviewMessage"></div>
    </body>
 
</html>