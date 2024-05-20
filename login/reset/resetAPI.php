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

$email = htmlspecialchars($decoded_json->email);

// Validate user inputs

if($email === null || $email === "") {
    http_response_code(400);
    echo "Please enter an email!";
    die();
}

if(strlen($email) > 254) {
    http_response_code(400);
    echo "Email invalid";
    die();
}

$stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows == 0) {
    http_response_code(400);
    echo "No account tied to this Email";
    die();
}


$stmt = $conn->prepare("SELECT email FROM resets WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if($result) {
    //if this email is in the reset database, delete it
    $stmt = $conn->prepare("DELETE FROM resets WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
}

//make a random code
$numb = rand(10000, 100000);
$code = strval($numb);

// Insert into table in Oceanus database
$stmt = $conn->prepare("INSERT INTO resets (email, code)
    VALUES (?, ?)");
$stmt->bind_param("ss", $email, $code);
$stmt->execute();

mail($email, "Sixerr: Password Reset Code", $code);


mysqli_close($conn);
?>