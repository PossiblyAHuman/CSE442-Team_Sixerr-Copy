<?php
include '../functions.php';

// Check if HTTP request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Request method must be POST";
    die();
}

http_response_code(400); // Will respond with 400 unless changed

// Check if logged in
session_start();
if (!isset($_SESSION["id"])) {
    echo "Must be logged in to create a post";
    die();
}

// Validate form data
// if (empty($_POST["title"]) || empty($_POST["time"]) || empty($_POST["price"]) || empty($_POST["description"]) || empty($_FILES["image"]) || $_FILES["image"]["error"] !== 0 || $_FILES["image"]["size"] === 0) { # World's 32nd worst if statement
//     echo "Missing all required fields";
//     die();
// }

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

// Validate form data
if (!isset($_POST["title"]) || !isset($_POST["time"]) || !isset($_POST["price"]) || !isset($_POST["description"]) || !isset($_FILES["image"]) || $_FILES["image"]["error"] !== 0 || $_FILES["image"]["size"] === 0) {
// if (!isset($fields["title"]) || !isset($fields["time"]) || !isset($fields["price"]) || !isset($fields["description"])) {
    echo "Missing all required fields";
    die();
}

// $title = $fields['title'];
// $time = $fields['time'];
// $price = $fields['price'];
// $description = $fields['description'];

$title = $_POST["title"];
$time = $_POST["time"];
$price = $_POST["price"];
$description = $_POST["description"];
$extension = get_image_file_extension($_FILES['image']['name']);
$filename = $_FILES['image']['tmp_name'];

if (strlen($title) < 1) {
    echo "Title cannot be empty";
    die();
}

if (strlen($title) > 64) {
    echo "Title cannot be more than 32 characters";
    die();
}

if (!is_numeric($time) || 0 > $time || $time > 1000000) {
    echo "Time must be within 0-999999";
    die();
}

if (!is_numeric($price) || 0 > $price || $price > 1000000) {
    echo "Price must be within 0-999999";
    die();
}

if (strlen($description) > 2000) {
    echo "Description cannot be more than 2000 characters";
    die();
}

if ($extension === null) {
    echo "Image must be png, jpg, or jpeg";
    die();
}

if (!insert_post($title, $time, $price, $description, $filename, $extension)) {
// if (!insert_post($title, $time, $price, $description)) {
    http_response_code(500);
    echo "Did not create post";
    die();
}

http_response_code(200);
echo "Successfully created post";
?>
