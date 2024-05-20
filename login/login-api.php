<?php
include '../functions.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") { // Check if method is POST
    http_response_code(405);
    echo "Request method must be POST";
    die();
}

http_response_code(400); // Will respond with 400 unless changed

// Receive POST
$data = file_get_contents('php://input');

if ($data === "") { // Empty body
    echo "The body of the request was empty";
    die();
}

// JSON processing
$fields = json_decode($data); // Malformed JSON
if ($fields === null) {
    echo "The JSON object sent was malformed";
    die();
}

$username = $fields->username;
if ($username === null || $username === "") { // JSON with username missing or empty string
    echo "Username cannot be empty";
    die();
}

$password = $fields->password;
if ($password === null || $password === "") { // JSON with password missing or empty string
    echo "Password cannot be empty";
    die();
}

if (strlen($username) > 32) { // Username too long
    echo "Username cannot be more than 32 characters";
    die();
}

if (strlen($password) > 64) { // Password too long
    echo "Password cannot be more than 64 characters";
    die();
}

// Establish database connection
$conn = connect_to_database();
if ($conn === NULL) {
    http_response_code(500);
    echo "Login failed: failed to connect to database";
    die();
}

// Database query
$stmt = $conn->prepare("SELECT password, id FROM users WHERE username = ?");
$stmt->bind_param("s", htmlspecialchars($username));
$stmt->execute();

$result = $stmt->get_result();

$stmt->close();
$conn->close();

if ($result->num_rows === 0) { // User does not exist
    http_response_code(404);
    echo "Username does not correspond to an existing user";
    die();
}

$record = $result->fetch_assoc();
if (password_verify($password, $record['password'])) { // Password correct
    http_response_code(200);
    session_set_cookie_params(['secure' => true, 'httponly' => true]);
    session_start();
    session_regenerate_id();

    $_SESSION["id"] = $record["id"];
} else {
    echo "Password incorrect";
}
?>