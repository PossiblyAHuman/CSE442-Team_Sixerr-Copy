<?php
// Server info
$servername = "oceanus.cse.buffalo.edu:3306";
$username = "tli58";
$password = "50351999";
$db = "cse442_2023_spring_team_t_db";

// Create server connection
$conn = mysqli_connect($servername, $username, $password, $db);

// Check server connection
if(!$conn) {
    http_response_code(400);
    die("Connection failed: " . mysqli_connect_error());
}

http_response_code(200);

// Parse json string recieved from front end
$str_json = file_get_contents('php://input');
$decoded_json = json_decode($str_json);

if($decoded_json === null) {
    http_response_code(400);
    echo "Server side error: No JSON data!";
    die();
}

$user = htmlspecialchars($decoded_json->username);
$pass = htmlspecialchars($decoded_json->password);
$email = htmlspecialchars($decoded_json->email);

// Validate user inputs
if($user === null || $user === "" ) {
    http_response_code(400);
    echo "Please enter a username!";
    die();
}
if($pass === null || $pass === "") {
    http_response_code(400);
    echo "Please enter a password!";
    die();
}

if($email === null || $email === "") {
    http_response_code(400);
    echo "Please enter an email!";
    die();
}

if(strlen($user) > 32 || strlen($pass) > 64 || strlen($email) > 254) {
    http_response_code(400);
    echo "Please follow the signup length requirements";
    die();
}

// Checks if the username already exists
$stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0) {
    http_response_code(400);
    echo "This username already exists";
    die();
} else {
// Insert into table in Oceanus database
    $stmt = $conn->prepare("INSERT INTO users (username, password, email)
        VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user, $pass, $email);
    $stmt->execute();
    mysqli_close($conn);
}
mysqli_close($conn);
?>