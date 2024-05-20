<?php
include '../functions.php';

// Check if HTTP request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Request method must be POST';
    die();
}

// Receive body
$id = file_get_contents('php://input');

if (!is_numeric($id) || $id < 0) {
    http_response_code(404);
    echo "Post does not exist";
    die();
}

if (remove_post($id) === false) {
    http_response_code(500);
    echo "Failed to remove post";
    die();
}

http_response_code(200);
?>