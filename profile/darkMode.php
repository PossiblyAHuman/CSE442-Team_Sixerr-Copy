<?php
include '../functions.php';

http_response_code(400); // Will respond with 400 unless changed
echo "darkMode.php reached";
// Check if logged in
session_start();
if (!isset($_SESSION["id"])) {
    echo "Must be logged in to edit profile";
    die();
 }
 $CSS_dark_mode;
 if($_COOKIE[$cookie_name] == "false"){
    setcookie($cookie_name, "true", time() + (86400 * 30 * 12 * 10), "/");
    $CSS_dark_mode = "";
 }else{
    setcookie($cookie_name, "false", time() + (86400 * 30 * 12 * 10), "/");
    $CSS_dark_mode = "
    <style>
    body {background-color: black;}
    .gigs-text{color: white;}
    </style>
    ";
 }

http_response_code(200);
?>
