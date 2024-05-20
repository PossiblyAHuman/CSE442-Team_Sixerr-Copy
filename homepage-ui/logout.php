<?php
session_start();
// remove all session variables
unset($_SESSION["id"]);
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// destroy the session
session_destroy();

//redirect to the log in page.
header("Location: ../login/login.html");
exit();
?>