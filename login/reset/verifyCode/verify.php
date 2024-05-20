<?php
// Server info
$servername = "oceanus.cse.buffalo.edu:3306";
$username = "azhou7";
$password = "50375623";
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
$pass = $decoded_json->password;
$email = htmlspecialchars($decoded_json->email);
$code = htmlspecialchars($decoded_json->code);

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

if($code === null || $code === "") {
    http_response_code(400);
    echo "Please enter a code!";
    die();
}

if(strlen($user) > 32 || strlen($pass) > 64 || strlen($email) > 254 || strlen($code) > 10) {
    http_response_code(400);
    echo "invalid inputs";
    die();
}

//check if username is valid (there are no other usernames the same as the input)
$stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0) {
    http_response_code(400);
    echo "This username already exists";
    die();
}

//check if code and email matches with database
$stmt = $conn->prepare("SELECT id FROM resets WHERE email = ? AND code = ? ");
$stmt->bind_param("ss", $email, $code);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0) {
    //code and email matches
    $stmt = $conn->prepare("DELETE FROM resets WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
} else {
    http_response_code(400);
    echo "Code invalid";
    die();
}

//we now know the code is valid so update the user data
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->bind_param("ss", password_hash($pass, PASSWORD_DEFAULT), $email);
$stmt->execute();

$stmt = $conn->prepare("UPDATE users SET username = ? WHERE email = ?");
$stmt->bind_param("ss", $user, $email);
$stmt->execute();

mysqli_close($conn);
?>