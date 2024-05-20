<?php
include '../functions.php';

// Check if HTTP request method is GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo 'Request method must be GET';
    die();
}

$posts = get_posts();
if ($posts === NULL) {
    http_response_code(500);
    echo "Error in retrieving posts";
    die();
}

header("Content-Type: application/json");
echo json_encode($posts);
?>