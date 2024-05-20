<?php
include '../functions.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") { // Check if method is POST
    http_response_code(405);
    echo "Request method must be POST";
    die();
}

http_response_code(400); // Will respond with 400 unless changed

// Check if logged in
session_start();
if (!isset($_SESSION["id"])) {
    echo "Must be logged in to edit profile";
    die();
 }

// // Receive POST
// $data = file_get_contents('php://input');

// if ($data === "") { // Empty body
//     echo "The body of the request was empty";
//     die();
// }

// // JSON processing
// $fields = json_decode($data, true); // Malformed JSON
// if ($fields === null) {
//     echo "The JSON object sent was malformed";
//     die();
// }

// Receive form and don't change old code
$fields = $_POST;

// Check if no fields exist or are all empty strings
$field_names = array("password", "email", "bio");
$is_empty = true;
foreach ($field_names as $name) {
    if (array_key_exists($name, $fields) && $fields[$name] !== "") { // JSON with field missing or empty string
        $is_empty = false;
    }
}
if (array_key_exists("image", $_FILES) && (!empty($_FILES["image"]) || $_FILES["image"]["error"] === 0 || $_FILES["image"]["size"] !== 0)) {
    $is_empty = false;
} 
if ($is_empty) {
    echo "No fields specified";
    die();
}

// Verify password if it exists
if (array_key_exists("password", $fields)) {
    if (strlen($fields["password"]) > 64) { // Password too long
        echo "Password cannot be more than 64 characters";
        die();
    }
}

// Verify bio if it exists
if (array_key_exists("bio", $fields)) {
    if (strlen(htmlspecialchars($fields["bio"])) > 300) { // Bio too long
        echo "Bio cannot be more than 300 characters";
        die();
    }
}

// Verify email if it exists
if (array_key_exists("email", $fields)) {
    if (strlen(htmlspecialchars($fields["email"])) > 254) { // Email too long
        echo "Email cannot be more than 254 characters";
        die();
    }
}

// Verify image if it exists
if (array_key_exists("image", $_FILES)) {
    $extension = get_image_file_extension($_FILES['image']['name']);
    $filename = $_FILES['image']['tmp_name'];

    if ($extension === null) {
        echo "Image must be png, jpg, or jpeg";
        die();
    }
}

// Establish database connection
$conn = connect_to_database();
if ($conn === NULL) {
    http_response_code(500);
    echo "Login failed: failed to connect to database";
    die();
}

// Update fields if they exist
if (array_key_exists("email", $fields) && $fields["email"] !== "") {
    // Check if new email already in use
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", htmlspecialchars($fields["email"]));
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();

    if($result->num_rows > 0) {
        http_response_code(400);
        echo "Email already in use";
        $conn->close();
        die();
    }

    // Update
    $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->bind_param("si", htmlspecialchars($fields["email"]), $_SESSION["id"]);
    if (!$stmt->execute()) {
        http_response_code(500);
        $conn->close();
        echo "Server error in editing profile";
        die();
    }
    $stmt->close();
}

if (array_key_exists("password", $fields) && $fields["password"] !== "") {
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", password_hash($fields["password"], PASSWORD_DEFAULT), $_SESSION["id"]);
    if (!$stmt->execute()) {
        http_response_code(500);
        $conn->close();
        echo "Server error in editing profile";
        die();
    }
    $stmt->close();
}

if (array_key_exists("bio", $fields) && $fields["bio"] !== "") {
    $stmt = $conn->prepare("UPDATE users SET bio = ? WHERE id = ?");
    $stmt->bind_param("si", htmlspecialchars($fields["bio"]), $_SESSION["id"]);
    if (!$stmt->execute()) {
        http_response_code(500);
        $conn->close();
        echo "Server error in editing profile";
        die();
    }
    $stmt->close();
}

if (array_key_exists("image", $_FILES)) {
    // Write image
    $image = upload_form_image($filename, $extension);
    if ($image === null) {
        http_response_code(500);
        $conn->close();
        echo "Error uploading profile picture";
        die();
    }

    $stmt = $conn->prepare("UPDATE users SET picture = ? WHERE id = ?");
    $stmt->bind_param("si", $image, $_SESSION["id"]);
    if (!$stmt->execute()) {
        http_response_code(500);
        $conn->close();
        echo "Server error in editing profile";
        die();
    }
    $stmt->close();
}

$conn->close();

http_response_code(200);
?>
