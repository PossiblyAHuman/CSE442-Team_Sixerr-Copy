<?php
include '../functions.php';
session_start(); 

if (!isset($_SESSION["id"])) {
   echo "Edit Profile Page isn't displayed for any partiular user, please login first to access your specific homepage. </br> Please <button><a href='../login/login.html'>Log In</a></button> to continue.";
   die();
}else{
   $username = get_username_from_id($_SESSION["id"]);
   $CSS_dark_mode;
   if(!isset($_COOKIE[$cookie_name]))
   {
      //Sets the cookie for 10 years
      setcookie($cookie_name, "false", time() + (86400 * 30 * 12 * 10), "/");
   }

   if ($_COOKIE[$cookie_name] == "true"){
      //display darkmode
      $CSS_dark_mode = "
      <style>
         body {background-color: rgb(98, 94, 100);}
         nav{background-color: rgb(67, 64, 70);}
      </style>
      ";
   }
   else
   {
      //display lightmode
      $CSS_dark_mode = "";
   }
   include('create.html');
}


?>
