<?php
include '../functions.php';

// Check if HTTP request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Request method must be POST';
    die();
}

// // Get number at end of url if it exists
// $id = basename($_SERVER['REQUEST_URI']);

// Receive POST
$id = file_get_contents('php://input');

if (!is_numeric($id) || $id < 0) {
    http_response_code(404);
    echo "User does not exist";
    die();
}

$conn = connect_to_database();
if ($conn === NULL) {
    http_response_code(500);
    echo "Failed to connect to database";
    die();
}

$record = get_posts_by_user_id($id);
if ($record === NULL) {
    http_response_code(500);
    echo "Error is retrieving posts";
    die();
}

header("Content-Type: application/json");
echo json_encode($record);
?>